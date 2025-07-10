<?php
class Familia extends AppModel {
	var $name = 'Familia';
	var $primaryKey = 'fami_id';
	
	var $validate = array(
	    'fami_nombre' => array('rule' => '/[\w\s]+/', 'required' => true, 'message' => 'Debe ingresar el nombre de la familia'),
		'tifa_id' => array('rule' => 'numeric', 'required' => true, 'message' => 'Debe seleccionar el tipo de familia'),
		'cuco_id' => array('rule' => 'numeric', 'required' => true, 'message' => 'Debe seleccionar la cuenta contable')
	);
	
	var $belongsTo = array(
		'TipoFamilia' => array(
			'className' => 'TipoFamilia',
			'foreignKey' => 'tifa_id'
		),
		'CuentaContable' => array(
			'className' => 'CuentaContable',
			'foreignKey' => 'cuco_id'
		)
	);
}
