<?php
class TipoMontosController extends AppController {
	var $name = 'TipoMontos';

	function index() {
		$this->TipoMonto->recursive = 0;
		$this->set('tipoMontos', $this->paginate());
	}

	function view($id = null) {
		if (!$id) {
			$this->Session->setFlash(__('ID invalido', true));
			$this->redirect(array('action' => 'index'));
		}
		$this->set('tipoMonto', $this->TipoMonto->read(null, $id));
	}

	function add() {
		if (!empty($this->data)) {
			$this->TipoMonto->create();
			$this->TipoMonto->set($this->data);
			
			if ($this->TipoMonto->validates()) {
				if ($this->TipoMonto->save($this->data)) {
					$this->Log->write($this->Session->read('userdata.Usuario.usua_id'), 'Nuevo Tipo de Monto', $_REQUEST);
					$this->Session->setFlash(__('El tipo de monto ha sido guardado', true));
					$this->redirect(array('action' => 'index'));
				} else {
					$this->Session->setFlash(__(utf8_encode('El tipo de monto no se pudo guardar. Por favor, inténtelo nuevamente'), true));
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
			$this->TipoMonto->set($this->data);
			
			if ($this->TipoMonto->validates()) {
				if ($this->TipoMonto->save($this->data)) {
					$this->Log->write($this->Session->read('userdata.Usuario.usua_id'), utf8_encode('Modificación Tipo de Monto'), $_REQUEST);
					$this->Session->setFlash(__('El tipo de monto ha sido guardado', true));
					$this->redirect(array('action' => 'index'));
				} else {
					$this->Session->setFlash(__(utf8_encode('El tipo de monto no se pudo guardar. Por favor, inténtelo nuevamente'), true));
				}
			}
		}
		if (empty($this->data)) {
			$this->data = $this->TipoMonto->read(null, $id);
		}
	}

	function delete($id = null) {
		if (!$id) {
			$this->Session->setFlash(__('ID invalido', true));
			$this->redirect(array('action'=>'index'));
		}
		if ($this->TipoMonto->delete($id)) {
			$this->Log->write($this->Session->read('userdata.Usuario.usua_id'), utf8_encode('Eliminación Tipo de Monto'), $_REQUEST);
			$this->Session->setFlash(__('Tipo de monto eliminado', true));
			$this->redirect(array('action'=>'index'));
		}
		$this->Session->setFlash(__('No se pudo eliminar el tipo de monto', true));
		$this->redirect(array('action' => 'index'));
	}
}
?>