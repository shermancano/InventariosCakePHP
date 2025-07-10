<?php
class TipoContratosController extends AppController {
	var $name = 'TipoContratos';

	function index() {
		$this->TipoContrato->recursive = 0;
		$this->set('tipoContratos', $this->paginate());
	}

	function add() {
		if (!empty($this->data)) {
			$this->TipoContrato->create();
			$this->TipoContrato->set($this->data);
			
			if ($this->TipoContrato->validates()) {
				if ($this->TipoContrato->save($this->data)) {
					$this->Log->write($this->Session->read('userdata.Usuario.usua_id'), 'Nuevo Tipo de Contrato', $_REQUEST);
					$this->Session->setFlash(__('El tipo de contrato ha sido guardado', true));
					$this->redirect(array('action' => 'index'));
				} else {
					$this->Session->setFlash(__(utf8_encode('No se pudo salvar el tipo de contrato. Por favor, inténtelo nuevamente'), true));
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
			$this->TipoContrato->set($this->data);
			
			if ($this->TipoContrato->validates()) {
				if ($this->TipoContrato->save($this->data)) {
					$this->Log->write($this->Session->read('userdata.Usuario.usua_id'), utf8_encode('Modificación Tipo de Contrato'), $_REQUEST);
					$this->Session->setFlash(__('El tipo de contrato ha sido guardado', true));
					$this->redirect(array('action' => 'index'));
				} else {
					$this->Session->setFlash(__(utf8_encode('No se pudo salvar el tipo de contrato. Por favor, inténtelo nuevamente'), true));
				}
			}
		}
		if (empty($this->data)) {
			$this->data = $this->TipoContrato->read(null, $id);
		}
	}

	function delete($id = null) {
		if (!$id) {
			$this->Session->setFlash(__('ID invalido', true));
			$this->redirect(array('action'=>'index'));
		}
		if ($this->TipoContrato->delete($id)) {
			$this->Log->write($this->Session->read('userdata.Usuario.usua_id'), utf8_encode('Eliminación Tipo de Contrato'), $_REQUEST);
			$this->Session->setFlash(__('Tipo de contrato eliminado', true));
			$this->redirect(array('action'=>'index'));
		}
		$this->Session->setFlash(__('No se pudo eliminar el tipo de contrato', true));
		$this->redirect(array('action' => 'index'));
	}
}
?>