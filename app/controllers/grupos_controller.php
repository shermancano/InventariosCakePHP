<?php
class GruposController extends AppController {
	var $name = 'Grupos';

	function index() {
		$this->Grupo->recursive = 2;
		$grupos = $this->paginate();
		$this->set('grupos', $grupos);
	}

	function view($id = null) {
		if (!$id) {
			$this->Session->setFlash(__('ID invalido', true));
			$this->redirect(array('action' => 'index'));
		}
		$this->set('grupo', $this->Grupo->read(null, $id));
	}

	function add() {
		if (!empty($this->data)) {
			$this->Grupo->create();
			$this->Grupo->set($this->data);
			
			if ($this->Grupo->validates()) {
				if ($this->Grupo->save($this->data)) {
					$this->Log->write($this->Session->read('userdata.Usuario.usua_id'), 'Nuevo Grupo', $_REQUEST);
					$this->Session->setFlash(__('El grupo ha sido guardado', true));
					$this->redirect(array('action' => 'index'));
				} else {
					$this->Session->setFlash(__(utf8_encode('No se pudo guardar el grupo. Por favor inténtelo nuevamente.'), true));
				}
			}
		}
		
		$tipos_familias = $this->Grupo->Familia->TipoFamilia->find('list', array('fields' => array('tifa_id', 'tifa_nombre'), 'order' => 'tifa_id ASC'));
		$this->set('tipos_familias', $tipos_familias);
	}

	function edit($id = null) {
		if (!$id && empty($this->data)) {
			$this->Session->setFlash(__('ID invalido', true));
			$this->redirect(array('action' => 'index'));
		}
		if (!empty($this->data)) {
			$this->Grupo->set($this->data);
			
			if ($this->Grupo->validates()) {
				if ($this->Grupo->save($this->data)) {
					$this->Log->write($this->Session->read('userdata.Usuario.usua_id'), utf8_encode('Modificación Grupo'), $_REQUEST);
					$this->Session->setFlash(__('El grupo ha sido guardado', true));
					$this->redirect(array('action' => 'index'));
				} else {
					$this->Session->setFlash(__(utf8_encode('No se pudo guardar el grupo. Por favor inténtelo nuevamente.'), true));
				}
			}
		}
		if (empty($this->data)) {
			$this->data = $this->Grupo->read(null, $id);
		}
		
		$tipos_familias = $this->Grupo->Familia->TipoFamilia->find('list', array('fields' => array('tifa_id', 'tifa_nombre'), 'order' => 'tifa_id ASC'));
		$this->set('tipos_familias', $tipos_familias);
		$familias = $this->Grupo->Familia->find('list', array('fields' => array('Familia.fami_id', 'Familia.fami_nombre')));
		$this->set('familias', $familias);
	}

	function delete($id = null) {
		if (!$id) {
			$this->Session->setFlash(__('ID invalido', true));
			$this->redirect(array('action'=>'index'));
		}
		if ($this->Grupo->delete($id)) {
			$this->Log->write($this->Session->read('userdata.Usuario.usua_id'), utf8_encode('Eliminación Grupo'), $_REQUEST);
			$this->Session->setFlash(__('Grupo eliminado', true));
			$this->redirect(array('action'=>'index'));
		}
		$this->Session->setFlash(__('No se pudo eliminar el grupo', true));
		$this->redirect(array('action' => 'index'));
	}
	
	function findGrupos($fami_id) {
		$this->layout = "ajax";
		$info = $this->Grupo->find('all', array('conditions' => array('Grupo.fami_id' => $fami_id), 'order' => 'Grupo.grup_nombre asc', 'fields' => array('Grupo.grup_id', 'Grupo.grup_nombre')));
		$this->set('info', json_encode($info));
	}
	
	function searchTodo() {
		$this->layout = 'ajax';
		$string = stripslashes($_GET['term']);
		//buscamos todo
		$info = $this->Grupo->searchGrupo($string);
		$grupos = array();
		
		if (sizeof($info) > 0) {
			foreach ($info as $grupo) {
				$grupo = array_pop($grupo);
				$grupos[] = array('value' => $grupo['grup_nombre'], 'label' => $grupo['grup_nombre'], 'grup_id' => $grupo['grup_id']);
			}
		}
		$this->set('json_info', json_encode($grupos));
	}
}
