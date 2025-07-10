<?php
class Existencia extends AppModel {
	var $name = 'Existencia';
	var $useTable = 'existencias';
	var $primaryKey = 'exis_id';
	
	var $belongsTo = array(
		'CentroCosto' => array(
			'className' => 'CentroCosto',
			'foreignKey' => 'ceco_id'
		),
		'CentroCostoPadre' => array(
			'className' => 'CentroCosto',
			'foreignKey' => 'ceco_id_padre'
		),
		'CentroCostoHijo' => array(
			'className' => 'CentroCosto',
			'foreignKey' => 'ceco_id_hijo'
		),
		'TipoDocumento' => array(
			'className' => 'TipoDocumento',
			'foreignKey' => 'tido_id'
		),
		'Proveedor' => array(
			'className' => 'Proveedor',
			'foreignKey' => 'prov_id'
		),
		'ExistenciaPadre' => array(
			'className' => 'Existencia',
			'foreignKey' => 'exis_id_padre'
		)
	); 
	
	var $hasMany = array(
	    'DetalleExistencia' => array('className' => 'DetalleExistencia',
									 'foreignKey' => 'exis_id',
									 'order' => 'DetalleExistencia.prod_id asc'),
		'RechazoExistencia' => array('className' => 'RechazoExistencia',
									 'foreignKey' => 'exis_id')
	);
	
	var $validate = array(
		'prov_id' => array('rule' => 'numeric', 'required' => true, 'message' => 'Debe seleccionar el proveedor')
	);
	
	function obtieneCorrelativo($ceco_id, $tmov_id) {
		$sql = "select coalesce(max(exis_correlativo), 0) + 1 as exis_correlativo
				from existencias
				where ceco_id = ".$ceco_id."
				and   tmov_id = ".$tmov_id;
		$res = $this->query($sql);
		
		return $res[0][0]['exis_correlativo'];
	}
	
	function aceptaRecepcion($exis_id) {
		$info = $this->read('Existencia.exis_id_padre', $exis_id);
		$exis_id_padre = $info['Existencia']['exis_id_padre'];
		
		$sql = "update existencias set esre_id = 1 where exis_id in (".$exis_id.", ".$exis_id_padre.")";
		$rs = $this->query($sql);
		
		return true;
	}
	
	function rechazaRecepcion($exis_id) {
		$info = $this->read('Existencia.exis_id_padre', $exis_id);
		$exis_id_padre = $info['Existencia']['exis_id_padre'];
		
		$sql = "update existencias set esre_id = 2 where exis_id in (".$exis_id.", ".$exis_id_padre.")";
		$rs = $this->query($sql);
		
		return true;
	}
	
	function infoMailAceptaRecepcion($exis_id) {
		$sql = "select ceco_pad.ceco_nombre as ceco_nombre_padre
					  ,exis_pad.exis_correlativo as correlativo
					  ,ceco_hij.ceco_nombre as ceco_nombre_hijo
					  ,exis_pad.exis_id as exis_id
					  ,exis_pad.ceco_id
					  ,(select conf_valor
					    from configuraciones
						where conf_id = 'site_logo') as logo
				from existencias as exis_hij
				join existencias as exis_pad on (exis_hij.exis_id_padre = exis_pad.exis_id)
				join centros_costos as ceco_pad on (exis_hij.ceco_id_padre = ceco_pad.ceco_id)
				join centros_costos as ceco_hij on (exis_hij.ceco_id = ceco_hij.ceco_id)
				where exis_hij.exis_id = ".$exis_id;
		$res = $this->query($sql);
		$vars = $res[0][0];
		
		// rescatamos a los responsables
		$sql = "select usua.usua_email
				from responsables as resp
				join usuarios as usua using (usua_id)
				where resp.esre_id = 1
				and   resp.ceco_id = ".$vars['ceco_id'];
		
		$res = $this->query($sql);
		$responsables = array();
		
		foreach ($res as $row) {
			$row = array_pop($row);
			$responsables[] = $row['usua_email'];
		}
		
		return array($vars, $responsables);
	}
	
	function infoMailRechazaRecepcion($exis_id) {
		return $this->infoMailAceptaRecepcion($exis_id);
	}
	
	function infoMailNuevoTraslado($exis_id) {
		$sql = "select ceco_hij.ceco_nombre as ceco_nombre_hijo
					  ,exis.exis_correlativo as correlativo
					  ,ceco_pad.ceco_nombre as ceco_nombre_padre
					  ,exis.exis_id as exis_id
					  ,ceco_hij.ceco_id
					  ,(select conf_valor
						from configuraciones
						where conf_id = 'site_logo') as logo
				from existencias as exis
				join centros_costos as ceco_pad on (exis.ceco_id_padre = ceco_pad.ceco_id)
				join centros_costos as ceco_hij on (exis.ceco_id = ceco_hij.ceco_id)
				where exis.exis_id = ".$exis_id;
				
		$res = $this->query($sql);
		$vars = $res[0][0];
		
		// rescatamos a los responsables
		$sql = "select usua.usua_email
				from responsables as resp
				join usuarios as usua using (usua_id)
				where resp.esre_id = 1
				and   resp.ceco_id = ".$vars['ceco_id'];
		
		$res = $this->query($sql);
		$responsables = array();
		
		foreach ($res as $row) {
			$row = array_pop($row);
			$responsables[] = $row['usua_email'];
		}
		
		return array($vars, $responsables);
	}
	
	function infoMailEditTraslado($exis_id) {
		$sql = "select ceco_pad.ceco_nombre as ceco_nombre_padre
					  ,ceco_hij.ceco_nombre as ceco_nombre_hijo
					  ,exis_hij.exis_id
					  ,exis_hij.exis_correlativo as correlativo
					  ,(select conf_valor
						from configuraciones
						where conf_id = 'site_logo') as logo
				from existencias as exis
				join centros_costos as ceco_pad on (exis.ceco_id = ceco_pad.ceco_id)
				join centros_costos as ceco_hij on (exis.ceco_id_hijo = ceco_hij.ceco_id)
				join existencias as exis_hij on (exis.exis_id = exis_hij.exis_id_padre)
				where exis.exis_id = ".$exis_id;
		$res = $this->query($sql);
		$vars = $res[0][0];
		
		// rescatamos a los responsables
		$sql = "select usua.usua_email
				from responsables as resp
				join usuarios as usua using (usua_id)
				where resp.esre_id = 1
				and   resp.ceco_id = (select ceco_id from existencias where exis_id_padre = ".$exis_id.")";
		
		$res = $this->query($sql);
		$responsables = array();
		
		foreach ($res as $row) {
			$row = array_pop($row);
			$responsables[] = $row['usua_email'];
		}
		
		return array($vars, $responsables);
	}
	
	function itemsTransito() {
		$sql = "select prod.prod_id
					  ,prod.prod_nombre
					  ,prod.prod_codigo
					  ,tibi.tibi_nombre
					  ,fami.fami_nombre
					  ,grup.grup_nombre
					  ,deex.deex_cantidad
					  ,deex.deex_precio
					  ,deex.deex_serie
					  ,deex.deex_fecha_vencimiento
					  ,tmov.tmov_descripcion
					  ,ceco_pad.ceco_nombre as ceco_padre
					  ,ceco.ceco_nombre
					  ,ceco_hij.ceco_nombre as ceco_hijo
				from detalle_existencias as deex
				join existencias as exis using (exis_id)
				join productos as prod using (prod_id)
				join tipos_bienes as tibi using (tibi_id)
				join grupos as grup using (grup_id)
				join familias as fami using (fami_id)
				join tipo_movimientos as tmov using (tmov_id)
				join centros_costos as ceco using (ceco_id)
				left join centros_costos as ceco_pad on (ceco_pad.ceco_id = exis.ceco_id_padre)
				left join centros_costos as ceco_hij on (ceco_hij.ceco_id = exis.ceco_id_hijo)
				where exis.esre_id = 2
				order by deex.deex_id desc";
		$rs = $this->query($sql);
		
		return $rs;
	}
	
	function searchAll($string, $ceco_id, $tmov_id) {
		$string = strtolower($string);
		
		$sql = "select distinct 
					   exis.exis_correlativo
					  ,exis.exis_fecha
					  ,exis.exis_id
				from detalle_existencias as deex
				join existencias as exis using (exis_id)
				join productos as prod using (prod_id)
				left join proveedores as prov using (prov_id)
				where exis.tmov_id = ".$tmov_id."
				and   exis.esre_id = 1
				and   exis.ceco_id = ".$ceco_id."
				and   (to_char(exis.exis_fecha, 'DD-MM-YYYY')                like '%".$string."%'
					   or lower(prov.prov_nombre)                            like '%".$string."%'
					   or lower(prod.prod_nombre)                            like '%".$string."%'
					   or lower(prod.prod_nombre_fantasia)                   like '%".$string."%'
					   or lower(deex.deex_serie)                             like '%".$string."%'
					   or to_char(deex.deex_fecha_vencimiento, 'DD-MM-YYYY') like '%".$string."%'
					   or lower(exis.exis_orden_compra)                      like '%".$string."%'
					   or lower(exis.exis_nro_documento)                     like '%".$string."%'
					   or to_char(exis.exis_fecha_compra, 'DD-MM-YYYY')      like '%".$string."%')";
		$res = $this->query($sql);
		return $res;		
	}
}

?>
