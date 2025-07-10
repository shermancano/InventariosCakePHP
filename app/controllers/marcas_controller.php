<?php
class MarcasController extends AppController {
	var $name = 'Marcas';
	
	function index() {
		$this->Marca->recursive = 0;
		$marcas = $this->paginate();
		$this->set('marcas', $marcas);
	}
	
	function add() {
		if (!empty($this->data)) {
			$this->Marca->set($this->data);
			
			if ($this->Marca->validates()) {
				if ($this->Marca->save($this->data)) {
					$this->Log->write($this->Session->read('userdata.Usuario.usua_id'), "Nueva Marca", $_REQUEST);
					$this->Session->setFlash(__('La marca ha sido guardada', true));
					$this->redirect(array('action' => 'index'));
				} else {
					$this->Session->setFlash(__(utf8_encode('No se pudo guardar la marca, por favor inténtelo nuevamente'), true));
				}
			}
		}
	}
	
	function edit($marc_id) {
		if (!$marc_id && empty($this->data)) {
			$this->Session->setFlash(__('ID invalido', true));
			$this->redirect(array('action' => 'index'));
		}
		
		if (!empty($this->data)) {
			$this->Marca->set($this->data);
			
			if ($this->Marca->validates()) {
				if ($this->Marca->save($this->data)) {
					$this->Log->write($this->Session->read('userdata.Usuario.usua_id'), utf8_encode("Modificación Marca"), $_REQUEST);
					$this->Session->setFlash(__('La marca ha sido guardada', true));
					$this->redirect(array('action' => 'index'));
				} else {
					$this->Session->setFlash(__(utf8_encode('No se pudo guardar la marca, por favor inténtelo nuevamente'), true));
				}
			}
		}
		
		if (empty($this->data)) {
			$this->data = $this->Marca->read(null, $marc_id);
		}
	}
	
	function delete($id = null) {
		if (!$id) {
			$this->Session->setFlash(__('ID invalido', true));
			$this->redirect(array('action'=>'index'));
		}
		if ($this->Marca->delete($id)) {
			$this->Log->write($this->Session->read('userdata.Usuario.usua_id'), utf8_encode("Eliminación Marca"), $_REQUEST);
			$this->Session->setFlash(__('La marca ha sido eliminada', true));
			$this->redirect(array('action'=>'index'));
		}
		$this->Session->setFlash(__('No se pudo eliminar la marca', true));
		$this->redirect(array('action' => 'index'));
	}
	
	function searchTodo() {
		$this->layout = 'ajax';
		$string = stripslashes($_GET['term']);
		//buscamos todo
		$info = $this->Marca->searchMarca($string);
		$marcas = array();
		
		if (sizeof($info) > 0) {
			foreach ($info as $marca) {
				$marca = array_pop($marca);
				$marcas[] = array('value' => $marca['marc_nombre'], 'label' => $marca['marc_nombre'], 'marc_id' => $marca['marc_id']);
			}
		}
		$this->set('json_info', json_encode($marcas));
	}
}
?>
