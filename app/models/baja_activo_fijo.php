<?php
class BajaActivoFijo extends AppModel {
	var $name = 'BajaActivoFijo';
	var $primaryKey = 'baaf_id';
	var $useTable = 'bajas_activos_fijos';
	
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
	    'DetalleBaja' => array(
			'className' => 'DetalleBaja',
			'foreignKey' => 'baaf_id',
			'order'      => ''
		)		
	);
	
	function obtieneCorrelativo($ceco_id, $tmov_id) {
		$sql = "select coalesce(max(baaf_correlativo), 0) + 1 as baaf_correlativo
				from bajas_activos_fijos
				where ceco_id = ".$ceco_id."
				and   tmov_id = ".$tmov_id;
		$res = $this->query($sql); 
		
		return $res[0][0]['baaf_correlativo'];
	}
	
	function deleteCodigoBarra($codigo_barra) {
		$sql = "delete
				from ubicaciones_activos_fijos 
				where ubaf_codigo = '".$codigo_barra."'";
		$res = $this->query($sql);
		return true;
	}
	
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
	
	function updateValorActivoFijo($codigo_barra, $ceco_id) {
		$sql = "update ubicaciones_activos_fijos
				set ubaf_precio = 1,
				    ceco_id = ".$ceco_id."
				where ubaf_codigo = '".$codigo_barra."'";
		$res = $this->query($sql);
		return true;
	}

}
?>