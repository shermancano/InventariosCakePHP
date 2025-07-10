<?php
class Marca extends AppModel {
	var $name = 'Marca';
	var $useTable = 'marcas';
	var $primaryKey = 'marc_id';
	
	//var $validate = array(
		//'marc_nombre' => array('rule' => '/[\w\s]+/', 'required' => true, 'message' => 'Debe ingresar el nombre de la marca')
	//);
	
	function searchMarca($string) {
		
		$string = strtolower($string);
		$string = trim($string);
			
		$sql = "select marc_id
					  ,marc_nombre
				from marcas			
				where (lower(marc_nombre) like '%".strtolower($string)."%'
				or lower(marc_nombre) like '%".$string."%')";
				
		$res = $this->query($sql);
		return $res;
	}
}
?>
