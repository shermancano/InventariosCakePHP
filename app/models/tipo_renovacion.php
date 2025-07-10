<?php
class TipoRenovacion extends AppModel {
	var $name = 'TipoRenovacion';
	var $useTable = 'tipo_renovacion';
	var $primaryKey = 'tire_id';
	
	var $validate = array(
	 	'tire_descripcion' => array('rule' => '/[\w\s]+/', 'required' => true, 'message' => 'Debe ingresar el nombre del tipo de renovacion')
	);
}
?>