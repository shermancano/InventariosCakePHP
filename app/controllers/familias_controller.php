<?php
class FamiliasController extends AppController {
	var $name = 'Familias';

	function index() {
		$this->Familia->recursive = 0;
		$this->set('familias', $this->paginate());
	}

	function view($id = null) {
		if (!$id) {
			$this->Session->setFlash(__('ID invalido', true));
			$this->redirect(array('action' => 'index'));
		}
		$this->set('familia', $this->Familia->read(null, $id));
	}

	function add() {
		if (!empty($this->data)) {
			$this->Familia->create();
			$this->Familia->set($this->data);
			
			if ($this->Familia->validates()) {
				if ($this->Familia->save($this->data)) {
					$this->Log->write($this->Session->read('userdata.Usuario.usua_id'), 'Nueva Familia', $_REQUEST);
					$this->Session->setFlash(__('La familia ha sido guardada', true));
					$this->redirect(array('action' => 'index'));
				} else {
					$this->Session->setFlash(__(utf8_encode('No se pudo guardar la familia. Por favor, inténtelo nuevamente.'), true));
				}
			}
		}
		$tipos_familia = $this->Familia->TipoFamilia->find('list', array('fields' => array('tifa_id','tifa_nombre')));
		$cuentas_contables = $this->Familia->CuentaContable->find('list');
		$this->set('tipos_familia', $tipos_familia);
		$this->set('cuentas_contables', $cuentas_contables);
	}

	function edit($id = null) {
		if (!$id && empty($this->data)) {
			$this->Session->setFlash(__('ID invalido', true));
			$this->redirect(array('action' => 'index'));
		}
		if (!empty($this->data)) {
			$this->Familia->set($this->data);
			
			if ($this->Familia->validates()) {
				if ($this->Familia->save($this->data)) {
					$this->Log->write($this->Session->read('userdata.Usuario.usua_id'), utf8_encode('Modificación Familia'), $_REQUEST);
					$this->Session->setFlash(__('La familia ha sido guardada', true));
					$this->redirect(array('action' => 'index'));
				} else {
					$this->Session->setFlash(__(utf8_encode('No se pudo guardar la familia. Por favor, inténtelo nuevamente.'), true));
				}
			}
		}
		if (empty($this->data)) {
			$this->data = $this->Familia->read(null, $id);
		}
		$tipos_familia = $this->Familia->TipoFamilia->find('list', array('fields' => array('tifa_id','tifa_nombre')));
		$cuentas_contables = $this->Familia->CuentaContable->find('list');
		$this->set('tipos_familia', $tipos_familia);
		$this->set('cuentas_contables', $cuentas_contables);
	}

	function delete($id = null) {
		if (!$id) {
			$this->Session->setFlash(__('ID invalido', true));
			$this->redirect(array('action'=>'index'));
		}
		if ($this->Familia->delete($id)) {
			$this->Log->write($this->Session->read('userdata.Usuario.usua_id'), utf8_encode('Eliminación Familia'), $_REQUEST);
			$this->Session->setFlash(__('Familia eliminada', true));
			$this->redirect(array('action'=>'index'));
		}
		$this->Session->setFlash(__('No se pudo eliminar la familia', true));
		$this->redirect(array('action' => 'index'));
	}
	
	function findFamilias($tifa_id) {
		$this->layout = "ajax";
		$info = $this->Familia->find('all', array('conditions' => array('Familia.tifa_id' => $tifa_id), 'order' => 'Familia.fami_nombre asc', 'fields' => array('Familia.fami_id', 'Familia.fami_nombre')));
		$this->set('info', json_encode($info));
	}
	
}
