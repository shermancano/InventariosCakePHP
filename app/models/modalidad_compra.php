<?php
class ModalidadCompra extends AppModel {
	var $name = 'ModalidadCompra';
	var $useTable = 'modalidad_compra';
	var $primaryKey = 'moco_id';
	
	var $validate = array(
	 	'moco_descripcion' => array('rule' => '/[\w\s]+/', 'required' => true, 'message' => 'Debe ingresar el nombre de la modalidad de compra')
	);
}
?>