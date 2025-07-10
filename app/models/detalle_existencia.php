<?php
class DetalleExistencia extends AppModel {
	var $name = 'DetalleExistencia';
	var $primaryKey = 'deex_id';
	var $useTable = 'detalle_existencias';
	
	var $belongsTo = array(
		'Producto' => array(
			'className' => 'Producto',
			'foreignKey' => 'prod_id'
		),
		'Existencia' => array(
			'className' => 'Existencia',
			'foreignKey' => 'exis_id'
		)
	);

	function searchStockOnly($ceco_id, $term) {
		$term = strtolower($term);
		$term = trim($term);
		
		$sql = "select prod_id
					  ,prod_nombre
					  ,coalesce(entradas.total, 0) as total_entradas
					  ,coalesce(traslados.total, 0) as total_traslados
				from productos as prod
				left join (select deex.prod_id
								 ,sum(deex.deex_cantidad) as total
						   from detalle_existencias as deex
						   join existencias as exis using (exis_id)
						   where exis.tmov_id = 1
						   and   exis.ceco_id = ".$ceco_id."
						   and   exis.esre_id = 1
						   group by deex.prod_id) as entradas using (prod_id)
				left join (select deex.prod_id
								 ,sum(deex.deex_cantidad) as total
						   from detalle_existencias as deex
						   join existencias as exis using (exis_id)
						   where exis.tmov_id = 2
						   and   exis.ceco_id = ".$ceco_id."
						   and   exis.esre_id = 1
						   group by deex.prod_id) as traslados using (prod_id)
				where (lower(prod.prod_nombre) like '%".$term."%'
				or lower(prod.prod_nombre_fantasia) like '%".$term."%'
				or lower(prod.prod_codigo) like '%".$term."%')
				and (coalesce(entradas.total, 0) - coalesce(traslados.total, 0)) > 0";
		
		$rs = $this->query($sql);
		return $rs;
	}
	
	function searchAllByProdCc($ceco_id, $prod_id) {
		$sql = "select Producto.prod_id
					  ,Producto.prod_nombre
					  ,DetalleExistencia.deex_serie
					  ,DetalleExistencia.deex_fecha_vencimiento
					  ,(total_entradas_exis(".$ceco_id.", ".$prod_id.", DetalleExistencia.deex_serie, DetalleExistencia.deex_fecha_vencimiento, DetalleExistencia.deex_precio)- total_traslados_exis(".$ceco_id.", ".$prod_id.", DetalleExistencia.deex_serie, DetalleExistencia.deex_fecha_vencimiento, DetalleExistencia.deex_precio)) as total
					  ,DetalleExistencia.deex_precio
				from detalle_existencias as DetalleExistencia
				join productos as Producto using (prod_id)
				join existencias as Existencia using (exis_id)
				where Existencia.tmov_id = 1
				and   Existencia.esre_id = 1
				and   Existencia.ceco_id = ".$ceco_id."
				and   Producto.prod_id = ".$prod_id."
				group by Producto.prod_id
						,Producto.prod_nombre
						,DetalleExistencia.deex_serie
						,DetalleExistencia.deex_fecha_vencimiento
						,DetalleExistencia.deex_precio
				order by Producto.prod_nombre,
						 DetalleExistencia.deex_fecha_vencimiento";
		$rs = $this->query($sql);
		return $rs;
	}

	function saveTraslado($exis_id, $data) {
	
		foreach ($data['DetalleExistencia'] as $row) {
			// se descartan traslados de 0 cantidad de items
			if ($row['deex_cantidad'] > 0) {
				$this->create();
				$info = array();
				$info['DetalleExistencia'] = $row;
				$info['DetalleExistencia']['exis_id'] = $exis_id;
				
				if (!$this->save($info)) {
					return false;
				}
			}
		}
		return true;
	}
	
	function updTraslado($exis_id, $data) {
		// guardamos padre
		foreach ($data['DetalleExistencia'] as $row) {
			// se descartan traslados de 0 cantidad de items
			if ($row['deex_cantidad'] > 0) {
				$info = array();
				$info['DetalleExistencia'] = $row;
				$info['DetalleExistencia']['exis_id'] = $exis_id;
				$this->create();
				
				if (!$this->save($info)) {
					return false;
				}
			}
		}
		
		$sql = "select exis_id from existencias where exis_id_padre = ".$exis_id;
		$res = $this->query($sql);
		$exis_id_hijo = $res[0][0]['exis_id'];
		
		// borramos detalle del hijo
		$sql = "delete from detalle_existencias where exis_id = ".$exis_id_hijo;
		$res = $this->query($sql);
		
		// guardamos hijo
		foreach ($data['DetalleExistencia'] as $row) {
			// se descartan traslados de 0 cantidad de items
			if ($row['deex_cantidad'] > 0) {
				$info = array();
				unset($row['deex_id']);
				$info['DetalleExistencia'] = $row;
				$info['DetalleExistencia']['exis_id'] = $exis_id_hijo;
				$this->create();
				
				if (!$this->save($info)) {
					return false;
				}
				
			}
		}
		
		return true;
	}
}
?>
