<?php
class Perfil extends AppModel {
	var $name = 'Perfil';
	var $primaryKey = 'perf_id';
	var $useTable = 'perfiles';
	
	var $validate = array(
		'perf_nombre' => array('rule' => '/[\w\s]+/', 'required' => false, 'message' => 'Debe ingresar el nombre del perfil')
	);
	
	var $hasMany = array(
	    'Permiso' => array('className' => 'Permiso',
						   'foreignKey' => 'perf_id')
	);
	
}
?>
