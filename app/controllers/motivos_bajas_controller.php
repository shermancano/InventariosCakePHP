<?php
class MotivosBajasController extends AppController {
	var $uses = array('MotivoBaja');
	
	function index() {
		$this->MotivoBaja->recursive = 0;
		$motivosBajas = $this->paginate();
		$this->set('motivos', $motivosBajas);
	}
	
	function add() {
		if (!empty($this->data)) {
			$this->MotivoBaja->set($this->data);
			
			if ($this->MotivoBaja->validates()) {
				if ($this->MotivoBaja->save($this->data)) {
					$this->Log->write($this->Session->read('userdata.Usuario.usua_id'), "Nuevo Motivo de Baja", $_REQUEST);
					$this->Session->setFlash(__('El motivo ha sido guardado', true));					
					$this->redirect(array('action' => 'index'));
				} else {
					$this->Session->setFlash(__(utf8_encode('No se pudo guardar el motivo, por favor inténtelo nuevamente'), true));
				}
			}
		}
	}
	
	function edit($moba_id = null) {
		if (!$moba_id && empty($this->data)) {
			$this->Session->setFlash(__('ID invalido', true));
			$this->redirect(array('action' => 'index'));
		}
		
		if (!empty($this->data)) {
			$this->MotivoBaja->set($this->data);
			
			if ($this->MotivoBaja->validates()) {
				if ($this->MotivoBaja->save($this->data)) {
					$this->Log->write($this->Session->read('userdata.Usuario.usua_id'), utf8_encode("Modificación Motivo de Baja"), $_REQUEST);
					$this->Session->setFlash(__('El motivo ha sido guardado', true));
					$this->redirect(array('action' => 'index'));
				} else {
					$this->Session->setFlash(__(utf8_encode('No se pudo guardar el motivo, por favor inténtelo nuevamente'), true));
				}
			}
		}
		
		if (empty($this->data)) {
			$this->data = $this->MotivoBaja->read(null, $moba_id);
		}
	}
	
	function delete($id = null) {
		if (!$id) {
			$this->Session->setFlash(__('ID invalido', true));
			$this->redirect(array('action'=>'index'));
		}
		if ($this->MotivoBaja->delete($id)) {
			$this->Log->write($this->Session->read('userdata.Usuario.usua_id'), utf8_encode("Eliminación Motivo de Baja"), $_REQUEST);
			$this->Session->setFlash(__('El motivo ha sido eliminado', true));
			$this->redirect(array('action'=>'index'));
		}
		$this->Session->setFlash(__('El motivo no se pudo eliminar', true));
		$this->redirect(array('action' => 'index'));	
	}
}
?>