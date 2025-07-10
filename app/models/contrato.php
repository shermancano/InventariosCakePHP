<?php
class Contrato extends AppModel {
	var $name = 'Contrato';
	var $primaryKey = 'cont_id';
	
	var $hasMany = array(
		'Documento' => array('className' => 'Documento',
						     'foreignKey' => 'cont_id')
	);
	
	var $hasOne = array(
		'Evaluacion' => array(
			'className' => 'Evaluacion'
		   ,'foreignKey' => 'cont_id'
		)
	); 
	
	var $belongsTo = array(
	 	 'ModalidadCompra' => array('className' => 'ModalidadCompra',
	 	 	 						'foreignKey' => 'moco_id')
	 	,'TipoContrato' => array('className' => 'TipoContrato',
	 	 	 					 'foreignKey' => 'tico_id')
	 	,'Proveedor' => array('className' => 'Proveedor',
	 	 	 				  'foreignKey' => 'prov_id')
	 	,'Rubro' => array('className' => 'Rubro',
	 	 	 		      'foreignKey' => 'prov_id')
	 	,'TipoRenovacion' => array('className' => 'TipoRenovacion',
	 	 	 		      		   'foreignKey' => 'tire_id')
	 	,'TipoMonto' => array('className' => 'TipoMonto',
	 	 	 		      	  'foreignKey' => 'timo_id')
	 	,'UnidadCompra' => array('className' => 'UnidadCompra',
	 	 	 		      	     'foreignKey' => 'unco_id')
	 	,'Banco' => array('className' => 'Banco',
	 					  'foreignKey' => 'banc_id')
	 	,'TipoMontoGarantia' => array('className' => 'TipoMonto',
	 	 	 		      	          'foreignKey' => 'timo_id_garantia')
	 );
	 
	 var $validate = array(
	 	'cont_nombre' => array('rule' => '/[\w\s]+/', 'required' => true, 'message' => 'Debe ingresar el nombre del contrato')
	   ,'cont_nro_licitacion' => array('rule' => '/[\w\s\-\_]+/', 'required' => true, 'message' => 'Debe ingresar el numero de licitacion')
	   ,'cont_monto_compra' => array('rule' => 'numeric', 'required' => false, 'message' => 'El monto de compra debe ser numerico')
	   ,'cont_monto_mensual' => array('rule' => 'numeric', 'required' => false, 'message' => 'El monto mensual debe ser numerico')
	   ,'cont_admin_tecnico' => array('rule' => '/[\w\s\_\-]+/', 'required' => true, 'message' => 'Debe ingresar el nombre del administrador tecnico')
	 );
	 
	 function searchContrato($strings) {
	 	 $arr_strings = array();
	 	 
	 	 foreach ($strings as $string) {
	 	 	 $string = strtolower($string);
			 $string = trim($string);
	 	 	 $arr_strings[] = "lower(cont_nombre) like '%".$string."%' or lower(prov_nombre) like '%".$string."%' or lower(prov_rut) like '%".$string."%'";
	 	 }
	 	 
	 	 $sql = "select Contrato.cont_id
	 	 		       ,Contrato.cont_nombre
	 	 		 from contratos as Contrato
	 	 		     ,proveedores as Proveedor
	 	 		 where Contrato.prov_id = Proveedor.prov_id
	 	 		 and   (".implode("or ", $arr_strings).")";
	 	 $rs = $this->query($sql);
	 	 return $rs;
	 }
}
?>