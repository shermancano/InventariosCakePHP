<?php
class Proveedor extends AppModel {
	var $name = 'Proveedor';
	var $useTable = 'proveedores';
	var $primaryKey = 'prov_id';
	var $displayField = 'prov_nombre';
	
	 var $validate = array(
	    'prov_nombre' => array(
			'notempty' => array(
				'rule' => '/[\w\s]+/', 
				'required' => true, 
				'message' => 'Debe ingresar el nombre de la razon social'
			),
			'unique' => array(
				'rule' => 'isUnique',
				'message' => 'El nombre del proveedor ya se encuentra en uso.',
				'required' => true
			)
		),
	   	'prov_rut' => array(
			'notempty' => array(
				'rule' => '/[\d]+\-[\w]/', 
				'required' => true, 
				'message' => 'Debe ingresar un RUT valido'
			),
			'unique' => array(
				'rule' => 'isUnique',
				'message' => 'El rut del proveedor ya se encuentra en uso.',
				'required' => true
			)
		)
	   //,'prov_direccion' => array('rule' => '/[\w\s\,\#\d]+/', 'required' => true, 'message' => 'Debe ingresar la direccion')
	   //,'prov_telefono' => array('rule' => 'numeric', 'required' => true, 'message' => 'Debe ingresar el telefono')
	   //,'prov_email' => array('rule' => 'email', 'required' => true, 'message' => 'Debe ingresar el correo electronico')
	   //,'prov_web' => array('rule' => 'url', 'required' => true, 'message' => 'Debe ingresar el sitio web del proveedor')
	   //,'prov_contacto' => array('rule' => '/[\w\s]+/', 'required' => true, 'message' => 'Debe ingresar el nombre del contacto')
	 );
	 
	 function searchProveedor($string) {
		
		$string = strtolower($string);
		$string = trim($string);
			
		$sql = "select prov_id
					  ,prov_nombre
				from proveedores			
				where (lower(prov_nombre) like '%".strtolower($string)."%'
				or lower(prov_nombre) like '%".$string."%')";
				
		$res = $this->query($sql);
		return $res;
	}
}
?>