<?php
class ExclusionActivoFijo extends AppModel {
	var $name = 'ExclusionActivoFijo';
	var $primaryKey = 'exaf_id';
	var $useTable = 'exclusiones_activos_fijos';
	
	var $belongsTo = array(
		'CentroCosto' => array(
			'className' => 'CentroCosto',
			'foreignKey' => 'ceco_id'
		),
		'TipoMovimiento' => array(
			'className' => 'TipoMovimiento',
			'foreignKey' => 'tmov_id'
		),
		'EstadoRegistro' => array(
			'className' => 'EstadoRegistro',
			'foreignKey' => 'esre_id'
		),
		'DependenciaVirtual' => array(
			'className' => 'DependenciaVirtual',
			'foreignKey' => 'devi_id'
		),
		'MotivoBaja' => array(
			'className' => 'MotivoBaja',
			'foreignKey' => 'moba_id'
		)
	);
	
	var $hasMany = array(
	    'DetalleExclusion' => array(
			'className' => 'DetalleExclusion',
			'foreignKey' => 'exaf_id',
			'order'      => ''
		)		
	);
	
	function findBodegasBajas() {
		$sql = "select hijo.ceco_id,
					   hijo.ceco_nombre
				from centros_costos as padre
				left join centros_costos as hijo on (padre.ceco_id = hijo.ceco_id_padre)
				where padre.ceco_id = (select ceco_id
									   from centros_costos
									   order by ceco_id asc
									   limit 1)";
		$res = $this->query($sql);
		return $res;
	}
	
	function obtieneCorrelativo($ceco_id, $tmov_id) {
		$sql = "select coalesce(max(exaf_correlativo), 0) + 1 as exaf_correlativo
				from exclusiones_activos_fijos
				where ceco_id = ".$ceco_id."
				and   tmov_id = ".$tmov_id;
		$res = $this->query($sql); 
		
		return $res[0][0]['exaf_correlativo'];
	}
	
	function deleteCodigoBarra($codigo_barra) {
		$sql = "delete
				from ubicaciones_activos_fijos 
				where ubaf_codigo = '".$codigo_barra."'";
		$res = $this->query($sql);
		
		$sql = "delete
				from detalle_bajas 
				where deba_codigo = '".$codigo_barra."'";				
		$res = $this->query($sql);
		return true;
	}
}
?>