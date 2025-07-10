<?php
class UnidadCompra extends AppModel {
	var $name = 'UnidadCompra';
	var $useTable = 'unidad_compra';
	var $primaryKey = 'unco_id';
	
	var $validate = array(
	 	'unco_descripcion' => array('rule' => '/[\w\s\-\_]+/', 'required' => true, 'message' => 'Debe ingresar el nombre de la unidad de compra')
	 );
}
?>