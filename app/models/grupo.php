<?php
class Grupo extends AppModel {
	var $name = 'Grupo';
	var $primaryKey = 'grup_id';
	//The Associations below have been created with all possible keys, those that are not needed can be removed

	var $belongsTo = array(
		'Familia' => array(
			'className' => 'Familia',
			'foreignKey' => 'fami_id',
			'conditions' => '',
			'fields' => '',
			'order' => ''
		)
	);
	
	var $validate = array(
		 'tifa_id' => array('rule' => '/[\w\s]+/', 'required' => true, 'message' => 'Debe ingresar el tipo de familia')
		,'fami_id' => array('rule' => '/[\w\s]+/', 'required' => true, 'message' => 'Debe seleccionar la familia')
	    ,'grup_nombre' => array('rule' => '/[\w\s]+/', 'required' => true, 'message' => 'Debe ingresar el nombre del grupo')
	);
	
	function searchGrupo($string) {
		
		$string = strtolower($string);
		$string = trim($string);
			
		$sql = "select grup_id
					  ,grup_nombre
				from grupos			
				where (lower(grup_nombre) like '%".strtolower($string)."%'
				or lower(grup_nombre) like '%".$string."%')";
				
		$res = $this->query($sql);
		return $res;
	}
}
