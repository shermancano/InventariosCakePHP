<?php
class DetalleEvaluacion extends AppModel {
	var $name = 'DetalleEvaluacion';
	var $primaryKey = 'deev_id';
	var $useTable = 'detalle_evaluacion';
	
	var $belongsTo = array(
	 	 'Evaluacion' => array('className' => 'Evaluacion',
	 	 	 				   'foreignKey' => 'eval_id')
	);
	
	var $hasMany = array(
	    'Nota' => array('className' => 'Nota',
					    'foreignKey' => 'deev_id')
	);
	
	function saveDetalleEvaluacion($data) {
		$cont_id = $data['Contrato']['cont_id'];
		$eval_obs = $data['Evaluacion']['eval_observaciones'];
		$this->begin();
		
		foreach ($data['DetalleEvaluacion'] as $deeval) {
			if ($this->Evaluacion->save(array('Evaluacion' => array('cont_id' => $cont_id, 'eval_fecha' => date("Y-m-d"), 'eval_observaciones' => $eval_obs)))) {
				$deeval['eval_id'] = $this->Evaluacion->id;
				$this->create();
				
				if ($this->save(array('DetalleEvaluacion' => $deeval))) {
					$deev_id = $this->id;
					
					foreach ($deeval['Nota'] as $nota) {
						$nota['deev_id'] = $deev_id;
						$this->Nota->create();
						
						if (!$this->Nota->save(array('Nota' => $nota))) {
							$this->rollback();
							return false;		
						}
					}
				} else {
					$this->rollback();
					return false;	
				}
			} else {
				$this->rollback();
				return false;
			}
		}
		
		$this->commit();
		return true;
	}
	
	function updateDetalleEvaluacion($data) {
		$cont_id = $data['Contrato']['cont_id'];
		$eval_id = $data['Evaluacion']['eval_id'];
		$eval_obs = $data['Evaluacion']['eval_observaciones'];
		$this->begin();
		
		if (!$this->Evaluacion->save(array('Evaluacion' => array('eval_id' => $eval_id, 'cont_id' => $cont_id, 'eval_fecha_modificacion' => date("Y-m-d"), 'eval_observaciones' => $eval_obs)))) {
			$this->rollback();
			return false;
		}
		
		foreach ($data['DetalleEvaluacion'] as $deeval) {
			$deeval['eval_id'] = $eval_id;
			$this->create();
				
			if ($this->save(array('DetalleEvaluacion' => $deeval))) {
				$deev_id = $this->id;
				
				foreach ($deeval['Nota'] as $nota) {
					$nota['deev_id'] = $deev_id;
					$this->Nota->create();
					
					if (!isset($nota['nota_nota']))
						$nota['nota_nota'] = "";
					
					if (!$this->Nota->save(array('Nota' => $nota))) {
						$this->rollback();
						return false;		
					}
				}
			} else {
				$this->rollback();
				return false;	
			}
		}
					
		$this->commit();
		return true;
	}
	
	function findEditInfo($eval_id) {
		$sql = "select DetalleEvaluacion.deev_id
					  ,DetalleEvaluacion.deev_ponderacion
					  ,TipoItem.tiit_descripcion
					  ,TipoItem.tiit_id
					  ,Item.item_descripcion
					  ,Item.item_id
					  ,Nota.nota_nota
					  ,Nota.nota_id
				from tipo_item as TipoItem
				natural join items as Item
				left join notas as Nota using (item_id)
				left join detalle_evaluacion as DetalleEvaluacion2 using (deev_id)
				left join (select * from detalle_evaluacion where eval_id = $eval_id) as DetalleEvaluacion using (deev_id)
				where DetalleEvaluacion2.eval_id = $eval_id
				order by TipoItem.tiit_id";
		$rs_nota = $this->query($sql);
		$info = array();
		$notas = array();
		
		foreach ($rs_nota as $data) {
			$data = array_pop($data);
			$notas[$data['tiit_id']][] = array('item_descripcion' => $data['item_descripcion']
											  ,'item_id' => $data['item_id']
											  ,'nota_id' => $data['nota_id']
											  ,'nota_nota' => $data['nota_nota']);
			
			if (!isset($info['DetalleEvaluacion'][$data['tiit_id']])) {
			
			$info['DetalleEvaluacion'][$data['tiit_id']] = array('deev_id' => $data['deev_id']
														     	,'tiit_descripcion' => $data['tiit_descripcion']
														     	,'tiit_id' => $data['tiit_id']
																,'deev_ponderacion' => $data['deev_ponderacion']);
			}
			$info['DetalleEvaluacion'][$data['tiit_id']]['Nota'] = $notas[$data['tiit_id']];
		}
		return $info;
	}
	
	function findResumenInfo($eval_id) {
		$sql = "select DetalleEvaluacion.deev_id
					  ,DetalleEvaluacion.deev_ponderacion
					  ,TipoItem.tiit_descripcion
					  ,TipoItem.tiit_id
					  ,Item.item_descripcion
					  ,Item.item_id
					  ,Nota.nota_nota
					  ,Nota.nota_id
				from tipo_item as TipoItem
				natural join items as Item
				left join notas as Nota using (item_id)
				left join detalle_evaluacion as DetalleEvaluacion2 using (deev_id)
				left join (select * from detalle_evaluacion where eval_id = $eval_id) as DetalleEvaluacion using (deev_id)
				where DetalleEvaluacion2.eval_id = $eval_id
				and   Nota.nota_nota is not null
				order by TipoItem.tiit_id";
		$rs_nota = $this->query($sql);
		$info = array();
		$notas = array();
		
		foreach ($rs_nota as $data) {
			$data = array_pop($data);
			$notas[$data['tiit_id']][] = array('item_descripcion' => $data['item_descripcion']
											  ,'item_id' => $data['item_id']
											  ,'nota_id' => $data['nota_id']
											  ,'nota_nota' => $data['nota_nota']);
			
			if (!isset($info['DetalleEvaluacion'][$data['tiit_id']])) {
			
			$info['DetalleEvaluacion'][$data['tiit_id']] = array('deev_id' => $data['deev_id']
														     	,'tiit_descripcion' => $data['tiit_descripcion']
														     	,'tiit_id' => $data['tiit_id']
																,'deev_ponderacion' => $data['deev_ponderacion']);
			}
			$info['DetalleEvaluacion'][$data['tiit_id']]['Nota'] = $notas[$data['tiit_id']];
		}
		return $info;
	}
	
}
?>
