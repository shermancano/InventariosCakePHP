<?php
class ResponsablesController extends AppController {
	var $name = 'Responsables';
	
	function index() {
		$this->Responsable->recursive = 0;
		$userdata = $this->Session->read('userdata');
		$ceco_id = $userdata['CentroCosto']['ceco_id'];
		$responsables = $this->paginate('Responsable', array('OR' => array('CentroCosto.ceco_id_padre = '.$ceco_id, 'CentroCosto.ceco_id = '.$ceco_id)));
		$this->set('responsables', $responsables);
	}
	
	function add() {
		if (!empty($this->data)) {
			$this->Responsable->set($this->data);
			
			if ($this->Responsable->validates()) {
				if ($this->Responsable->save($this->data)) {
					$this->Log->write($this->Session->read('userdata.Usuario.usua_id'), "Nuevo Responsable", $_REQUEST);
					$this->Session->setFlash(__('El responsable ha sido guardado', true));
					$this->redirect(array('action' => 'index'));
				} else {
					$this->Session->setFlash(__(utf8_encode('No se pudo guardar el responsable, por favor inténtelo nuevamente'), true));
				}
			}
		}
		
		$estados = $this->Responsable->EstadoRegistro->find('list', array('fields' => array('esre_id', 'esre_nombre')));
		$this->set('estados', $estados);
		$centros_costos = $this->Session->read('userdata.selectCC');
		$this->set('centros_costos', $centros_costos);
	}
	
	function edit($resp_id) {
		if (!$resp_id && empty($this->data)) {
			$this->Session->setFlash(__('ID invalido', true));
			$this->redirect(array('action' => 'index'));
		}
		
		if (!empty($this->data)) {
			$this->Responsable->set($this->data);
			
			if ($this->Responsable->validates()) {
				if ($this->Responsable->save($this->data)) {
					$this->Log->write($this->Session->read('userdata.Usuario.usua_id'), utf8_encode("Modificación Responsable"), $_REQUEST);
					$this->Session->setFlash(__('El responsable ha sido guardado', true));
					$this->redirect(array('action' => 'index'));
				} else {
					$this->Session->setFlash(__(utf8_encode('No se pudo guardar el responsable, por favor inténtelo nuevamente'), true));
				}
			}
		}
		
		if (empty($this->data)) {
			$this->data = $this->Responsable->read(null, $resp_id);
		}
		
		$estados = $this->Responsable->EstadoRegistro->find('list', array('fields' => array('esre_id', 'esre_nombre')));
		$this->set('estados', $estados);
		$centros_costos = $this->Session->read('userdata.selectCC');
		$this->set('centros_costos', $centros_costos);
	}
	
	function delete($id = null) {
		if (!$id) {
			$this->Session->setFlash(__('ID invalido', true));
			$this->redirect(array('action'=>'index'));
		}
		if ($this->Responsable->delete($id)) {
			$this->Log->write($this->Session->read('userdata.Usuario.usua_id'), utf8_encode("Eliminación Responsable"), $_REQUEST);
			$this->Session->setFlash(__('El responsable ha sido eliminado', true));
			$this->redirect(array('action'=>'index'));
		}
		$this->Session->setFlash(__('El responsable no se pudo eliminar', true));
		$this->redirect(array('action' => 'index'));
	}
	
}
?>
