<?php
class CuentasContablesController extends AppController {
	var $name = 'CuentasContables';
	var $uses = array('CuentaContable');
	
	function index() {
		$this->CuentaContable->recursive = 0;
		$cuentas_contables = $this->paginate();
		$this->set('cuentas_contables', $cuentas_contables);
	}
	
	function add() {
		if (!empty($this->data)) {
			$this->CuentaContable->set($this->data);
			
			if ($this->CuentaContable->validates()) {
				if ($this->CuentaContable->save($this->data)) {
					$this->Log->write($this->Session->read('userdata.Usuario.usua_id'), "Nueva Cuenta Contable", $_REQUEST);
					$this->Session->setFlash(__('La cuenta contable ha sido guardada', true));
					$this->redirect(array('action' => 'index'));
				} else {
					$this->Session->setFlash(__(utf8_encode('No se pudo guardar la cuenta contable, por favor inténtelo nuevamente'), true));
				}
			}
		}
	}
	
	function edit($cuco_id) {
		if (!$cuco_id && empty($this->data)) {
			$this->Session->setFlash(__('ID invalido', true));
			$this->redirect(array('action' => 'index'));
		}
		
		if (!empty($this->data)) {
			$this->CuentaContable->set($this->data);
			
			if ($this->CuentaContable->validates()) {
				if ($this->CuentaContable->save($this->data)) {
					$this->Log->write($this->Session->read('userdata.Usuario.usua_id'), utf8_encode("Modificación Cuenta Contable"), $_REQUEST);
					$this->Session->setFlash(__('La cuenta contable ha sido guardada', true));
					$this->redirect(array('action' => 'index'));
				} else {
					$this->Session->setFlash(__(utf8_encode('No se pudo guardar la cuenta contable, por favor inténtelo nuevamente'), true));
				}
			}
		}
		
		if (empty($this->data)) {
			$this->data = $this->CuentaContable->read(null, $cuco_id);
		}
	}
	
	function delete($id = null) {
		if (!$id) {
			$this->Session->setFlash(__('ID invalido', true));
			$this->redirect(array('action'=>'index'));
		}
		if ($this->CuentaContable->delete($id)) {
			$this->Log->write($this->Session->read('userdata.Usuario.usua_id'), utf8_encode("Eliminación Cuenta Contable"), $_REQUEST);
			$this->Session->setFlash(__('La cuenta contable ha sido eliminada', true));
			$this->redirect(array('action'=>'index'));
		}
		$this->Session->setFlash(__('No se pudo eliminar la cuenta contable', true));
		$this->redirect(array('action' => 'index'));
	}
}
?>
