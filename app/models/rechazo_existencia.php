<?php
class RechazoExistencia extends AppModel {
	var $name = 'RechazoExistencia';
	var $useTable = 'rechazos_existencias';
	var $primaryKey = 'reex_id';
	
	var $belongsTo = array(
		'Usuario' => array(
			'className' => 'Usuario',
			'foreignKey' => 'usua_id'
		),
		'Existencia' => array(
			'className' => 'Existencia',
			'foreignKey' => 'exis_id'
		)
	);
	
	var $validate = array(
		'reex_motivo' => array('rule' => '/[\w\s]+/', 'required' => true, 'message' => 'Debe ingresar el motivo del rechazo')
	);
}
?>