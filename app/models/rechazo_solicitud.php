<?php
class RechazoSolicitud extends AppModel {
	var $name = 'RechazoSolicitud';
	var $useTable = 'rechazos_solicitudes';
	var $primaryKey = 'reso_id';
	
	var $belongsTo = array(
		'Usuario' => array(
			'className' => 'Usuario',
			'foreignKey' => 'usua_id'
		),
		'Solicitud' => array(
			'className' => 'Solicitud',
			'foreignKey' => 'soli_id'
		)
	);
	
	var $validate = array(
		'reso_motivo' => array('rule' => '/[\w\s]+/', 'required' => true, 'message' => 'Debe ingresar el motivo del rechazo')
	);
}
?>