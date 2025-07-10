<?php
class DetalleDepreciacion extends AppModel {
	var $name = 'DetalleDepreciacion';
	var $useTable = 'detalle_depreciaciones';
	var $primaryKey = 'dede_id';
	
	function obtieneTodo($depr_id) {
		$sql = "select fami.fami_id
		              ,fami.fami_nombre
		              ,prod.prod_nombre
					  ,ubaf.ubaf_fecha_adquisicion
					  ,ubaf.ubaf_fecha_garantia
					  ,depr.depr_ipc
					  ,prop.prop_nombre
					  ,situ.situ_nombre
					  ,marc.marc_nombre
					  ,colo.colo_nombre
		              ,dede.*
				from detalle_depreciaciones as dede
				join ubicaciones_activos_fijos as ubaf using (ubaf_codigo)
				join productos as prod using (prod_id)
				join grupos as grup using (grup_id)
				join familias as fami using (fami_id)
				join depreciaciones as depr using (depr_id)
				left join propiedades as prop using (prop_id)
				left join situaciones as situ using (situ_id)
				left join marcas as marc using (marc_id)
				left join colores as colo using (colo_id) 
				where dede.depr_id = ".$depr_id."
				order by prod.prod_nombre, ubaf.ubaf_codigo asc";
		$res = $this->query($sql);
		$info = array();
		
		// ordenar por familia
		foreach ($res as $row) {
			$row = array_pop($row);
			$info[$row['fami_nombre']][] = $row;
		}
		
		return $info;
	}
	
	function obtieneAllByProducto($codigo_barra) {
		$sql = "select fami.fami_id
		              ,fami.fami_nombre
		              ,prod.prod_nombre
					  ,ubaf.ubaf_fecha_adquisicion
					  ,ubaf.ubaf_fecha_garantia
					  ,depr.depr_ipc
					  ,prop.prop_nombre
					  ,situ.situ_nombre
					  ,marc.marc_nombre
					  ,colo.colo_nombre
					  ,depr.depr_ano
		              ,dede.*
				from detalle_depreciaciones as dede
				join ubicaciones_activos_fijos as ubaf using (ubaf_codigo)
				join productos as prod using (prod_id)
				join grupos as grup using (grup_id)
				join familias as fami using (fami_id)
				join depreciaciones as depr using (depr_id)
				left join propiedades as prop using (prop_id)
				left join situaciones as situ using (situ_id)
				left join marcas as marc using (marc_id)
				left join colores as colo using (colo_id) 
				where dede.ubaf_codigo = '".$codigo_barra."'
				order by depr.depr_fecha asc";
		$res = $this->query($sql);		
		return $res;
	}
}