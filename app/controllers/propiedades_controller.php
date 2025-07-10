<?php
class PropiedadesController extends AppController {
	var $name = 'Propiedades';
	var $uses = array('Propiedad');
	
	function index() {
		$this->Propiedad->recursive = 0;
		$propiedades = $this->paginate();
		$this->set('propiedades', $propiedades);
	}
	
	function add() {
		if (!empty($this->data)) {
			$this->Propiedad->set($this->data);
			
			if ($this->Propiedad->validates()) {
				if ($this->Propiedad->save($this->data)) {
					$this->Log->write($this->Session->read('userdata.Usuario.usua_id'), "Nueva Propiedad", $_REQUEST);
					$this->Session->setFlash(__(utf8_encode('La propiedad ha sido guardada'), true));
					$this->redirect(array('action' => 'index'));
				} else {
					$this->Session->setFlash(__(utf8_encode('No se pudo guardar la propiedad, por favor inténtelo nuevamente'), true));
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
			$this->Propiedad->set($this->data);
			
			if ($this->Propiedad->validates()) {
				if ($this->Propiedad->save($this->data)) {
					$this->Log->write($this->Session->read('userdata.Usuario.usua_id'), utf8_encode("Modificación Propiedad"), $_REQUEST);
					$this->Session->setFlash(__(utf8_encode('La propiedad ha sido guardada'), true));
					$this->redirect(array('action' => 'index'));
				} else {
					$this->Session->setFlash(__(utf8_encode('No se pudo guardar la propiedad, por favor inténtelo nuevamente'), true));
				}
			}
		}
		
		if (empty($this->data)) {
			$this->data = $this->Propiedad->read(null, $situ_id);
		}
	}
	
	function delete($id = null) {
		if (!$id) {
			$this->Session->setFlash(__('ID invalido', true));
			$this->redirect(array('action'=>'index'));
		}
		if ($this->Propiedad->delete($id)) {
			$this->Log->write($this->Session->read('userdata.Usuario.usua_id'), utf8_encode("Eliminación Propiedad"), $_REQUEST);
			$this->Session->setFlash(__('La propiedad ha sido eliminada', true));
			$this->redirect(array('action'=>'index'));
		}
		$this->Session->setFlash(__('La propiedad no se pudo eliminar', true));
		$this->redirect(array('action' => 'index'));
	}
}
?>
