<?php
class DetalleOrdenCompra extends AppModel {
	var $name = 'DetalleOrdenCompra';
	var $useTable = 'detalle_ordenes_compras';
	var $primaryKey = 'deor_id';
	
	var $belongsTo = array(
	 	 'Producto' => array('className' => 'Producto',
							 'foreignKey' => 'prod_id')
	 	,'Unidad' => array('className' => 'Unidad',
	 	 	 			   'foreignKey' => 'unid_id')
	);
	
	function save($orco_id, $data) {
	
		foreach ($data['DetalleOrdenCompra'] as $row) {
			$this->create();
			if (isset($row['prod_id'])) {
				$data_ = array('DetalleOrdenCompra' => array('prod_id' 				   => $row['prod_id'],
						 									 'unid_id' 				   => $row['unid_id'],
															 'deor_especifi_comprador' => $row['deor_especifi_comprador'],
															 'deor_especifi_proveedor' => $row['deor_especifi_proveedor'],
															 'deor_precio'			   => $row['deor_precio'],
															 'deor_descuento'		   => $row['deor_descuento'],
															 'deor_cargos'			   => $row['deor_cargos'],
															 'orco_id'				   => $orco_id,
															 'deor_cantidad'		   => $row['deor_cantidad']));
			} else {
				$data_ = array('DetalleOrdenCompra' => array('deor_nombre'			   => $row['deor_nombre'],
						 									 'unid_id' 				   => $row['unid_id'],
															 'deor_especifi_comprador' => $row['deor_especifi_comprador'],
															 'deor_especifi_proveedor' => $row['deor_especifi_proveedor'],
															 'deor_precio'			   => $row['deor_precio'],
															 'deor_descuento'		   => $row['deor_descuento'],
															 'deor_cargos'			   => $row['deor_cargos'],
															 'orco_id'				   => $orco_id,
															 'deor_cantidad'		   => $row['deor_cantidad']));
			}
			
			if (!parent::save($data_)) {
				return false;
			}
		}
		
		return true;
	}
}
?>