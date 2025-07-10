<?php
class MotivoBaja extends AppModel {
	var $name = 'MotivoBaja';
	var $useTable = 'motivos_bajas';
	var $primaryKey = 'moba_id';
	var $displayField = 'moba_nombre';
	
	var $validate = array(
		'moba_nombre' => array(
			'notempty' => array(
				'rule' => array('notEmpty'),
				'message' => 'Debe ingresar el nombre del motivo.',
				'alowempty' => false,
				'required' => true
			),
			'unique' => array(
				'rule' => array('isUnique'),
				'message' => 'El nombre ingresado ya se encuentra en uso.',
				'alowempty' => false,
				'required' => true
			)
		)
	);
}
?>