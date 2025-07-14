<?php
class CentrosCostosController extends AppController {
	var $name = 'CentrosCostos';
	var $uses = array('CentroCosto', 'Responsable', 'TipoLocalizacion');
	
	function index() {
		$this->CentroCosto->recursive = 0;

		if (!empty($this->data)) {
			$this->params['named']['page'] = 1;
			$criterio = trim($this->data['CentroCosto']['busqueda']);
			$this->Session->write('userdata.criterio_ceco', $criterio);
			$userdata = $this->Session->read('userdata');
			$ceco_id = $userdata['CentroCosto']['ceco_id'];
			$centros_costos = $this->Session->read('userdata.arrayCC');

			$conds = array(
				'AND' => array(
					'CentroCosto.ceco_id != ' => $ceco_id, 
					'CentroCosto.ceco_id' => $centros_costos
				),
				'OR' => array(
					'CentroCosto.ceco_nombre ilike' => '%'.$criterio.'%'					
				)
			);

			$centros_costos = $this->paginate('CentroCosto', $conds);
		} else {
			$criterio = "";
			$userdata = $this->Session->read('userdata');
			$ceco_id = $userdata['CentroCosto']['ceco_id'];
			$centros_costos = $this->Session->read('userdata.arrayCC');

			if (isset($this->params['named']['page'])) {				
				$criterio = $this->Session->read('userdata.criterio_ceco');

				$conds = array(
					'AND' => array(
						'CentroCosto.ceco_id != ' => $ceco_id, 
						'CentroCosto.ceco_id' => $centros_costos
					),
					'OR' => array(
						'CentroCosto.ceco_nombre ilike' => '%'.$criterio.'%'					
					)
				);
			} else {
				// Eliminamos session cuando se presiona paginate sin filtros (index)
				$this->Session->delete('userdata.criterio_ceco');
				$conds = array(
					'AND' => array(
						'CentroCosto.ceco_id != ' => $ceco_id, 
						'CentroCosto.ceco_id' => $centros_costos
					)
				);
			}

			$centros_costos = $this->paginate('CentroCosto', $conds);
		}

		$this->set('centros_costos', $centros_costos);
		$this->set('criterio', $criterio);
	}
	
	function add() {
		if (!empty($this->data)) {
			$this->CentroCosto->set($this->data);
			
			if ($this->CentroCosto->validates()) {
				if ($this->CentroCosto->save($this->data)) {
					$ceco_id = $this->CentroCosto->id;
					$ubicacion = $this->CentroCosto->findUbicacion($ceco_id);
					$this->Log->write($this->Session->read('userdata.Usuario.usua_id'), "Nuevo Centro de Costo", $_REQUEST);
					$this->Session->setFlash(__('El Centro de Costo ha sido guardado. Ubicaci�n: '.$ubicacion, true));
				} else {
					$this->Session->setFlash(__(utf8_encode('No se pudo guardar el Centro de Costo, por favor int�ntelo nuevamente'), true));
				}
			}
		}
		
		$comunas = $this->CentroCosto->Comuna->find('list', array('fields' => array('comu_id', 'comu_nombre'), 'order' => 'comu_nombre ASC'));
		$this->set('comunas', $comunas);
		$centros_costos = $this->Session->read('userdata.selectCC');
		$this->set('centros_costos', $centros_costos);
		$tipos_localizaciones = $this->CentroCosto->TipoLocalizacion->find('list', array('fields' => 'tilo_nombre'));
		$this->set('tipos_localizaciones', $tipos_localizaciones);
		$nivelesEducativos = $this->CentroCosto->NivelEducativo->find('list', array('fields' => 'nied_nombre'));
		$this->set('nivelesEducativos', $nivelesEducativos);
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
					$ubicacion = $this->CentroCosto->findUbicacion($ceco_id);
					$this->Log->write($this->Session->read('userdata.Usuario.usua_id'), utf8_encode("Modificaci�n Centro de Costo"), $_REQUEST);
					$this->Session->setFlash(__('El Centro de Costo ha sido editado.  Ubicaci�n: '.$ubicacion, true));
					$this->redirect(array('action' => 'index'));
				} else {
					$this->Session->setFlash(__(utf8_encode('No se pudo guardar el Centro de Costo, por favor int�ntelo nuevamente'), true));
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
		$nivelesEducativos = $this->CentroCosto->NivelEducativo->find('list', array('fields' => 'nied_nombre'));
		$this->set('nivelesEducativos', $nivelesEducativos);
	}
	
	function delete($id = null) {
		if (!$id) {
			$this->Session->setFlash(__('ID invalido', true));
			$this->redirect(array('action'=>'index'));
		}
		if ($this->CentroCosto->delete($id)) {
			$this->Log->write($this->Session->read('userdata.Usuario.usua_id'), utf8_encode("Eliminaci�n Centro de Costo"), $_REQUEST);
			$this->Session->setFlash(__('El Centro de Costo ha sido eliminado', true));
			$this->redirect(array('action'=>'index'));
		}
		$this->Session->setFlash(__('El Centro de Costo no se pudo eliminar', true));
		$this->redirect(array('action' => 'index'));
	}
}

?>
