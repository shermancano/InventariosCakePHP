<?php
class CentrosCostosController extends AppController {
	var $name = 'CentrosCostos';
	var $uses = array('CentroCosto', 'Responsable', 'TipoLocalizacion');
	
	function index() {
		$this->CentroCosto->recursive = 0;
		$userdata = $this->Session->read('userdata');
		$ceco_id = $userdata['CentroCosto']['ceco_id'];
		$centros_costos = $this->Session->read('userdata.arrayCC');
		$centros_costos = $this->paginate('CentroCosto', array('AND' => array('CentroCosto.ceco_id != ' => $ceco_id, 'CentroCosto.ceco_id' => $centros_costos)));
		$this->set('centros_costos', $centros_costos);
	}
	
	function add() {
		if (!empty($this->data)) {
			$this->CentroCosto->set($this->data);
			
			if ($this->CentroCosto->validates()) {
				if ($this->CentroCosto->save($this->data)) {
					$this->Log->write($this->Session->read('userdata.Usuario.usua_id'), "Nuevo Centro de Costo", $_REQUEST);
					$this->Session->setFlash(__('El Centro de Costo ha sido guardado', true));
					$this->redirect(array('action' => 'index'));
				} else {
					$this->Session->setFlash(__(utf8_encode('No se pudo guardar el Centro de Costo, por favor inténtelo nuevamente'), true));
				}
			}
		}
		
		$comunas = $this->CentroCosto->Comuna->find('list', array('fields' => array('comu_id', 'comu_nombre'), 'order' => 'comu_nombre ASC'));
		$this->set('comunas', $comunas);
		$centros_costos = $this->Session->read('userdata.selectCC');
		$this->set('centros_costos', $centros_costos);
		$tipos_localizaciones = $this->CentroCosto->TipoLocalizacion->find('list', array('fields' => 'tilo_nombre'));
		$this->set('tipos_localizaciones', $tipos_localizaciones); 
	}
	
	function edit($ceco_id) {
		if (!$ceco_id && empty($this->data)) {
			$this->Session->setFlash(__('ID invalido', true));
			$this->redirect(array('action' => 'index'));
		}
		
		if (!empty($this->data)) {
			$this->CentroCosto->set($this->data);
			
			if ($this->CentroCosto->validates()) {
				if ($this->CentroCosto->save($this->data)) {
					$this->Log->write($this->Session->read('userdata.Usuario.usua_id'), utf8_encode("Modificación Centro de Costo"), $_REQUEST);
					$this->Session->setFlash(__('El Centro de Costo ha sido guardado', true));
					$this->redirect(array('action' => 'index'));
				} else {
					$this->Session->setFlash(__(utf8_encode('No se pudo guardar el Centro de Costo, por favor inténtelo nuevamente'), true));
				}
			}
		}
		
		if (empty($this->data)) {
			$this->data = $this->CentroCosto->read(null, $ceco_id);
		}
		
		$comunas = $this->CentroCosto->Comuna->find('list', array('fields' => array('comu_id', 'comu_nombre'), 'order' => 'comu_nombre ASC'));
		$this->set('comunas', $comunas);
		$centros_costos = $this->Session->read('userdata.selectCC');
		$this->set('centros_costos', $centros_costos);
		$tipos_localizaciones = $this->CentroCosto->TipoLocalizacion->find('list', array('fields' => 'tilo_nombre'));
		$this->set('tipos_localizaciones', $tipos_localizaciones); 
	}
	
	function delete($id = null) {
		if (!$id) {
			$this->Session->setFlash(__('ID invalido', true));
			$this->redirect(array('action'=>'index'));
		}
		if ($this->CentroCosto->delete($id)) {
			$this->Log->write($this->Session->read('userdata.Usuario.usua_id'), utf8_encode("Eliminación Centro de Costo"), $_REQUEST);
			$this->Session->setFlash(__('El Centro de Costo ha sido eliminado', true));
			$this->redirect(array('action'=>'index'));
		}
		$this->Session->setFlash(__('El Centro de Costo no se pudo eliminar', true));
		$this->redirect(array('action' => 'index'));
	}
}

?>
