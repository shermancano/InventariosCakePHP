<?php
class DetallesExistenciasController extends AppController {
	var $name = 'DetallesExistencias';
	var $uses = array('DetalleExistencia');
	
	function delete_ajax($id = null) {
		$this->layout = "ajax";
		
		if ($this->DetalleExistencia->delete($id)) {
			$ret = "ok";
		} else {
			$ret = "err";
		}
		
		$this->set('ret', $ret);
	}
	
	// busca solo los productos que tienen stock
	// para el centro de costo seleccionado
	function searchStockOnly ($ceco_id) {
		$this->layout = 'ajax';
		$term = htmlentities($_GET['term']);
		$info = $this->DetalleExistencia->searchStockOnly($ceco_id, $term);
		$productos = array();
		
		if (sizeof($info) > 0) {
			foreach ($info as $producto) {
				$producto = array_pop($producto);
				$productos[] = array('value' => $producto['prod_nombre'], 'label' => $producto['prod_nombre'], 'prod_id' => $producto['prod_id']);
			}
		}
		$this->set('json_info', json_encode($productos));
	}
	
	function searchAllByProd($ceco_id, $prod_id) {
		$this->layout = 'ajax';
		$this->DetalleExistencia->recursive = 2;
		$info = $this->DetalleExistencia->searchAllByProdCc($ceco_id, $prod_id);
		
		if (sizeof($info) > 0) {
			$info_ = array();
			foreach ($info as $row) {
				$row = array_pop($row);
				$info_[] = $row;
			}
			$info = $info_;
		}		
		$this->set('json_info', json_encode(array('info' => $info)));
	}
}
?>