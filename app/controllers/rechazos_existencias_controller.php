<?php
class RechazosExistenciasController extends AppController {
	var $name = 'RechazosExistencias';
	var $uses = array('RechazoExistencia');
	var $paginate = array(
		'RechazoExistencia' => array(
			'order' => array('RechazoExistencia.reex_fecha' => 'desc')
		)
	);
	
	function index() {
		$this->RechazoExistencia->recursive = 2;
		$rechazos = $this->paginate();
		$this->set('rechazos', $rechazos);
	}
	
	function edit($reex_id = null) {
		if (!$reex_id && empty($this->data)) {
			$this->Session->setFlash(__('ID invalido', true));
			$this->redirect(array('action' => 'index'));
		}
		
		if (!empty($this->data)) {
			$this->RechazoExistencia->create();
			$this->RechazoExistencia->set($this->data);	
			
			if ($this->RechazoExistencia->validates()) {
				if ($this->RechazoExistencia->save($this->data)) {
					$this->Log->write($this->Session->read('userdata.Usuario.usua_id'), utf8_encode('Modificación Rechazo de Existencia'), $_REQUEST);
					$this->Session->setFlash(__('El rechazo ha sido guardado', true));
					$this->redirect(array('action' => 'index'));
				} else {
					$this->Session->setFlash(__(utf8_encode('No se pudo guardar el rechazo. Por favor, inténtelo nuevamente.'), true));
				}
			}
		}
		
		if (empty($this->data)) {
			$this->data = $this->RechazoExistencia->read(null, $reex_id);
		}
	}
	
	function delete($reex_id = null) {
		if (!$reex_id) {
			$this->Session->setFlash(__('ID invalido', true));
			$this->redirect(array('action'=>'index'));
		}
		if ($this->RechazoExistencia->delete($reex_id)) {
			$this->Log->write($this->Session->read('userdata.Usuario.usua_id'), utf8_encode('Eliminación Rechazo de Existencia'), $_REQUEST);
			$this->Session->setFlash(__('Rechazo eliminado', true));
			$this->redirect(array('action'=>'index'));
		}
		$this->Session->setFlash(__('No se pudo eliminar el rechazo', true));
		$this->redirect(array('action' => 'index'));		
	}
}
?>