<?php
class DetalleActivoFijo extends AppModel {
	var $name = 'DetalleActivoFijo';
	var $useTable = 'detalle_activos_fijos';
	var $primaryKey = 'deaf_id';
	
	var $belongsTo = array(
		'Producto' => array(
			'className' => 'Producto',
			'foreignKey' => 'prod_id'
		),
		'Marca' => array(
			'className' => 'Marca',
			'foreignKey' => 'marc_id'
		),
		'Propiedad' => array(
			'className' => 'Propiedad',
			'foreignKey' => 'prop_id'
		),
		'Color' => array(
			'className' => 'Color',
			'foreignKey' => 'colo_id'
		),
		'Situacion' => array(
			'className' => 'Situacion',
			'foreignKey' => 'situ_id'
		),
		'ActivoFijo' => array(
			'className' => 'ActivoFijo',
			'foreignKey' => 'acfi_id'
		),
		'Modelo' => array(
			'className' => 'Modelo',
			'foreignKey' => 'mode_id'
		)
	);
	
	function addTraslado($data, $acfi_id) {
		$values = array();
		foreach ($data['DetalleActivoFijo'] as $row) {
			if ($row['ubaf_serie'] != "null") {
				$row['ubaf_serie'] = "'".$row['ubaf_serie']."'";
			}
		
			$values[] = "('".$row['deaf_codigo']."', ".$row['prod_id'].", ".$row['marc_id'].", ".$row['prop_id'].", ".$row['colo_id'].", ".$row['situ_id'].", ".$row['deaf_precio'].", ".$row['deaf_depreciable'].", ".$row['deaf_vida_util'].", ".$acfi_id.", '".$row['deaf_fecha_adquisicion']."', ".$row['mode_id'].", ".$row['ubaf_serie'].")";
		}
		
		$sql = "insert 
				into detalle_activos_fijos
					(deaf_codigo, prod_id, marc_id, prop_id, colo_id, situ_id, deaf_precio, deaf_depreciable, deaf_vida_util, acfi_id, deaf_fecha_adquisicion, mode_id, deaf_serie)
				values ".implode(", ", $values);
		$this->query($sql);
		return true;
	}
}
?>
