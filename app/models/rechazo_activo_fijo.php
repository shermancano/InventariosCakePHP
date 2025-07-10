<?php
class RechazoActivoFijo extends AppModel {
	var $name = 'RechazoActivoFijo';
	var $useTable = 'rechazos_activos_fijos';
	var $primaryKey = 'reaf_id';
	
	var $belongsTo = array(
		'Usuario' => array(
			'className' => 'Usuario',
			'foreignKey' => 'usua_id'
		),
		'ActivoFijo' => array(
			'className' => 'ActivoFijo',
			'foreignKey' => 'acfi_id'
		)
	);
	
	var $validate = array(
		'reaf_motivo' => array('rule' => '/[\w\s]+/', 'required' => true, 'message' => 'Debe ingresar el motivo del rechazo')
	);
}
?>