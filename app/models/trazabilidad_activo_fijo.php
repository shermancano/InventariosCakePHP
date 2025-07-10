<?php

class TrazabilidadActivoFijo extends AppModel {
	var $name = 'TrazabilidadActivoFijo';
	var $primaryKey = 'traf_id';
	var $useTable = 'trazabilidades_activos_fijos';
	
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
	
	var $validate = array(
		'traf_codigo' => array(
			'notempty' => array(
				'rule' => array('notempty'),
				'message' => 'Debe ingresar el código de barra del bien.',				
				'required' => true
			),
			'numeric' => array(
				'rule' => array('numeric'),
				'message' => 'El código de barra debe ser numerico.',				
				'required' => true
			)
		)
	);
	
	function obtieneTrazabilidadActivoFijo($codigos_barra) {
		unset($codigos_barra['Reporte']['ceco_id']);
				
		$codigo_barra = array();
		foreach ($codigos_barra['Reporte'] as $row) {
			$codigo_barra[] = $row['traf_codigo'];
		}
	
		$sql = "select traf_codigo,
					   prod.prod_nombre,
					   hijo.ceco_nombre as centro_costo,
					   padre.ceco_nombre as dependencia,
					   abuelo.ceco_nombre as establecimiento,
					   prop.prop_nombre,
					   situ.situ_nombre,
					   marc.marc_nombre,
					   colo.colo_nombre,
					   mode.mode_nombre,
					   esre.esre_nombre,
					   tmov.tmov_descripcion,
					   grup.grup_nombre,
					   fami.fami_nombre,
					   traf_fecha,
					   traf_fecha_garantia,
					   traf_precio,
					   CASE
						  WHEN traf_depreciable = '1' THEN 
						  	'Si'
						  ELSE 
						  	'No'
					   END as traf_depreciable,
					   traf_vida_util,
					   traf_serie
				from trazabilidades_activos_fijos as traf
				inner join productos as prod using (prod_id)
				left join centros_costos as hijo on (traf.ceco_id = hijo.ceco_id)
				left join centros_costos as padre on (hijo.ceco_id_padre = padre.ceco_id)
				left join centros_costos as abuelo on (padre.ceco_id_padre = abuelo.ceco_id)
				inner join propiedades as prop using (prop_id)
				inner join situaciones as situ using (situ_id)
				left join marcas as marc using (marc_id)
				left join colores as colo using (colo_id)
				left join modelos as mode using (mode_id)
				inner join estados_registro as esre using (esre_id)
				inner join tipo_movimientos as tmov using (tmov_id)
				inner join grupos as grup on (grup.grup_id = prod.grup_id)
				inner join familias as fami on (fami.fami_id = grup.fami_id)
				where traf_codigo in ('".implode("','", $codigo_barra)."')
				order by traf_codigo, traf_fecha asc";	
		$res = $this->query($sql);
		
		return $res;
	}
	
	function saveUbicacionToTrazabilidad($ubicaciones) {
		$numero = 1;			
		foreach ($ubicaciones as $row) {
			if (empty($row['UbicacionActivoFijo']['ubaf_fecha_garantia'])) {
				$garantia = 'NULL';
			} else {
				$garantia = $row['UbicacionActivoFijo']['ubaf_fecha_garantia'];
			}
			
			if ($row['UbicacionActivoFijo']['ubaf_depreciable'] == 1) {
				$depreciable = 'true';
			} else {
				$depreciable = 'false';
			}
			
			if (empty($row['UbicacionActivoFijo']['marc_id'])) {
				$marc_id = 'NULL';
			} else {
				$marc_id = $row['UbicacionActivoFijo']['marc_id'];
			}
			
			if (empty($row['UbicacionActivoFijo']['colo_id'])) {
				$colo_id = 'NULL';
			} else {
				$colo_id = $row['UbicacionActivoFijo']['colo_id'];
			}
			
			if (empty($row['UbicacionActivoFijo']['mode_id'])) {
				$mode_id = 'NULL';
			} else {
				$mode_id = $row['UbicacionActivoFijo']['mode_id'];
			}			
			
			$sql = "insert into 
			        trazabilidades_activos_fijos 
					values (".$numero.",
					        '".$row['UbicacionActivoFijo']['ubaf_codigo']."',
						    ".$row['UbicacionActivoFijo']['ceco_id'].",
						    ".$row['UbicacionActivoFijo']['prod_id'].",
						    ".$row['UbicacionActivoFijo']['prop_id'].",
						    ".$row['UbicacionActivoFijo']['situ_id'].",
						    ".$marc_id.",
						    ".$colo_id.",
						    ".$mode_id.",
						    1,
						    1,						   
						    ".$garantia.",
						    ".$row['UbicacionActivoFijo']['ubaf_precio'].",
						    '".$depreciable."',
						    ".$row['UbicacionActivoFijo']['ubaf_vida_util'].",
						    '".$row['UbicacionActivoFijo']['ubaf_fecha_adquisicion']."',
						    '".$row['UbicacionActivoFijo']['ubaf_serie']."')";
			$res = $this->query($sql);
			$numero++;
		}

		return true;
	}
}
?>