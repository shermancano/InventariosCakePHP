<?php
class CuentaContable extends AppModel {
	var $name = 'CuentaContable';
	var $useTable = 'cuentas_contables';
	var $displayField = 'cuco_nombre';
	var $primaryKey = 'cuco_id';
	
	var $validate = array(
		'cuco_nombre' => array('rule' => '/[\w\s]+/', 'required' => true, 'message' => 'Debe ingresar el nombre de la cuenta contable')
	);
	
	function gastosCtaContable($cuco_id, $ceco_id = null) {
		$and = "";
		if ($ceco_id != null) {
			$and = "and ceco.ceco_id in (".implode(",", $ceco_id).")\n";
			
		}
		
		$sql = "select prod.prod_id
					  ,ubaf.ubaf_codigo
					  ,prod.prod_nombre
					  ,tibi.tibi_nombre
					  ,prop.prop_nombre
					  ,situ.situ_nombre
					  ,marc.marc_nombre
					  ,colo.colo_nombre
					  ,ubaf.ubaf_fecha_garantia
					  ,ubaf.ubaf_precio
					  ,ubaf.ubaf_depreciable
					  ,ubaf.ubaf_vida_util
					  ,ceco.ceco_nombre
					  ,ceco.ceco_id_padre 
					  ,padre.ceco_nombre as padre
					  ,abuelo.ceco_nombre as abuelo
					  ,grup.grup_nombre
					  ,fami.fami_nombre
				from ubicaciones_activos_fijos as ubaf
				join productos as prod using (prod_id)
				join tipos_bienes as tibi using (tibi_id)
				join grupos as grup using (grup_id)
				join familias as fami using (fami_id)
				left join propiedades as prop using (prop_id)
				left join situaciones as situ using (situ_id)
				left join marcas as marc using (marc_id)
				left join colores as colo using (colo_id)
				join centros_costos as ceco using (ceco_id)
				left join centros_costos as padre on (ceco.ceco_id_padre = padre.ceco_id)
				left join centros_costos as abuelo on (padre.ceco_id_padre = abuelo.ceco_id)
				where fami.cuco_id = ".$cuco_id."
				".$and." 
				order by prod.prod_nombre asc";
				
		$res = $this->query($sql);
		$ret['ACTIVOS FIJOS'] = $res;
		
		$sql = "select prod.prod_id
					  ,prod.prod_codigo
					  ,prod.prod_nombre
					  ,tibi.tibi_nombre
					  ,fami.fami_nombre
					  ,grup.grup_nombre
					  ,deex.deex_cantidad
					  ,deex.deex_precio
					  ,deex.deex_serie
					  ,deex.deex_fecha_vencimiento
					  ,ceco.ceco_nombre
				from detalle_existencias as deex
				join existencias as exis using (exis_id)
				join productos as prod using (prod_id)
				join tipos_bienes as tibi using (tibi_id)
				join grupos as grup using (grup_id)
				join familias as fami using (fami_id)
				join cuentas_contables as cuco using (cuco_id)
				join centros_costos as ceco using (ceco_id)
				where cuco.cuco_id = ".$cuco_id."
				and   exis.tmov_id = 1
				".$and."
				order by prod.prod_nombre asc";
				
		$res = $this->query($sql);
		$ret['EXISTENCIAS'] = $res;
		
		return $ret;
	}
}
?>
