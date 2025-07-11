<?php
class EncargadoDependenciasController extends AppController {
	var $name = 'EncargadoDependencias';
	
	function index() {
		$this->EncargadoDependencia->recursive = 0;
		$userdata = $this->Session->read('userdata');
		$ceco_id = $userdata['CentroCosto']['ceco_id'];
		$responsables = $this->paginate(
            'EncargadoDependencia', array(
                'OR' => array(
                    'CentroCosto.ceco_id_padre = '.$ceco_id, 
                    'CentroCosto.ceco_id = '.$ceco_id
                )
            )
        );
		$this->set('responsables', $responsables);
	}
	
	function add() {
		if (!empty($this->data)) {
			$this->EncargadoDependencia->set($this->data);
			
			if ($this->EncargadoDependencia->validates()) {
				if ($this->EncargadoDependencia->save($this->data)) {
					$this->Log->write($this->Session->read('userdata.Usuario.usua_id'), "Nuevo Encargado Dependencia", $_REQUEST);
					$this->Session->setFlash(__('El encargado de dependencia ha sido guardado', true));
					$this->redirect(array('action' => 'index'));
				} else {
					$this->Session->setFlash(__(utf8_encode('No se pudo guardar el encargado de dependencia, por favor intentelo nuevamente'), true));
				}
			}
		}
		
		$estados = $this->EncargadoDependencia->EstadoRegistro->find('list', array('fields' => array('esre_id', 'esre_nombre')));
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
			$this->EncargadoDependencia->set($this->data);
			
			if ($this->EncargadoDependencia->validates()) {
				if ($this->EncargadoDependencia->save($this->data)) {
					$this->Log->write($this->Session->read('userdata.Usuario.usua_id'), utf8_encode("Modificación Encargado Dependencia"), $_REQUEST);
					$this->Session->setFlash(__('El encargado de dependencia ha sido guardado', true));
					$this->redirect(array('action' => 'index'));
				} else {
					$this->Session->setFlash(__(utf8_encode('No se pudo guardar el encargado de dependencia, por favor intentelo nuevamente'), true));
				}
			}
		}
		
		if (empty($this->data)) {
			$this->data = $this->EncargadoDependencia->read(null, $resp_id);
		}
		
		$estados = $this->EncargadoDependencia->EstadoRegistro->find('list', array('fields' => array('esre_id', 'esre_nombre')));
		$this->set('estados', $estados);
		$centros_costos = $this->Session->read('userdata.selectCC');
		$this->set('centros_costos', $centros_costos);
	}
	
	function delete($id = null) {
		if (!$id) {
			$this->Session->setFlash(__('ID invalido', true));
			$this->redirect(array('action'=>'index'));
		}
		if ($this->EncargadoDependencia->delete($id)) {
			$this->Log->write($this->Session->read('userdata.Usuario.usua_id'), utf8_encode("Eliminación Encargado Dependencia"), $_REQUEST);
			$this->Session->setFlash(__('El encargado de dependencia ha sido eliminado', true));
			$this->redirect(array('action'=>'index'));
		}
		$this->Session->setFlash(__('El encargado de dependencia no se pudo eliminar', true));
		$this->redirect(array('action' => 'index'));
	}	
}
?>
