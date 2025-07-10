<?php
class DetalleBaja extends AppModel {
	var $name = 'DetalleBaja';
	var $primaryKey = 'deba_id';
	var $useTable = 'detalle_bajas';
	
	var $belongsTo = array(
		'BajaActivoFijo' => array(
			'className' => 'BajaActivoFijo',
			'foreignKey' => 'baaf_id'
		),
		'Producto' => array(
			'className' => 'Producto',
			'foreignKey' => 'prod_id'
		),
		'Situacion' => array(
			'className' => 'Situacion',
			'foreignKey' => 'situ_id'
		),
		'Propiedad' => array(
			'className' => 'Propiedad',
			'foreignKey' => 'prop_id'
		),
		'Marca' => array(
			'className' => 'Marca',
			'foreignKey' => 'marc_id'
		),
		'Color' => array(
			'className' => 'Color',
			'foreignKey' => 'colo_id'
		),
		'Modelo' => array(
			'className' => 'Modelo',
			'foreignKey' => 'mode_id'
		)
	);
}
?>