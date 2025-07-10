<?php
class ProveedoresController extends AppController {
	var $name = 'Proveedores';
	var $uses = array('Proveedor');

	function index() {
		$this->Proveedor->recursive = 0;
		$this->set('proveedores', $this->paginate());
	}

	function view($id = null) {
		if (!$id) {
			$this->Session->setFlash(__('ID invalido', true));
			$this->redirect(array('action' => 'index'));
		}
		$this->set('proveedore', $this->Proveedor->read(null, $id));
	}

	function add() {
		if (!empty($this->data)) {
			$this->Proveedor->create();
			$this->Proveedor->set($this->data);
			
			if ($this->Proveedor->validates()) {			
				if ($this->Proveedor->save($this->data)) {
					$this->Log->write($this->Session->read('userdata.Usuario.usua_id'), "Nuevo Proveedor", $_REQUEST);
					$this->Session->setFlash(__('El proveedor ha sido guardado', true));
					$this->redirect(array('action' => 'index'));
				} else {
					$this->Session->setFlash(__(utf8_encode('El proveedor no pudo ser guardado, por favor, inténtelo nuevamente'), true));
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
			$this->Proveedor->set($this->data);
			
			if ($this->Proveedor->validates()) {
				if ($this->Proveedor->save($this->data)) {
					$this->Log->write($this->Session->read('userdata.Usuario.usua_id'), utf8_encode("Modificación Proveedor"), $_REQUEST);
					$this->Session->setFlash(__('El proveedor ha sido guardado', true));
					$this->redirect(array('action' => 'index'));
				} else {
					$this->Session->setFlash(__(utf8_encode('El proveedor no pudo ser guardado, por favor, inténtelo nuevamente'), true));
				}
			}
		}
		if (empty($this->data)) {
			$this->data = $this->Proveedor->read(null, $id);
		}
	}

	function delete($id = null) {
		if (!$id) {
			$this->Session->setFlash(__('ID invalido', true));
			$this->redirect(array('action'=>'index'));
		}
		if ($this->Proveedor->delete($id)) {
			$this->Log->write($this->Session->read('userdata.Usuario.usua_id'), utf8_encode("Eliminación Proveedor"), $_REQUEST);
			$this->Session->setFlash(__('Proveedor eliminado', true));
			$this->redirect(array('action'=>'index'));
		}
		$this->Session->setFlash(__('No se pudo eliminar el proveedor', true));
		$this->redirect(array('action' => 'index'));
	}
	
	function searchTodo() {
		$this->layout = 'ajax';
		$string = stripslashes($_GET['term']);
		//buscamos todo
		$info = $this->Proveedor->searchProveedor($string);
		$proveedores = array();
		
		if (sizeof($info) > 0) {
			foreach ($info as $proveedor) {
				$proveedor = array_pop($proveedor);
				$proveedores[] = array('value' => $proveedor['prov_nombre'], 'label' => $proveedor['prov_nombre'], 'prov_id' => $proveedor['prov_id']);
			}
		}
		$this->set('json_info', json_encode($proveedores));
	}
}
?>