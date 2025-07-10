<?php
class UbicacionActivoFijo extends AppModel {
	var $name = 'UbicacionActivoFijo';
	var $primaryKey = 'ubaf_codigo';
	var $useTable = 'ubicaciones_activos_fijos';
    var $virtualFields = array(
		'ubaf_ubicacion' => 'ubicacion_completa(UbicacionActivoFijo.ceco_id)'
    );
	
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
		'CentroCosto' => array(
			'className' => 'CentroCosto',
			'foreignKey' => 'ceco_id'
		),
		'Modelo' => array(
			'className' => 'Modelo',
			'foreignKey' => 'mode_id'
		)
	);
	
	function planchetaAgrupada($ceco_id) {
		$sql = "select count(prod_id) as total
					  ,prod.prod_nombre
				from ubicaciones_activos_fijos
				inner join productos as prod using (prod_id)
				where ceco_id = ".$ceco_id."
				group by prod_id
						,prod.prod_nombre";
		$res = $this->query($sql);
		return $res;
	}
	
	public function generar_plancheta_af($ceco_id) {
  $sql = "select ceco.ceco_id
                  ,ubaf.ubaf_vida_util
                  ,ubaf.ubaf_fecha_garantia
                  ,ubaf.ubaf_fecha_adquisicion
                  ,ubaf.ubaf_codigo
                  ,ceco.ceco_nombre
                  ,prod.prod_nombre
                  ,fami.fami_nombre
                  ,ubaf.ubaf_precio
                  ,prop.prop_nombre
                  ,situ.situ_nombre
                  ,marc.marc_nombre
                  ,colo.colo_nombre
                  ,mode.mode_nombre
                  ,ubaf.ubaf_serie
 	          ,fina.fina_nombre
  from ubicaciones_activos_fijos as ubaf
  inner join detalle_activos_fijos as detalle on (detalle.deaf_codigo = ubaf.ubaf_codigo)
  inner join activos_fijos as activo_fijo on (activo_fijo.acfi_id = detalle.acfi_id)
  inner join financiamientos as fina on (fina.fina_id = activo_fijo.fina_id)
  left join centros_costos as ceco on (ceco.ceco_id = ubaf.ceco_id)
  left join productos as prod on (prod.prod_id = ubaf.prod_id)
  left join propiedades as prop on (prop.prop_id = ubaf.prop_id)
  left join situaciones as situ on (situ.situ_id = ubaf.situ_id)
  left join marcas as marc on (marc.marc_id = ubaf.marc_id)
  left join colores as colo on (colo.colo_id = ubaf.colo_id)
  left join modelos as mode on (mode.mode_id = ubaf.mode_id)
  left join grupos as grup using (grup_id)
  left join familias as fami using (fami_id)
  where ceco.ceco_id = ".$ceco_id;
  $res = $this->query($sql);
  return $res;
}
}

?>
