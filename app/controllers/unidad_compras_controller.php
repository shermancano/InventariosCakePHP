<?php
class UnidadComprasController extends AppController {

	var $name = 'UnidadCompras';

	function index() {
		$this->UnidadCompra->recursive = 0;
		$this->set('unidadCompras', $this->paginate());
	}
	
	function add() {
		if (!empty($this->data)) {
			$this->UnidadCompra->create();
			$this->UnidadCompra->set($this->data);
			
			if ($this->UnidadCompra->validates()) {
				if ($this->UnidadCompra->save($this->data)) {
					$this->Log->write($this->Session->read('userdata.Usuario.usua_id'), 'Nueva Unidad de Compra', $_REQUEST);
					$this->Session->setFlash(__('La unidad de compra ha sido guardada', true));
					$this->redirect(array('action' => 'index'));
				} else {
					$this->Session->setFlash(__(utf8_encode('No se pudo guardar la unidad de compra. Por favor inténtelo nuevamente'), true));
				}
			}
		}
	}

	function edit($id = null) {
		if (!$id && empty($this->data)) {
			$this->Session->setFlash(__('ID invalido', true));
			$this->redirect(array('action' => 'index'));
		}
		if (!empty($this->data)) {
			if ($this->UnidadCompra->save($this->data)) {
				$this->Log->write($this->Session->read('userdata.Usuario.usua_id'), utf8_encode('Modificación Unidad de Compra'), $_REQUEST);
				$this->Session->setFlash(__('La unidad de compra ha sido guardada', true));
				$this->redirect(array('action' => 'index'));
			} else {
				$this->Session->setFlash(__(utf8_encode('No se pudo guardar la unidad de compra. Por favor inténtelo nuevamente'), true));
			}
		}
		if (empty($this->data)) {
			$this->data = $this->UnidadCompra->read(null, $id);
		}
	}

	function delete($id = null) {
		if (!$id) {
			$this->Session->setFlash(__('ID invalido', true));
			$this->redirect(array('action'=>'index'));
		}
		if ($this->UnidadCompra->delete($id)) {
			$this->Log->write($this->Session->read('userdata.Usuario.usua_id'), utf8_encode('Eliminación Unidad de Compra'), $_REQUEST);
			$this->Session->setFlash(__('Unidad de compra eliminada', true));
			$this->redirect(array('action'=>'index'));
		}
		$this->Session->setFlash(__('No se pudo eliminar la unidad de compra', true));
		$this->redirect(array('action' => 'index'));
	}
}
?>