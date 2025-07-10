<?php
class TipoMonto extends AppModel {
	var $name = 'TipoMonto';
	var $useTable = 'tipo_monto';
	var $primaryKey = 'timo_id';
	
	var $validate = array(
	 	'timo_descripcion' => array('rule' => '/[\w\s]+/', 'required' => true, 'message' => 'Debe ingresar el nombre del tipo de monto')
	);
}
?>