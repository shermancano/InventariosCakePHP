<?php
class Responsable extends AppModel {
	var $name = 'Responsable';
	var $useTable = 'responsables';
	var $primaryKey = 'resp_id';
	
	var $belongsTo = array(
	 	 'CentroCosto' => array('className' => 'CentroCosto',
	 	 	 			   		'foreignKey' => 'ceco_id')
	 	,'Usuario' => array('className' => 'Usuario',
	 	 	 			   	'foreignKey' => 'usua_id')
	 	,'EstadoRegistro' => array('className' => 'EstadoRegistro',
	 	 	 			   	       'foreignKey' => 'esre_id')
	);
	
	var $validate = array(
		'ceco_id' => array('rule' => '/[\w\s]+/', 'required' => true, 'message' => 'Debe seleccionar el Centro de Costo')
	   ,'resp_nombre' => array('rule' => '/[\w\s]+/', 'required' => true, 'message' => 'Debe ingresar el nombre del responsable')
	   ,'esre_id' => array('rule' => '/[\w\s]+/', 'required' => true, 'message' => 'Debe seleccionar el estado')
	);
}
?>
