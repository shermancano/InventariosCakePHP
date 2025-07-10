<?php
class ActivoFijoMantencion extends AppModel {
	var $name = 'ActivoFijoMantencion';
	var $primaryKey = 'afma_id';
	var $useTable = 'activos_fijos_mantenciones';
	
	var $belongsTo = array(
		'Proveedor' => array(
			'className' => 'Proveedor',
			'foreignKey' => 'prov_id',
			'conditions' => '',
			'fields' => '',
			'order' => ''
		),
		'UbicacionActivoFijo' => array(
			'className' => 'UbicacionActivoFijo',
			'foreignKey' => 'ubaf_codigo',
			'conditions' => '',
			'fields' => '',
			'order' => ''
		),
		'CentroCosto' => array(
			'className' => 'CentroCosto',
			'foreignKey' => 'ceco_id',
			'conditions' => '',
			'fields' => '',
			'order' => ''
		)
	);
	
	var $validate = array(
		'afma_numero_factura' => array(
			'notempty' => array(
				'rule' => array('notEmpty'),
				'message' => 'Debe ingresar el número de factura',
				'allowEmpty' => false,
				'required' => true
			)
		),
		'afma_fecha_factura' => array(
			'notempty' => array(
				'rule' => array('notEmpty'),
				'message' => 'Debe ingresar la fecha de factura',
				'allowEmpty' => false,
				'required' => true
			)
		),
		'prov_id' => array(
			'notempty' => array(
				'rule' => array('notEmpty'),
				'message' => 'Debe ingresar el proveedor',
				'allowEmpty' => false,
				'required' => true
			)
		)
	);
	
	function obtieneCorrelativo($ceco_id) {
		$sql = "select coalesce(max(afma_correlativo), 0) + 1 as baaf_correlativo
				from activos_fijos_mantenciones
				where ceco_id = ".$ceco_id."";
		$res = $this->query($sql); 		
		return $res[0][0]['baaf_correlativo'];
	}
	
	function findMantenciones($ubaf_codigo) {
		$sql = "select dema_fecha_servicio
					  ,dema_kilometraje
					  ,dema_descripcion
					  ,dema_nombre_operador
					  ,dema_valor
					  ,dema_observacion
					  ,afma_fecha
					  ,afma_numero_factura
					  ,afma_fecha_factura
					  ,ceco.ceco_nombre
					  ,prov.prov_nombre
				from detalle_activos_fijos_mantenciones
				inner join activos_fijos_mantenciones as afma using (afma_id)
				inner join centros_costos as ceco using (ceco_id)
				left join proveedores as prov using (prov_id)
				where afma.ubaf_codigo = '".$ubaf_codigo."'
				order by afma_fecha asc
				";
		$res = $this->query($sql);
		return $res;
	}
}
?>