<?php
class PerfilesController extends AppController {
	var $name = 'Perfiles';
	var $uses = array('Perfil');
	
	function index() {
		$this->Perfil->recursive = 0;
		$perfiles = $this->paginate();
		$this->set('perfiles', $perfiles);
	}
	
	function add() {
	
		if (!empty($this->data)) {
			$this->Perfil->create();
			$this->Perfil->set($this->data);
			
			if ($this->Perfil->validates()) {
				if ($this->Perfil->save($this->data)) {
					$this->Log->write($this->Session->read('userdata.Usuario.usua_id'), 'Nuevo Perfil', $_REQUEST);
					$this->Session->setFlash(__('El perfil ha sido guardado', true));
					$this->redirect(array('action' => 'index'));
				} else {
					$this->Session->setFlash(__(utf8_encode('No se pudo salvar el perfil. Por favor, inténtelo nuevamente'), true));
				}
			}
		}
	}
	
	function edit($id) {
	
		if (!$id && empty($this->data)) {
			$this->Session->setFlash(__('ID invalido', true));
			$this->redirect(array('action' => 'index'));
		}
		if (!empty($this->data)) {
			$this->Perfil->set($this->data);
			
			if ($this->Perfil->validates()) {
				if ($this->Perfil->save($this->data)) {
					$this->Log->write($this->Session->read('userdata.Usuario.usua_id'), utf8_encode('Modificación de Perfil'), $_REQUEST);
					$this->Session->setFlash(__('El perfil ha sido guardado', true));
					$this->redirect(array('action' => 'index'));
				} else {
					$this->Session->setFlash(__(utf8_encode('El perfil no pudo ser guardado. Por favor inténtelo nuevamente.'), true));
				}
			}
		}
		if (empty($this->data)) {
			$this->data = $this->Perfil->read(null, $id);
		}
	}
	
	function delete($id = null) {
		if (!$id) {
			$this->Session->setFlash(__('ID invalido', true));
			$this->redirect(array('action'=>'index'));
		}
		if ($this->Perfil->delete($id)) {
			$this->Log->write($this->Session->read('userdata.Usuario.usua_id'), utf8_encode('Eliminación de Perfil'), $_REQUEST);
			$this->Session->setFlash(__('Perfil eliminado', true));
			$this->redirect(array('action'=>'index'));
		}
		$this->Session->setFlash(__('No se pudo eliminar el perfil', true));
		$this->redirect(array('action' => 'index'));
	}
}
?>