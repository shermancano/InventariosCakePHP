<?php
class ColoresController extends AppController {
	var $name = 'Colores';
	var $uses = array('Color');
	
	function index() {
		$this->Color->recursive = 0;
		$colores = $this->paginate();
		$this->set('colores', $colores);
	}
	
	function add() {
		if (!empty($this->data)) {
			$this->Color->set($this->data);
			
			if ($this->Color->validates()) {
				if ($this->Color->save($this->data)) {
					$this->Log->write($this->Session->read('userdata.Usuario.usua_id'), "Nuevo Color", $_REQUEST);
					$this->Session->setFlash(__('El color ha sido guardado', true));					
					$this->redirect(array('action' => 'index'));
				} else {
					$this->Session->setFlash(__(utf8_encode('No se pudo guardar el color, por favor inténtelo nuevamente'), true));
				}
			}
		}
	}
	
	function edit($colo_id) {
		if (!$colo_id && empty($this->data)) {
			$this->Session->setFlash(__('ID invalido', true));
			$this->redirect(array('action' => 'index'));
		}
		
		if (!empty($this->data)) {
			$this->Color->set($this->data);
			
			if ($this->Color->validates()) {
				if ($this->Color->save($this->data)) {
					$this->Log->write($this->Session->read('userdata.Usuario.usua_id'), utf8_encode("Modificación Color"), $_REQUEST);
					$this->Session->setFlash(__('El color ha sido guardado', true));
					$this->redirect(array('action' => 'index'));
				} else {
					$this->Session->setFlash(__(utf8_encode('No se pudo guardar el color, por favor inténtelo nuevamente'), true));
				}
			}
		}
		
		if (empty($this->data)) {
			$this->data = $this->Color->read(null, $colo_id);
		}
	}
	
	function delete($id = null) {
		if (!$id) {
			$this->Session->setFlash(__('ID invalido', true));
			$this->redirect(array('action'=>'index'));
		}
		if ($this->Color->delete($id)) {
			$this->Log->write($this->Session->read('userdata.Usuario.usua_id'), utf8_encode("Eliminación Color"), $_REQUEST);
			$this->Session->setFlash(__('El color ha sido eliminado', true));
			$this->redirect(array('action'=>'index'));
		}
		$this->Session->setFlash(__('El color no se pudo eliminar', true));
		$this->redirect(array('action' => 'index'));
	}
}
?>
