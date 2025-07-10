<?php
class TipoCambiosController extends AppController {

	var $name = 'TipoCambios';

	function index() {
		$this->TipoCambio->recursive = 0;
		$this->set('tipoCambios', $this->paginate());
	}

	function add() {
		if (!empty($this->data)) {
			$this->TipoCambio->create();
			$this->TipoCambio->set($this->data);
			
			if ($this->TipoCambio->validates()) {
				if ($this->TipoCambio->save($this->data)) {
					$this->Log->write($this->Session->read('userdata.Usuario.usua_id'), 'Nuevo Tipo de Cambio', $_REQUEST);
					$this->Session->setFlash(__('El tipo cambio ha sido guardado', true));
					$this->redirect(array('action' => 'index'));
				} else {
					$this->Session->setFlash(__(utf8_encode('El tipo de cambio no pudo ser guardado. Por favor inténtelo nuevamente.'), true));
				}
			}
		}
		
		$tipos_monto = $this->TipoCambio->TipoMonto->find('all', array('order' => 'timo_descripcion asc'));
		$arr_montos = array();
		
		foreach ($tipos_monto as $tipo_monto) {
			$arr_montos[$tipo_monto['TipoMonto']['timo_id']] = $tipo_monto['TipoMonto']['timo_descripcion'];
		}
		$this->set('arr_montos', $arr_montos);
	}

	function edit($id = null) {
		if (!$id && empty($this->data)) {
			$this->Session->setFlash(__('ID invalido', true));
			$this->redirect(array('action' => 'index'));
		}
		if (!empty($this->data)) {
			$this->TipoCambio->set($this->data);
			
			if ($this->TipoCambio->validates()) {
				if ($this->TipoCambio->save($this->data)) {
					$this->Log->write($this->Session->read('userdata.Usuario.usua_id'), utf8_encode('Modificación Tipo de Cambio'), $_REQUEST);
					$this->Session->setFlash(__('El tipo cambio ha sido guardado', true));
					$this->redirect(array('action' => 'index'));
				} else {
					$this->Session->setFlash(__(utf8_encode('El tipo de cambio no pudo ser guardado. Por favor inténtelo nuevamente.'), true));
				}
			}
		}
		if (empty($this->data)) {
			$this->data = $this->TipoCambio->read(null, $id);
		}
		
		$tipos_monto = $this->TipoCambio->TipoMonto->find('all', array('order' => 'timo_descripcion asc'));
		$arr_montos = array();
		
		foreach ($tipos_monto as $tipo_monto) {
			$arr_montos[$tipo_monto['TipoMonto']['timo_id']] = $tipo_monto['TipoMonto']['timo_descripcion'];
		}
		$this->set('arr_montos', $arr_montos);
	}

	function delete($id = null) {
		if (!$id) {
			$this->Session->setFlash(__('ID invalido', true));
			$this->redirect(array('action'=>'index'));
		}
		if ($this->TipoCambio->delete($id)) {
			$this->Log->write($this->Session->read('userdata.Usuario.usua_id'), utf8_encode('Eliminación Tipo de Cambio'), $_REQUEST);
			$this->Session->setFlash(__('Tipo de cambio eliminado', true));
			$this->redirect(array('action'=>'index'));
		}
		$this->Session->setFlash(__('El tipo cambio no se pudo eliminar', true));
		$this->redirect(array('action' => 'index'));
	}
}
?>