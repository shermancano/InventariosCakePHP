<?php
class Color extends AppModel {
	var $name = 'Color';
	var $useTable = 'colores';
	var $primaryKey = 'colo_id';
	
	var $validate = array(
		'colo_nombre' => array('rule' => '/[\w\s]+/', 'required' => true, 'message' => 'Debe ingresar el nombre del color')
	);
}
?>
