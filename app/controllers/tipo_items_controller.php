<?php
class TipoItemsController extends AppController {
	var $name = 'TipoItems';

	function index() {
		$this->TipoItem->recursive = 0;
		$tipo_items = $this->paginate();
		$this->set('tipoItems', $tipo_items);
	}

	function add() {
		if (!empty($this->data)) {
			$this->TipoItem->create();
			$this->TipoItem->set($this->data);
			
			if ($this->TipoItem->validates()) {
				if ($this->TipoItem->save($this->data)) {
					$this->Log->write($this->Session->read('userdata.Usuario.usua_id'), utf8_encode('Nuevo Tipo de �tem'), $_REQUEST);
					$this->Session->setFlash(__('El tipo de item ha sido guardado', true));
					$this->redirect(array('action' => 'index'));
				} else {
					$this->Session->setFlash(__(utf8_encode('El tipo de item no pudo ser guardado. Por favor int�ntelo nuevamente'), true));
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
			$this->TipoItem->set($this->data);
			
			if ($this->TipoItem->validates()) {
				if ($this->TipoItem->save($this->data)) {
					$this->Log->write($this->Session->read('userdata.Usuario.usua_id'), utf8_encode('Modificaci�n Tipo de �tem'), $_REQUEST);
					$this->Session->setFlash(__('El tipo de item ha sido guardado', true));
					$this->redirect(array('action' => 'index'));
				} else {
					$this->Session->setFlash(__(utf8_encode('El tipo de item no pudo ser guardado. Por favor int�ntelo nuevamente'), true));
				}
			}
		}
		if (empty($this->data)) {
			$this->data = $this->TipoItem->read(null, $id);
		}
	}

	function delete($id = null) {
		if (!$id) {
			$this->Session->setFlash(__('ID invalido', true));
			$this->redirect(array('action'=>'index'));
		}
		if ($this->TipoItem->delete($id)) {
			$this->Log->write($this->Session->read('userdata.Usuario.usua_id'), utf8_encode('Eliminaci�n Tipo de �tem'), $_REQUEST);
			$this->Session->setFlash(__('Tipo de item eliminado', true));
			$this->redirect(array('action'=>'index'));
		}
		$this->Session->setFlash(__('No se pudo eliminar el tipo de item', true));
		$this->redirect(array('action' => 'index'));
	}
}
?>