<?php
class DependenciasVirtualesController extends AppController {
	var $uses = array('DependenciaVirtual');
	
	function index() {
		$this->DependenciaVirtual->recursive = 0;
		$depedenciaVirtual = $this->paginate();
		$this->set('dependenciaVirtual', $depedenciaVirtual);
	}
	
	function add() {
		if (!empty($this->data)) {
			$this->DependenciaVirtual->set($this->data);
			
			if ($this->DependenciaVirtual->validates()) {
				if ($this->DependenciaVirtual->save($this->data)) {
					$this->Log->write($this->Session->read('userdata.Usuario.usua_id'), "Nueva Dependencia Virtual", $_REQUEST);
					$this->Session->setFlash(__('La dependencia ha sido guardada', true));					
					$this->redirect(array('action' => 'index'));
				} else {
					$this->Session->setFlash(__(utf8_encode('No se pudo guardar la dependencia, por favor inténtelo nuevamente'), true));
				}
			}
		}
	}
	
	function edit($devi_id = null) {
		if (!$devi_id && empty($this->data)) {
			$this->Session->setFlash(__('ID invalido', true));
			$this->redirect(array('action' => 'index'));
		}
		
		if (!empty($this->data)) {
			$this->DependenciaVirtual->set($this->data);
			
			if ($this->DependenciaVirtual->validates()) {
				if ($this->DependenciaVirtual->save($this->data)) {
					$this->Log->write($this->Session->read('userdata.Usuario.usua_id'), utf8_encode("Modificación Dependencia Virtual"), $_REQUEST);
					$this->Session->setFlash(__('La dependencia ha sido guardada', true));
					$this->redirect(array('action' => 'index'));
				} else {
					$this->Session->setFlash(__(utf8_encode('No se pudo guardar la dependencia, por favor inténtelo nuevamente'), true));
				}
			}
		}
		
		if (empty($this->data)) {
			$this->data = $this->DependenciaVirtual->read(null, $devi_id);
		}
	}
	
	function delete($id = null) {
		if (!$id) {
			$this->Session->setFlash(__('ID invalido', true));
			$this->redirect(array('action'=>'index'));
		}
		if ($this->DependenciaVirtual->delete($id)) {
			$this->Log->write($this->Session->read('userdata.Usuario.usua_id'), utf8_encode("Eliminación Dependencia Virtual"), $_REQUEST);
			$this->Session->setFlash(__('La dependencia ha sido eliminado', true));
			$this->redirect(array('action'=>'index'));
		}
		$this->Session->setFlash(__('La dependencia no se pudo eliminar', true));
		$this->redirect(array('action' => 'index'));	
	}
}
?>