<?php
class Solicitud extends AppModel {
	var $name = 'Solicitud';	
	var $useTable = 'solicitudes';
	var $primaryKey = 'soli_id';
	
	var $belongsTo = array(
		'TipoSolicitud' => array(
			'className' => 'TipoSolicitud',
			'foreignKey' => 'tiso_id',
			'conditions' => '',
			'fields' => '',
			'order' => ''
		),
		'CentroCosto' => array(
			'className' => 'CentroCosto',
			'foreignKey' => 'ceco_id',
			'conditions' => '',
			'fields' => '',
			'order' => ''			
		),
		'CentroCosto2' => array(
			'className' => 'CentroCosto',
			'foreignKey' => 'ceco_id_hacia',
			'conditions' => '',
			'fields' => '',
			'order' => ''			
		),
		'Proveedor' => array(
			'className' => 'Proveedor',
			'foreignKey' => 'prov_id',
			'conditions' => '',
			'fields' => '',
			'order' => ''
		),
		'EstadoSolicitud' => array(
			'className' => 'EstadoSolicitud',
			'foreignKey' => 'esso_id',
			'conditions' => '',
			'fields' => '',
			'order' => ''
		)
	);
	
	var $hasMany = array(
		'DetalleSolicitud' => array(
			'className' => 'DetalleSolicitud',
			'foreignKey' => 'soli_id',
			'conditions' => '',
			'fields' => '',
			'order' => ''
		),
		'RechazoSolicitud' => array(
			'className' => 'RechazoSolicitud',
			'foreignKey' => 'soli_id',
			'conditions' => '',
			'fields' => '',
			'order' => ''
		)
	);
	
	var $validate = array (
		'tiso_id' => array(
			'notempty' => array(
				'rule' => array('notempty'),
				'message' => 'Debe seleccionar el tipo de solicitud',
				'alowempty' => false,
				'required' => true
			)
		),
		'soli_fecha' => array(
			'notempty' => array(
				'rule' => array('notempty'),
				'message' => 'Debe ingresar la fecha de solicitud',
				'alowempty' => false,
				'required' => true
			)
		),
		'soli_comentario' => array(
			'notempty' => array(
				'rule' => array('notempty'),
				'message' => 'Debe ingresar observaciones',
				'alowempty' => false,
				'required' => true
			)
		),
		'esso_id' => array(
			'notempty' => array(
				'rule' => array('notempty'),
				'message' => 'Debe ingresar el estado de la solicitud',
				'alowempty' => false,
				'required' => true
			)
		)
	);
	
	function obtieneCorrelativo($ceco_id) {
		$sql = "select coalesce(max(soli_correlativo), 0) + 1 as soli_correlativo
				from solicitudes
				where ceco_id = ".$ceco_id;
		$res = $this->query($sql);
		
		return $res[0][0]['soli_correlativo'];
	}
	
	function infoMailNuevaSolicitudCC($ceco_id, $soli_id) {
		$sql = "select ceco_pad.ceco_nombre as ceco_nombre_padre
					  ,soli.soli_correlativo as correlativo
					  ,ceco_hij.ceco_nombre as ceco_nombre_hacia
					  ,soli.soli_id as soli_id
					  ,(select conf_valor
					    from configuraciones
						where conf_id = 'site_logo') as logo
				from solicitudes as soli
				join centros_costos as ceco_pad on (soli.ceco_id = ceco_pad.ceco_id)
				join centros_costos as ceco_hij on (soli.ceco_id_hacia = ceco_hij.ceco_id)
				where soli.soli_id = ".$soli_id;
		$res = $this->query($sql);
		$vars = $res[0][0];
		
		$sql = "select usua.usua_email
				from responsables as resp
				join usuarios as usua using (usua_id)
				where resp.ceco_id = ".$ceco_id;
		$res = $this->query($sql);
		$responsables = array();
		
		foreach ($res as $row) {
			$row = array_pop($row);
			$responsables[] = $row['usua_email'];
		}
		
		return array($vars, $responsables);
	}
	
	function infoMailNuevaSolicitudProv($prov_id, $soli_id) {
		$sql = "select ceco_pad.ceco_nombre as ceco_nombre_padre
					  ,soli.soli_correlativo as correlativo
					  ,soli.soli_id as soli_id
					  ,(select conf_valor
					    from configuraciones
						where conf_id = 'site_logo') as logo
					  ,(select conf_valor
					    from configuraciones
						where conf_id = 'site_title') as site_title
					  ,prov.prov_nombre
					  ,prov.prov_contacto
					  ,prov.prov_email
				from solicitudes as soli
				join centros_costos as ceco_pad on (soli.ceco_id = ceco_pad.ceco_id)
				join proveedores as prov using (prov_id)
				where soli.soli_id = ".$soli_id;
	
		$res = $this->query($sql);
		$vars = $res[0][0];
		
		return $vars;
	}
	
	function infoMailRechazaSolicitud($soli_id) {
		$sql = "select ceco_pad.ceco_nombre as ceco_nombre_padre
		              ,ceco_hij.ceco_nombre as ceco_nombre_hijo
					  ,soli.soli_correlativo as correlativo
					  ,soli.soli_id as soli_id
					  ,(select conf_valor
					    from configuraciones
						where conf_id = 'site_logo') as logo
					  ,prov.prov_nombre
				from solicitudes as soli
				left join centros_costos as ceco_hij on (soli.ceco_id_hacia = ceco_hij.ceco_id)
				left join centros_costos as ceco_pad on (soli.ceco_id = ceco_pad.ceco_id)
				left join proveedores as prov using (prov_id)
				where soli.soli_id = ".$soli_id;
	
		$res = $this->query($sql);
		$vars = $res[0][0];
		
		$sql = "select usua_email
				from solicitudes 
				join responsables as resp using (ceco_id)
				join usuarios as usua using (usua_id)
				where soli_id = ".$soli_id;
		
		$responsables = array();
		$res = $this->query($sql);
		foreach ($res as $row) {
			$row = array_pop($row);
			$responsables[] = $row['usua_email'];
		}
		
		return array($vars, $responsables);
	}
	
	function infoMailAceptaSolicitud($soli_id) {
		$sql = "select ceco_pad.ceco_nombre as ceco_nombre_padre
		              ,ceco_hij.ceco_nombre as ceco_nombre_hijo
					  ,soli.soli_correlativo as correlativo
					  ,soli.soli_id as soli_id
					  ,(select conf_valor
					    from configuraciones
						where conf_id = 'site_logo') as logo
					  ,prov.prov_nombre
				from solicitudes as soli
				left join centros_costos as ceco_hij on (soli.ceco_id_hacia = ceco_hij.ceco_id)
				left join centros_costos as ceco_pad on (soli.ceco_id = ceco_pad.ceco_id)
				left join proveedores as prov using (prov_id)
				where soli.soli_id = ".$soli_id;
	
		$res = $this->query($sql);
		$vars = $res[0][0];
		
		$sql = "select usua_email
				from solicitudes 
				join responsables as resp using (ceco_id)
				join usuarios as usua using (usua_id)
				where soli_id = ".$soli_id;
		
		$responsables = array();
		$res = $this->query($sql);
		foreach ($res as $row) {
			$row = array_pop($row);
			$responsables[] = $row['usua_email'];
		}
		
		return array($vars, $responsables);
	}
	
	function aceptaSolicitud($soli_id) {
		$sql = "update solicitudes set esso_id = 1 where soli_id = ".$soli_id;
		$res = $this->query($sql);
		
		if (is_array($res) == false) {
			return false;
		}
		
		return true;
	}
	
	function rechazaSolicitud($soli_id) {
		$sql = "update solicitudes set esso_id = 2 where soli_id = ".$soli_id;
		$res = $this->query($sql);
		
		if (is_array($res) == false) {
			return false;
		}
		
		return true;
	}
}
?>