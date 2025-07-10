<?php
class StocksCentrosCostosController extends AppController {
	var $name = 'StocksCentrosCostos';
	var $uses = array('StockCentroCosto');
	
	function index() {
		$centros_costos = $this->Session->read('userdata.arrayCC');
		$stocks = $this->paginate('StockCentroCosto', array('AND' => array('StockCentroCosto.ceco_id' => $centros_costos)));
		$this->set('stocks', $stocks);
	}
	
	function add() {
		if (!empty($this->data)) {
			$this->StockCentroCosto->set($this->data);
			
			if ($this->StockCentroCosto->validates()) {
				if ($this->StockCentroCosto->save($this->data)) {
					$this->Log->write($this->Session->read('userdata.Usuario.usua_id'), utf8_encode("Nuevo Stock Cr�tico"), $_REQUEST);
					$this->Session->setFlash(__(utf8_encode('El stock cr�tico ha sido guardado'), true));
					$this->redirect(array('action' => 'index'));
				} else {
					$this->Session->setFlash(__(utf8_encode('No se pudo guardar el stock cr�tico, por favor int�ntelo nuevamente'), true));
				}
			}
		}
		
		$centros_costos = $this->Session->read('userdata.selectCC');
		$this->set('centros_costos', $centros_costos);
	}
	
	function edit($stcc_id = null) {
		if (!$stcc_id && empty($this->data)) {
			$this->Session->setFlash(__('ID invalido', true));
			$this->redirect(array('action' => 'index'));
		}
		
		if (!empty($this->data)) {
			$this->StockCentroCosto->set($this->data);
			
			if ($this->StockCentroCosto->validates()) {
				if ($this->StockCentroCosto->save($this->data)) {
					$this->Log->write($this->Session->read('userdata.Usuario.usua_id'), utf8_encode("Modificaci�n Stock Cr�tico"), $_REQUEST);
					$this->Session->setFlash(__(utf8_encode('El stock cr�tico ha sido guardado'), true));
					$this->redirect(array('action' => 'index'));
				} else {
					$this->Session->setFlash(__(utf8_encode('No se pudo guardar el stock cr�tico, por favor int�ntelo nuevamente'), true));
				}
			}
		}
		
		if (empty($this->data)) {
			$this->data = $this->StockCentroCosto->read(null, $stcc_id);
		}
		
		$centros_costos = $this->Session->read('userdata.selectCC');
		$this->set('centros_costos', $centros_costos);
	}
	
	function delete($stcc_id = null) {
		if (!$stcc_id) {
			$this->Session->setFlash(__('ID invalido', true));
			$this->redirect(array('action'=>'index'));
		}
		if ($this->StockCentroCosto->delete($stcc_id)) {
			$this->Log->write($this->Session->read('userdata.Usuario.usua_id'), utf8_encode("Eliminaci�n Stock Cr�tico"), $_REQUEST);
			$this->Session->setFlash(__(utf8_encode('Stock cr�tico eliminado'), true));
			$this->redirect(array('action'=>'index'));
		}
		$this->Session->setFlash(__(utf8_encode('El stock cr�tico no se pudo eliminar'), true));
		$this->redirect(array('action' => 'index'));
	}
}
?>