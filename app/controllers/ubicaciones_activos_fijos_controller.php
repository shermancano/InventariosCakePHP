<?php
class UbicacionesActivosFijosController extends AppController {
	var $name = 'UbicacionesActivosFijos';
	var $uses = array('UbicacionActivoFijo', 'TrazabilidadActivoFijo');
	
	function searchByCc($ceco_id) {
		$this->layout = 'ajax';
		$string = stripslashes($_GET['term']);
		
		$conditions = array('UbicacionActivoFijo.ceco_id' => $ceco_id, 'or' => array('UbicacionActivoFijo.ubaf_codigo like' => '%'.$string.'%', 'lower(Producto.prod_nombre) like' => '%'.strtolower($string).'%'));
		$info = $this->UbicacionActivoFijo->find('all', array('conditions' => $conditions));
		$activos_fijos = array();
		
		if (sizeof($info) > 0) {
			foreach ($info as $row) {
				$prod_nombre = $row['Producto']['prod_nombre'];
				$ubaf_codigo = $row['UbicacionActivoFijo']['ubaf_codigo'];
				$activos_fijos[] = array('value' => $prod_nombre, 'label' => $prod_nombre." (".$ubaf_codigo.")", 'ubaf_codigo' => $ubaf_codigo);
			}
		}
		$this->set('json_info', json_encode($activos_fijos));
	}
	
	function searchByCodigo($ubaf_codigo) {
		$this->layout = 'ajax';
		$info = $this->UbicacionActivoFijo->find('first', array('conditions' => array('UbicacionActivoFijo.ubaf_codigo' => $ubaf_codigo)));
		$this->set('json_info', json_encode($info));
	}
	
	function searchChildrenCc($ceco_id) {
		$this->layout = 'ajax';
		$string = stripslashes($_GET['term']);
		$cc_hijos = $this->ccArrayToCcVector($this->UbicacionActivoFijo->CentroCosto->findAllChildren($ceco_id));
		
		$conditions = array(			
			'UbicacionActivoFijo.ceco_id in ('.implode(', ', $cc_hijos).')', 
			'or' => array(
				'UbicacionActivoFijo.ubaf_codigo like' => '%'.$string.'%', 
				'lower(Producto.prod_nombre) like' => '%'.strtolower($string).'%'
			)
		);
		
		$info = $this->UbicacionActivoFijo->find('all', array('conditions' => $conditions));
		$activos_fijos = array();
		
		if (sizeof($info) > 0) {
			foreach ($info as $row) {
				$prod_nombre = $row['Producto']['prod_nombre'];
				$ubaf_codigo = $row['UbicacionActivoFijo']['ubaf_codigo'];
				$activos_fijos[] = array('value' => $ubaf_codigo.' - '.$prod_nombre, 'label' => $prod_nombre." (".$ubaf_codigo.")", 'ubaf_codigo' => $ubaf_codigo);
			}
		}

		$this->set('json_info', json_encode($activos_fijos));
	}
	
	function searchTrazabilidad($ceco_id) {
		$this->layout = 'ajax';
		$string = stripslashes($_GET['term']);
		$cc_hijos = $this->ccArrayToCcVector($this->UbicacionActivoFijo->CentroCosto->findAllChildren($ceco_id));
		
		$conditions = array(			
			'TrazabilidadActivoFijo.ceco_id in ('.implode(', ', $cc_hijos).')', 
			'or' => array(
				'TrazabilidadActivoFijo.traf_codigo like' => '%'.$string.'%', 
				'lower(Producto.prod_nombre) like' => '%'.strtolower($string).'%'
			)
		);
		
		$info = $this->TrazabilidadActivoFijo->find('all', array('conditions' => $conditions));
		
		if (sizeof($info) > 0) {
			foreach ($info as $row) {
				$prod_nombre = $row['Producto']['prod_nombre'];
				$ubaf_codigo = $row['TrazabilidadActivoFijo']['traf_codigo'];
				$activos_fijos[] = array('value' => $ubaf_codigo.' - '.$prod_nombre, 'label' => $prod_nombre." (".$ubaf_codigo.")", 'traf_codigo' => $ubaf_codigo);
			}
		}

		$this->set('json_info', json_encode($activos_fijos));
	}
}

?>