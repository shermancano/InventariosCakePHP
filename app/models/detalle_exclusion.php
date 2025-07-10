<?php
class DetalleExclusion extends AppModel {
	var $name = 'DetalleExclusion';
	var $primaryKey = 'dete_id';
	var $useTable = 'detalle_exclusiones';
	
	var $belongsTo = array(
		'ExclusionActivoFijo' => array(
			'className' => 'ExclusionActivoFijo',
			'foreignKey' => 'exaf_id'
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