<?php
class Banco extends AppModel {
	var $name = 'Banco';
	var $primaryKey = 'banc_id';
	
	var $validate = array(
		'banc_nombre' => array('rule' => '/[\w\s]+/', 'required' => true, 'message' => 'Debe ingresar el nombre del banco')
	);
}
?>