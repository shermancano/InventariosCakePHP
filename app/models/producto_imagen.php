<?php
class ProductoImagen extends AppModel {
	var $name = 'ProductoImagen';
	var $useTable = 'productos_imagenes';
	var $primaryKey = 'prim_id';
	
	var $belongsTo = array(
		'Producto' => array(
			'className' => 'Producto',
			'foreignKey' => 'prod_id'
		)
	);
}
?>