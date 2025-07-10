<?php
class FinanciamientosController extends AppController {
	var $name = 'Financiamientos';
	
	function index() {
		$this->Financiamiento->recursive = 0;
		$financiamientos = $this->paginate();
		$this->set('financiamientos', $financiamientos);
	}
	
	function add() {
		if (!empty($this->data)) {
			$this->Financiamiento->set($this->data);
			
			if ($this->Financiamiento->validates()) {
				if ($this->Financiamiento->save($this->data)) {
					$this->Log->write($this->Session->read('userdata.Usuario.usua_id'), "Nuevo Financiamiento", $_REQUEST);
					$this->Session->setFlash(__('El financiamiento ha sido guardado', true));
					$this->redirect(array('action' => 'index'));
				} else {
					$this->Session->setFlash(__(utf8_encode('No se pudo guardar el financiamiento, por favor inténtelo nuevamente'), true));
				}
			}
		}
	}
	
	function edit($fina_id) {
		if (!$fina_id && empty($this->data)) {
			$this->Session->setFlash(__('ID invalido', true));
			$this->redirect(array('action' => 'index'));
		}
		
		if (!empty($this->data)) {
			$this->Financiamiento->set($this->data);
			
			if ($this->Financiamiento->validates()) {
				if ($this->Financiamiento->save($this->data)) {
					$this->Log->write($this->Session->read('userdata.Usuario.usua_id'), utf8_encode('Modificación Financiamiento'), $_REQUEST);
					$this->Session->setFlash(__('El financiamiento ha sido guardado', true));
					$this->redirect(array('action' => 'index'));
				} else {
					$this->Session->setFlash(__(utf8_encode('No se pudo guardar el financiamiento, por favor inténtelo nuevamente'), true));
				}
			}
		}
		
		if (empty($this->data)) {
			$this->data = $this->Financiamiento->read(null, $fina_id);
		}
	}
	
	function delete($id = null) {
		if (!$id) {
			$this->Session->setFlash(__('ID invalido', true));
			$this->redirect(array('action'=>'index'));
		}
		if ($this->Financiamiento->delete($id)) {
			$this->Log->write($this->Session->read('userdata.Usuario.usua_id'), utf8_encode("Eliminación Financiamiento"), $_REQUEST);
			$this->Session->setFlash(__('El financiamiento ha sido eliminado', true));
			$this->redirect(array('action'=>'index'));
		}
		$this->Session->setFlash(__('No se pudo eliminar el financiamiento', true));
		$this->redirect(array('action' => 'index'));
	}
}
?>