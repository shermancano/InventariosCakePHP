<?php
class TipoItem extends AppModel {
	var $name = 'TipoItem';
	var $primaryKey = 'tiit_id';
	var $useTable = 'tipo_item';
	
	var $hasMany = array(
		'Item' => array('className' => 'Item',
						'foreignKey' => 'tiit_id')
	);
	
	var $validate = array(
		'tiit_descripcion' => array('rule' => '/[\w\s]+/', 'required' => true, 'message' => 'Debe ingresar el nombre del tipo de item')
	);
}
?>