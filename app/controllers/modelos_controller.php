<?php
class ModelosController extends AppController {
	var $name = 'Modelos';
	
	function index() {
		$this->Modelo->recursive = 0;
		$modelos = $this->paginate();
		$this->set('modelos', $modelos);
	}
	
	function add() {
		if (!empty($this->data)) {
			$this->Modelo->set($this->data);
			
			if ($this->Modelo->validates()) {
				if ($this->Modelo->save($this->data)) {
					$this->Log->write($this->Session->read('userdata.Usuario.usua_id'), "Nuevo Modelo", $_REQUEST);
					$this->Session->setFlash(__('El modelo ha sido guardado', true));
					$this->redirect(array('action' => 'index'));
				} else {
					$this->Session->setFlash(__(utf8_encode('No se pudo guardar el modelo, por favor inténtelo nuevamente'), true));
				}
			}
		}
	}
	
	function edit($mode_id) {
		if (!$mode_id && empty($this->data)) {
			$this->Session->setFlash(__('ID invalido', true));
			$this->redirect(array('action' => 'index'));
		}
		
		if (!empty($this->data)) {
			$this->Modelo->set($this->data);
			
			if ($this->Modelo->validates()) {
				if ($this->Modelo->save($this->data)) {
					$this->Log->write($this->Session->read('userdata.Usuario.usua_id'), utf8_encode("Modificación Marca"), $_REQUEST);
					$this->Session->setFlash(__('El modelo ha sido guardado', true));
					$this->redirect(array('action' => 'index'));
				} else {
					$this->Session->setFlash(__(utf8_encode('No se pudo guardar el modelo, por favor inténtelo nuevamente'), true));
				}
			}
		}
		
		if (empty($this->data)) {
			$this->data = $this->Modelo->read(null, $mode_id);
		}
	}
	
	function delete($id = null) {
		if (!$id) {
			$this->Session->setFlash(__('ID invalido', true));
			$this->redirect(array('action'=>'index'));
		}
		if ($this->Modelo->delete($id)) {
			$this->Log->write($this->Session->read('userdata.Usuario.usua_id'), utf8_encode("Eliminación Modelo"), $_REQUEST);
			$this->Session->setFlash(__('El modelo ha sido eliminado', true));
			$this->redirect(array('action'=>'index'));
		}
		$this->Session->setFlash(__('No se pudo eliminar el modelo', true));
		$this->redirect(array('action' => 'index'));
	}
	
	function searchTodo() {
		$this->layout = 'ajax';
		$string = stripslashes($_GET['term']);
		//buscamos todo
		$info = $this->Modelo->searchModelo($string);
		$modelos = array();
		
		if (sizeof($info) > 0) {
			foreach ($info as $modelo) {
				$modelo = array_pop($modelo);
				$modelos[] = array('value' => $modelo['mode_nombre'], 'label' => $modelo['mode_nombre'], 'mode_id' => $modelo['mode_id']);
			}
		}
		$this->set('json_info', json_encode($modelos));
	}
}
?>
