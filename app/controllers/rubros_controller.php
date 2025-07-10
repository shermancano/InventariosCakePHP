<?php
class RubrosController extends AppController {
	var $name = 'Rubros';

	function index() {
		$this->Rubro->recursive = 0;
		$this->set('rubros', $this->paginate());
	}

	function view($id = null) {
		if (!$id) {
			$this->Session->setFlash(__('ID invalido', true));
			$this->redirect(array('action' => 'index'));
		}
		$this->set('rubro', $this->Rubro->read(null, $id));
	}

	function add() {
		if (!empty($this->data)) {
			$this->Rubro->create();
			$this->Rubro->set($this->data);
			
			if ($this->Rubro->validates()) {
				if ($this->Rubro->save($this->data)) {
					$this->Log->write($this->Session->read('userdata.Usuario.usua_id'), 'Nuevo Rubro Interno', $_REQUEST);
					$this->Session->setFlash(__('El rubro ha sido guardado', true));
					$this->redirect(array('action' => 'index'));
				} else {
					$this->Session->setFlash(__(utf8_encode('No se pudo guardar el rubro. Por favor inténtelo nuevamente'), true));
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
			$this->Rubro->set($this->data);
			
			if ($this->Rubro->validates()) {
				if ($this->Rubro->save($this->data)) {
					$this->Log->write($this->Session->read('userdata.Usuario.usua_id'), utf8_encode('Modificación Rubro Interno'), $_REQUEST);
					$this->Session->setFlash(__('El rubro ha sido guardado', true));
					$this->redirect(array('action' => 'index'));
				} else {
					$this->Session->setFlash(__(utf8_encode('No se pudo guardar el rubro. Por favor inténtelo nuevamente'), true));
				}
			}
		}
		if (empty($this->data)) {
			$this->data = $this->Rubro->read(null, $id);
		}
	}

	function delete($id = null) {
		if (!$id) {
			$this->Session->setFlash(__('ID invalido', true));
			$this->redirect(array('action'=>'index'));
		}
		if ($this->Rubro->delete($id)) {
			$this->Log->write($this->Session->read('userdata.Usuario.usua_id'), utf8_encode('Eliminación Rubro Interno'), $_REQUEST);		
			$this->Session->setFlash(__('Rubro eliminado', true));
			$this->redirect(array('action'=>'index'));
		}
		$this->Session->setFlash(__('No se pudo eliminar el rubro', true));
		$this->redirect(array('action' => 'index'));
	}
}
?>