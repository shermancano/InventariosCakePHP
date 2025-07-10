<?php
class BancosController extends AppController {
	var $name = 'Bancos';

	function index() {
		$this->Banco->recursive = 0;
		$this->set('bancos', $this->paginate());
	}

	function view($id = null) {
		if (!$id) {
			$this->Session->setFlash(__('Id invalido', true));
			$this->redirect(array('action' => 'index'));
		}
		$this->set('banco', $this->Banco->read(null, $id));
	}

	function add() {
		if (!empty($this->data)) {
			$this->Banco->create();
			$this->Banco->set($this->data);
			
			if ($this->Banco->validates()) {
				if ($this->Banco->save($this->data)) {
					$this->Log->write($this->Session->read('userdata.Usuario.usua_id'), 'Nuevo Banco', $_REQUEST);
					$this->Session->setFlash(__('El banco ha sido guardado', true));
					$this->redirect(array('action' => 'index'));
				} else {
					$this->Session->setFlash(__(utf8_encode('No se pudo guardar el banco, por favor inténtelo nuevamente'), true));
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
			$this->Banco->set($this->data);
			
			if ($this->Banco->validates()) {
				if ($this->Banco->save($this->data)) {
					$this->Log->write($this->Session->read('userdata.Usuario.usua_id'), utf8_encode('Modificación Banco'), $_REQUEST);
					$this->Session->setFlash(__('El banco ha sido guardado', true));
					$this->redirect(array('action' => 'index'));
				} else {
					$this->Session->setFlash(__(utf8_encode('No se pudo guardar el banco, por favor inténtelo nuevamente'), true));
				}
			}
		}
		if (empty($this->data)) {
			$this->data = $this->Banco->read(null, $id);
		}
	}

	function delete($id = null) {
		if (!$id) {
			$this->Session->setFlash(__('ID invalido', true));
			$this->redirect(array('action'=>'index'));
		}
		if ($this->Banco->delete($id)) {
			$this->Log->write($this->Session->read('userdata.Usuario.usua_id'), utf8_encode('Eliminación Banco'), $_REQUEST);
			$this->Session->setFlash(__('Banco eliminado', true));
			$this->redirect(array('action'=>'index'));
		}
		$this->Session->setFlash(__('El banco no se pudo eliminar', true));
		$this->redirect(array('action' => 'index'));
	}
}
?>