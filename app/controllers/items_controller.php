<?php
class ItemsController extends AppController {
	var $name = 'Items';

	function index() {
		$this->Item->recursive = 0;
		$this->set('items', $this->paginate());
	}

	function add() {
		if (!empty($this->data)) {
			$this->Item->create();
			$this->Item->set($this->data);
			
			if ($this->Item->validates()) {
				if ($this->Item->save($this->data)) {
					$this->Log->write($this->Session->read('userdata.Usuario.usua_id'), utf8_encode('Nuevo Ítem de Evaluación'), $_REQUEST);
					$this->Session->setFlash(__('El item ha sido guardado', true));
					$this->redirect(array('action' => 'index'));
				} else {
					$this->Session->setFlash(__(utf8_encode('No se pudo guardar el item. Por favor inténtelo nuevamente'), true));
				}
			}
		}
		$tipo_items = $this->Item->TipoItem->find('list', array('order' => 'tiit_id', 'fields' => array('tiit_descripcion')));
		$this->set('tipo_items', $tipo_items);
	}

	function edit($id = null) {
		if (!$id && empty($this->data)) {
			$this->Session->setFlash(__('ID invalido', true));
			$this->redirect(array('action' => 'index'));
		}
		if (!empty($this->data)) {
			$this->Item->set($this->data);
			$this->Item->set($this->data);
			
			if ($this->Item->validates()) {
				if ($this->Item->save($this->data)) {
					$this->Log->write($this->Session->read('userdata.Usuario.usua_id'), utf8_encode('Modificación Ítem de Evaluación'), $_REQUEST);
					$this->Session->setFlash(__('El item ha sido guardado', true));
					$this->redirect(array('action' => 'index'));
				} else {
					$this->Session->setFlash(__(utf8_encode('No se pudo guardar el item. Por favor inténtelo nuevamente'), true));
				}
			}
		}
		if (empty($this->data)) {
			$this->data = $this->Item->read(null, $id);
		}
		
		$tipo_items = $this->Item->TipoItem->find('list', array('order' => 'tiit_id', 'fields' => array('tiit_descripcion')));
		$this->set('tipo_items', $tipo_items);
	}

	function delete($id = null) {
		if (!$id) {
			$this->Session->setFlash(__('ID invalido', true));
			$this->redirect(array('action'=>'index'));
		}
		if ($this->Item->delete($id)) {
			$this->Log->write($this->Session->read('userdata.Usuario.usua_id'), utf8_encode('Eliminación Ítem de Evaluación'), $_REQUEST);
			$this->Session->setFlash(__('Item eliminado', true));
			$this->redirect(array('action'=>'index'));
		}
		$this->Session->setFlash(__('No se pudo eliminar el item', true));
		$this->redirect(array('action' => 'index'));
	}
}
?>