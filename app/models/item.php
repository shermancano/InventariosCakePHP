<?php
class Item extends AppModel {
	var $name = 'Item';
	var $primaryKey = 'item_id';
	var $useTable = 'items';
	
	var $belongsTo = array(
	 	 'TipoItem' => array('className' => 'TipoItem',
	 	 	 				 'foreignKey' => 'tiit_id')
	);
	
	var $validate = array(
	 	'item_descripcion' => array('rule' => '/[\w\s]+/', 'required' => true, 'message' => 'Debe ingresar la descripcion del item')
	);
}
?>
