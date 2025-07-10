<?php
class Financiamiento extends AppModel {
	var $name = 'Financiamiento';
	var $primaryKey = 'fina_id';
	var $useTable = 'financiamientos';
	var $displayField = 'fina_nombre';
	
	var $validate = array(
		'fina_nombre' => array('rule' => '/[\w\s]+/', 'required' => true, 'message' => 'Debe ingresar el nombre del financiamiento')
	);
}
?>