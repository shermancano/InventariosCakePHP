<?php
class Modelo extends AppModel {
	var $name = 'Modelo';
	var $useTable = 'modelos';
	var $primaryKey = 'mode_id';
	
	var $validate = array(
		'mode_nombre' => array(
			'notempty' => array( 
				'rule' => 'notEmpty',
				'required' => true,
				'message' => 'Debe ingresar el nombre del modelo'
			),
			'unique' => array(
				'rule' => 'isUnique',
				'required' => true,
				'message' => 'El nombre del modelo ya se encuentra en uso.'
			)
		)
	);
	
	function searchModelo($string) {
		
		$string = strtolower($string);
		$string = trim($string);
			
		$sql = "select mode_id
					  ,mode_nombre
				from modelos			
				where (lower(mode_nombre) like '%".strtolower($string)."%'
				or lower(mode_nombre) like '%".$string."%')";
				
		$res = $this->query($sql);
		return $res;
	}
}
?>
