<?php
class DependenciaVirtual extends AppModel {
	var $name = 'DependenciaVirtual';
	var $useTable = 'dependencias_virtuales';
	var $primaryKey = 'devi_id';
	var $displayField = 'devi_nombre';
	
	var $validate = array(
		'devi_nombre' => array(
			'notempty' => array(
				'rule' => array('notEmpty'),
				'message' => 'Debe ingresar el nombre de la dependencia.',
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