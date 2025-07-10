<?php
class Propiedad extends AppModel {
	var $name = 'Propiedad';
	var $useTable = 'propiedades';
	var $primaryKey = 'prop_id';
	
	 var $validate = array(
	 	'prop_nombre' => array('rule' => '/[\w\s]+/', 'required' => false, 'message' => 'Debe ingresar el nombre de la propiedad')
	 );
}
?>
