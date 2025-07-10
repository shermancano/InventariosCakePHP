<?php
class TipoContrato extends AppModel {
	var $name = 'TipoContrato';
	var $useTable = 'tipo_contrato';
	var $primaryKey = 'tico_id';
	
	 var $validate = array(
	 	'tico_descripcion' => array('rule' => '/[\w\s]+/', 'required' => true, 'message' => 'Debe ingresar el nombre del tipo de contrato')
	 );
}
?>