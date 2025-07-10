<?php
class TipoRenovacionesController extends AppController {
	var $name = 'TipoRenovaciones';
	var $uses = array('TipoRenovacion');

	function index() {
		$this->TipoRenovacion->recursive = 0;
		$this->set('tipoRenovacions', $this->paginate());
	}

	function add() {
		if (!empty($this->data)) {
			$this->TipoRenovacion->create();
			$this->TipoRenovacion->set($this->data);
			
			if ($this->TipoRenovacion->validates()) {
				if ($this->TipoRenovacion->save($this->data)) {
					$this->Log->write($this->Session->read('userdata.Usuario.usua_id'), utf8_encode('Nuevo Tipo de Renovación'), $_REQUEST);
					$this->Session->setFlash(__(utf8_encode('El tipo de renovación ha sido guardado'), true));
					$this->redirect(array('action' => 'index'));
				} else {
					$this->Session->setFlash(__(utf8_encode('No se pudo guardar el tipo de renovación. Por favor, inténtelo nuevamente'), true));
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
			$this->TipoRenovacion->set($this->data);
			
			if ($this->TipoRenovacion->validates()) {
				if ($this->TipoRenovacion->save($this->data)) {
					$this->Log->write($this->Session->read('userdata.Usuario.usua_id'), utf8_encode('Modificación Tipo de Renovación'), $_REQUEST);
					$this->Session->setFlash(__(utf8_encode('El tipo de renovación ha sido guardado'), true));
					$this->redirect(array('action' => 'index'));
				} else {
					$this->Session->setFlash(__(utf8_encode('No se pudo guardar el tipo de renovación. Por favor, inténtelo nuevamente'), true));
				}
			}
		}
		if (empty($this->data)) {
			$this->data = $this->TipoRenovacion->read(null, $id);
		}
	}

	function delete($id = null) {
		if (!$id) {
			$this->Session->setFlash(__('ID invalido', true));
			$this->redirect(array('action'=>'index'));
		}
		if ($this->TipoRenovacion->delete($id)) {
			$this->Log->write($this->Session->read('userdata.Usuario.usua_id'), utf8_encode('Eliminación Tipo de Renovación'), $_REQUEST);	
			$this->Session->setFlash(__('Tipo de renovacion eliminado', true));
			$this->redirect(array('action'=>'index'));
		}
		$this->Session->setFlash(__('No se pudo eliminar el tipo de renovacion', true));
		$this->redirect(array('action' => 'index'));
	}
}
?>