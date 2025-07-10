<?php
class ModalidadComprasController extends AppController {
	var $name = 'ModalidadCompras';

	function index() {
		$this->ModalidadCompra->recursive = 0;
		$this->set('modalidadCompras', $this->paginate());
	}

	function add() {
		if (!empty($this->data)) {
			$this->ModalidadCompra->create();
			$this->ModalidadCompra->set($this->data);
			
			if ($this->ModalidadCompra->validates()) {
				if ($this->ModalidadCompra->save($this->data)) {
					$this->Log->write($this->Session->read('userdata.Usuario.usua_id'), 'Nueva Modalidad de Compra', $_REQUEST);
					$this->Session->setFlash(__('La modalidad de compra ha sido guardada', true));
					$this->redirect(array('action' => 'index'));
				} else {
					$this->Session->setFlash(__(utf8_encode('No se pudo guardar la modalidad de compra. Por favor, inténtelo nuevamente'), true));
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
			$this->ModalidadCompra->set($this->data);
			if ($this->ModalidadCompra->validates()) {
				if ($this->ModalidadCompra->save($this->data)) {
					$this->Log->write($this->Session->read('userdata.Usuario.usua_id'), utf8_encode('Modificación Modalidad de Compra'), $_REQUEST);
					$this->Session->setFlash(__('La modalidad de compra ha sido guardada', true));
					$this->redirect(array('action' => 'index'));
				} else {
					$this->Session->setFlash(__(utf8_encode('No se pudo guardar la modalidad de compra. Por favor, inténtelo nuevamente'), true));
				}
			}
		}
		if (empty($this->data)) {
			$this->data = $this->ModalidadCompra->read(null, $id);
		}
	}

	function delete($id = null) {
		if (!$id) {
			$this->Session->setFlash(__('ID invalido', true));
			$this->redirect(array('action'=>'index'));
		}
		if ($this->ModalidadCompra->delete($id)) {
			$this->Log->write($this->Session->read('userdata.Usuario.usua_id'), utf8_encode('Eliminación Modalidad de Compra'), $_REQUEST);
			$this->Session->setFlash(__('Modalidad de compra eliminada', true));
			$this->redirect(array('action'=>'index'));
		}
		$this->Session->setFlash(__('No se pudo eliminar la modalidad de compra', true));
		$this->redirect(array('action' => 'index'));
	}
}
?>