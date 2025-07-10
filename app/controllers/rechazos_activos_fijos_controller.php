<?php
class RechazosActivosFijosController extends AppController {
	var $name = 'RechazosActivosFijos';
	var $uses = array('RechazoActivoFijo');
	var $paginate = array(
		'RechazoActivoFijo' => array(
			'order' => array('RechazoActivoFijo.reaf_fecha' => 'desc')
		)
	);
	
	function index() {
		$this->RechazoActivoFijo->recursive = 2;
		$rechazos = $this->paginate();
		$this->set('rechazos', $rechazos);
	}
	
	function edit($reaf_id = null) {
		if (!$reaf_id && empty($this->data)) {
			$this->Session->setFlash(__('ID invalido', true));
			$this->redirect(array('action' => 'index'));
		}
		
		if (!empty($this->data)) {
			$this->RechazoActivoFijo->create();
			$this->RechazoActivoFijo->set($this->data);	
			
			if ($this->RechazoActivoFijo->validates()) {
				if ($this->RechazoActivoFijo->save($this->data)) {
					$this->Log->write($this->Session->read('userdata.Usuario.usua_id'), utf8_encode('Modificación Rechazo de Activo Fijo'), $_REQUEST);
					$this->Session->setFlash(__('El rechazo ha sido guardado', true));
					$this->redirect(array('action' => 'index'));
				} else {
					$this->Session->setFlash(__(utf8_encode('No se pudo guardar el rechazo. Por favor, inténtelo nuevamente.'), true));
				}
			}
		}
		
		if (empty($this->data)) {
			$this->data = $this->RechazoActivoFijo->read(null, $reaf_id);
		}
	}
	
	function delete($reaf_id = null) {
		if (!$reaf_id) {
			$this->Session->setFlash(__('ID invalido', true));
			$this->redirect(array('action'=>'index'));
		}
		if ($this->RechazoActivoFijo->delete($reaf_id)) {
			$this->Log->write($this->Session->read('userdata.Usuario.usua_id'), utf8_encode('Eliminación Rechazo de Activo Fijo'), $_REQUEST);
			$this->Session->setFlash(__('Rechazo eliminado', true));
			$this->redirect(array('action'=>'index'));
		}
		$this->Session->setFlash(__('No se pudo eliminar el rechazo', true));
		$this->redirect(array('action' => 'index'));		
	}
}

?>