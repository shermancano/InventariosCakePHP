<?php
class EncargadoInventariosController extends AppController {
	var $name = 'EncargadoInventarios';
	
	function index() {
		$this->EncargadoInventario->recursive = 0;
		$userdata = $this->Session->read('userdata');
		$ceco_id = $userdata['CentroCosto']['ceco_id'];
		$responsables = $this->paginate(
            'EncargadoInventario', array(
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
			$this->EncargadoInventario->set($this->data);
			
			if ($this->EncargadoInventario->validates()) {
				if ($this->EncargadoInventario->save($this->data)) {
					$this->Log->write($this->Session->read('userdata.Usuario.usua_id'), "Nuevo Encargado Inventario", $_REQUEST);
					$this->Session->setFlash(__('El encargado de inventario ha sido guardado', true));
					$this->redirect(array('action' => 'index'));
				} else {
					$this->Session->setFlash(__(utf8_encode('No se pudo guardar el encargado de inventario, por favor int�ntelo nuevamente'), true));
				}
			}
		}
		
		$estados = $this->EncargadoInventario->EstadoRegistro->find('list', array('fields' => array('esre_id', 'esre_nombre')));
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
			$this->EncargadoInventario->set($this->data);
			
			if ($this->EncargadoInventario->validates()) {
				if ($this->EncargadoInventario->save($this->data)) {
					$this->Log->write($this->Session->read('userdata.Usuario.usua_id'), utf8_encode("Modificación Encargado Inventario"), $_REQUEST);
					$this->Session->setFlash(__('El encargado de inventario ha sido guardado', true));
					$this->redirect(array('action' => 'index'));
				} else {
					$this->Session->setFlash(__(utf8_encode('No se pudo guardar el encargado de inventario, por favor int�ntelo nuevamente'), true));
				}
			}
		}
		
		if (empty($this->data)) {
			$this->data = $this->EncargadoInventario->read(null, $resp_id);
		}
		
		$estados = $this->EncargadoInventario->EstadoRegistro->find('list', array('fields' => array('esre_id', 'esre_nombre')));
		$this->set('estados', $estados);
		$centros_costos = $this->Session->read('userdata.selectCC');
		$this->set('centros_costos', $centros_costos);
	}
	
	function delete($id = null) {
		if (!$id) {
			$this->Session->setFlash(__('ID invalido', true));
			$this->redirect(array('action'=>'index'));
		}
		if ($this->EncargadoInventario->delete($id)) {
			$this->Log->write($this->Session->read('userdata.Usuario.usua_id'), utf8_encode("Eliminación Encargado Inventario"), $_REQUEST);
			$this->Session->setFlash(__('El encargado de inventario ha sido eliminado', true));
			$this->redirect(array('action'=>'index'));
		}
		$this->Session->setFlash(__('El encargado de inventario no se pudo eliminar', true));
		$this->redirect(array('action' => 'index'));
	}	
}
?>
