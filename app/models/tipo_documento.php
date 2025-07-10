<?php
class TipoDocumento extends AppModel {
	var $name = 'TipoDocumento';
	var $useTable = 'tipos_documentos';
	var $primaryKey = 'tido_id';
	
	var $validate = array(
		'tido_descripcion' => array('rule' => '/[\w\s]+/', 'required' => true, 'message' => 'Debe ingresar la descripcion del tipo de documento')
	);
}
?>
