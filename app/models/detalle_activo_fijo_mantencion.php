<?php
class DetalleActivoFijoMantencion extends AppModel {
	var $name = 'DetalleActivoFijoMantencion';
	var $primaryKey = 'dema_id';
	var $useTable = 'detalle_activos_fijos_mantenciones';
	
	var $belongsTo = array(
		'ActivoFijoMantencion' => array(
			'className' => 'ActivoFijoMantencion',
			'foreignKey' => 'afma_id',
			'conditions' => '',
			'fields' => '',
			'order' => ''
		)
	);
}
?>