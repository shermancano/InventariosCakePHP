<?php
class Rubro extends AppModel {
	var $name = 'Rubro';
	var $primaryKey = 'rubr_id';
	
	 var $validate = array(
	 	'rubr_descripcion' => array('rule' => '/[\w\s]+/', 'required' => true, 'message' => 'Debe ingresar el nombre del rubro interno')
	 );
}
?>