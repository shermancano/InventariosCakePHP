<?php
class Producto extends AppModel {
	var $name = 'Producto';
	var $useTable = 'productos';
	var $primaryKey = 'prod_id';
	
	var $belongsTo = array(
	 	 'Grupo' => array('className' => 'Grupo',
	 	 	 			  'foreignKey' => 'grup_id')
	 	,'TipoBien' => array('className' => 'TipoBien',
	 	 	 			     'foreignKey' => 'tibi_id')
	 	,'Unidad' => array('className' => 'Unidad',
	 	 	 			   'foreignKey' => 'unid_id')
	);
	
	var $validate = array(
	    'tifa_id' => array('rule' => 'numeric', 'required' => true, 'message' => 'Debe seleccionar el tipo de familia')
	   ,'fami_id' => array('rule' => 'numeric', 'required' => true, 'message' => 'Debe seleccionar la familia')
	   ,'grup_id' => array('rule' => 'numeric', 'required' => true, 'message' => 'Debe seleccionar el grupo')
	   ,'tibi_id' => array('rule' => 'numeric', 'required' => true, 'message' => 'Debe seleccionar el tipo de bien')
	   ,'unid_id' => array('rule' => 'numeric', 'required' => true, 'message' => 'Debe seleccionar el tipo de unidad')
	   ,'prod_nombre' => array(
	   		'notempty' => array(
				'rule' => 'notEmpty',
				'required' => true,
				'message' => 'Debe ingresar el nombre del producto'
			),
			'unique' => array(
				'rule' => 'isUnique',
				'required' => true,
				'message' => 'El nombre del producto ya se encuentra en uso'
			)
		)
	);
	
	function searchProducto($string, $tibi_id = null) {
		
		$string = strtolower($string);
		$string = trim($string);
				
		if ($tibi_id == null) {
			$tibi_filter = "tibi_id in (1, 2, 3)";
		} else {
			$tibi_filter = "tibi_id = ".$tibi_id;
		}
		
		$sql = "select prod_id
					  ,prod_nombre
				from productos
				where ".$tibi_filter."
				and (lower(prod_nombre) like '%".strtolower($string)."%'
				or lower(prod_codigo) like '%".strtolower($string)."%'
				or lower(prod_nombre_fantasia) like '%".strtolower($string)."%')";
				
		$res = $this->query($sql);
		return $res;
	}
}
?>
