<?php
class SituacionesController extends AppController {
	var $name = 'Situaciones';
	var $uses = array('Situacion');
	
	function index() {
		$this->Situacion->recursive = 0;
		$situaciones = $this->paginate();
		$this->set('situaciones', $situaciones);
	}
	
	function add() {
		if (!empty($this->data)) {
			$this->Situacion->set($this->data);
			
			if ($this->Situacion->validates()) {
				if ($this->Situacion->save($this->data)) {
					$this->Log->write($this->Session->read('userdata.Usuario.usua_id'), utf8_encode("Nueva Situación"), $_REQUEST);
					$this->Session->setFlash(__(utf8_encode('La situación ha sido guardada'), true));
					$this->redirect(array('action' => 'index'));
				} else {
					$this->Session->setFlash(__(utf8_encode('No se pudo guardar la situación, por favor inténtelo nuevamente'), true));
				}
			}
		}
	}
	
	function edit($situ_id) {
		if (!$situ_id && empty($this->data)) {
			$this->Session->setFlash(__('ID invalido', true));
			$this->redirect(array('action' => 'index'));
		}
		
		if (!empty($this->data)) {
			$this->Situacion->set($this->data);
			
			if ($this->Situacion->validates()) {
				if ($this->Situacion->save($this->data)) {
					$this->Log->write($this->Session->read('userdata.Usuario.usua_id'), utf8_encode("Modificación Situación"), $_REQUEST);
					$this->Session->setFlash(__(utf8_encode('La situación ha sido guardada'), true));
					$this->redirect(array('action' => 'index'));
				} else {
					$this->Session->setFlash(__(utf8_encode('No se pudo guardar la situación, por favor inténtelo nuevamente'), true));
				}
			}
		}
		
		if (empty($this->data)) {
			$this->data = $this->Situacion->read(null, $situ_id);
		}
	}
	
	function delete($id = null) {
		if (!$id) {
			$this->Session->setFlash(__('ID invalido', true));
			$this->redirect(array('action'=>'index'));
		}
		if ($this->Situacion->delete($id)) {
			$this->Log->write($this->Session->read('userdata.Usuario.usua_id'), utf8_encode("Eliminación Situación"), $_REQUEST);
			$this->Session->setFlash(__('La situacion ha sido eliminada', true));
			$this->redirect(array('action'=>'index'));
		}
		$this->Session->setFlash(__('La situación no se pudo eliminar', true));
		$this->redirect(array('action' => 'index'));
	}
}
?>
