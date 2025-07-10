<?php
class Situacion extends AppModel {
	var $name = 'Situacion';
	var $useTable = 'situaciones';
	var $primaryKey = 'situ_id';
	
	var $validate = array(
		'situ_nombre' => array('rule' => '/[\w\s]+/', 'required' => true, 'message' => 'Debe ingresar el nombre de la situacion')
	);
}
?>
