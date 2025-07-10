<?php
class OrdenCompra extends AppModel {
	var $name = 'OrdenCompra';
	var $primaryKey = 'orco_id';
	var $useTable = 'ordenes_compras';
	
	var $belongsTo = array(
	 	 'Proveedor' => array('className' => 'Proveedor',
							  'foreignKey' => 'prov_id')
	 	,'MetodoDespacho' => array('className' => 'MetodoDespacho',
	 	 	 			   	       'foreignKey' => 'mede_id')
		,'FormaPago' => array('className' => 'FormaPago',
	 	 	 			   	  'foreignKey' => 'mede_id')
		,'Financiamiento' => array('className' => 'Financiamiento',
								   'foreignKey' => 'fina_id')
	);
	
	var $hasMany = array(
	 	 'DetalleOrdenCompra' => array('className' => 'DetalleOrdenCompra',
									   'foreignKey' => 'orco_id')
	);
	
	var $validate = array(
		'prov_id' => array(
			'notempty' => array(
				'rule' => array('notempty'),
				'message' => 'Debe seleccionar el proveedor',
				'alowempty' => false,
				'required' => true
			)
		),
		'ceco_id' => array(
			'notempty' => array(
				'rule' => array('notempty'),
				'message' => 'Debe seleccionar el centro de costo',
				'alowempty' => false,
				'required' => true
			)
		),
		'orco_numero' => array(
			'notempty' => array(
				'rule' => array('notempty'),
				'message' => 'Debe ingresar el número de la orden de compra',
				'alowempty' => false,
				'required' => true
			),
			'unique' => array(
				'rule' => array('isUnique'),
				'message' => 'Este número ya se encuentra registrado',
				'alowempty' => false,
				'required' => true
			) 
		),
		'orco_nombre' => array(
			'notempty' => array(
				'rule' => array('notempty'),
				'message' => 'Debe ingresar el nombre de la orden de compra',
				'alowempty' => false,
				'required' => true
			)
		),
		'orco_fecha_entrega' => array(
			'notempty' => array(
				'rule' => array('notempty'),
				'message' => 'Debe seleccionar la fecha entrega',
				'alowempty' => false,
				'required' => true
			)
		),
		'orco_direccion_factura' => array(
			'notempty' => array(
				'rule' => array('notempty'),
				'message' => 'Debe ingresar la dirección de envío de la factura',
				'alowempty' => false,
				'required' => true
			)
		),
		'mede_id' => array(
			'notempty' => array(
				'rule' => array('notempty'),
				'message' => 'Debe seleccionar el método de despacho',
				'alowempty' => false,
				'required' => true
			)
		),
		'fopa_id' => array(
			'notempty' => array(
				'rule' => array('notempty'),
				'message' => 'Debe seleccionar la forma de pago',
				'alowempty' => false,
				'required' => true
			)
		),
		'fina_id' => array(
			'notempty' => array(
				'rule' => array('notempty'),
				'message' => 'Debe seleccionar la fuente de financiamiento',
				'alowempty' => false,
				'required' => true
			)
		),
		'orco_responsable' => array(
			'notempty' => array(
				'rule' => array('notempty'),
				'message' => 'Debe ingresar al responsable de la orden de compra',
				'alowempty' => false,
				'required' => true
			)
		)
	);
}
?>