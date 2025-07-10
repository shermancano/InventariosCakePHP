<?php
class Evaluacion extends AppModel {
	var $name = 'Evaluacion';
	var $primaryKey = 'eval_id';
	var $useTable = 'evaluaciones';
	
	var $belongsTo = array(
	    'Contrato' => array('className' => 'Contrato',
	 	 	 				'foreignKey' => 'cont_id')
	);
	
	var $hasMany = array(
	    'DetalleEvaluacion' => array('className' => 'DetalleEvaluacion',
					      	         'foreignKey' => 'eval_id')
	);
	
	var $validate = array(
	   'cont_nombre' => array('rule' => '/[\w\s]+/', 'required' => false, 'message' => 'Debe seleccionar un contrato')
	);
	
	function findInfo($eval_id) {
		$sql = "select Contrato.cont_nombre
					  ,Proveedor.prov_nombre
					  ,Evaluacion.eval_observaciones
			    from evaluaciones as Evaluacion
			    	,contratos as Contrato
			    	,proveedores as Proveedor
			    where Evaluacion.cont_id = Contrato.cont_id
			    and   Contrato.prov_id = Proveedor.prov_id
			    and   Evaluacion.eval_id = ".$eval_id;
		$rs = $this->query($sql);
		return $rs;
	}
	
	function searchEvaluacion($strings) {
		$arr_strings = array();
	 	 
	 	 foreach ($strings as $string) {
	 	 	 $string = strtolower($string);
			 $string = trim($string);
	 	 	 $arr_strings[] = "lower(Contrato.cont_nombre) like '%".$string."%' or lower(Proveedor.prov_nombre) like '%".$string."%' or lower(Proveedor.prov_rut) like '%".$string."%'";
	 	 }
	 	 
	 	 $sql = "select Contrato.cont_nombre 
	 	 			   ,Evaluacion.eval_id
	 	 	     from contratos as Contrato
	 	 	         ,evaluaciones as Evaluacion
	 	 	         ,proveedores as Proveedor
	 	 	     where Contrato.cont_id = Evaluacion.cont_id
	 	 	     and   Contrato.prov_id = Proveedor.prov_id
	 	 	     and   (".implode("or ", $arr_strings).")";
				
	 	 $rs = $this->query($sql);
	 	 return $rs;
	}
	
	function getResumen($eval_id) {
		$info_cont = $this->findInfo($eval_id);
		$info_res = $this->DetalleEvaluacion->findResumenInfo($eval_id);
		
		$info_cont = array('cont_nombre' => $info_cont[0][0]['cont_nombre']
						  ,'prov_nombre' => $info_cont[0][0]['prov_nombre']
						  ,'eval_observaciones' => $info_cont[0][0]['eval_observaciones']);
		$deev_info = array();
		$total_pond = 0;
		
		foreach ($info_res['DetalleEvaluacion'] as $deev) {
			$sum = 0;
			$count = 0;
			
			foreach ($deev['Nota'] as $nota) {
				if ($nota['nota_nota'] != "" || $nota['nota_nota'] != null) {
					$sum += $nota['nota_nota'];
					$count += 1;
				}
			}
			
			if ($count > 0) {
				$deev['total'] = $sum;
				$deev['promedio'] = number_format(round($sum/$count, 1), 1, ".", "");
				$deev['pond'] = number_format(round($deev['promedio']*($deev['deev_ponderacion']/100), 1), 1, ".", "");
			} else {
				$deev['total'] = 0;
				$deev['promedio'] = 0;
				$deev['pond'] = 0;
			}
			$total_pond += $deev['pond'];
			$deev_info[] = $deev;
		}
		return json_encode(array('info_cont' => $info_cont, 'info_res' => $deev_info, 'total_pond' => number_format($total_pond, 1, ".", "")));
	}
	
	function getNotaFinal($cont_id) {
		$sql = "select promedio_evaluacion($cont_id) as nota_final";
		$rs = $this->query($sql);
		return $rs[0][0]['nota_final'];	
	}
}
?>