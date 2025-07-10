<?php
class RechazosSolicitudesController extends AppController {
	var $name = 'RechazosSolicitudes';
	var $uses = array('RechazoSolicitud');

	function index() {
		$this->RechazoSolicitud->recursive = 2;
		$ceco_id = $this->Session->read('userdata.CentroCosto.ceco_id');
		
		$this->paginate = array(
			'RechazoSolicitud' => array(
				'order' => array('RechazoSolicitud.reso_fecha' => 'desc'),
				'conditions' => array('Solicitud.ceco_id' => $ceco_id)
			)
		);
		
		$rechazos = $this->paginate();
		$this->set('rechazos', $rechazos);
	}
	
	function edit($reso_id = null) {
		if (!$reso_id && empty($this->data)) {
			$this->Session->setFlash(__('ID invalido', true));
			$this->redirect(array('action' => 'index'));
		}
		
		if (!empty($this->data)) {
			$this->RechazoSolicitud->create();
			$this->RechazoSolicitud->set($this->data);	
			
			if ($this->RechazoSolicitud->validates()) {
				if ($this->RechazoSolicitud->save($this->data)) {
					$this->Log->write($this->Session->read('userdata.Usuario.usua_id'), 'Modificación Rechazo de Activo Fijo', $_REQUEST);
					$this->Session->setFlash(__('El rechazo ha sido guardado', true));
					$this->redirect(array('action' => 'index'));
				} else {
					$this->Session->setFlash(__('No se pudo guardar el rechazo. Por favor, inténtelo nuevamente.', true));
				}
			}
		}
		
		if (empty($this->data)) {
			$this->data = $this->RechazoSolicitud->read(null, $reso_id);
		}
	}
	
	function delete($reso_id = null) {
		if (!$reso_id) {
			$this->Session->setFlash(__('ID invalido', true));
			$this->redirect(array('action'=>'index'));
		}
		if ($this->RechazoSolicitud->delete($reso_id)) {
			$this->Log->write($this->Session->read('userdata.Usuario.usua_id'), 'Eliminación Rechazo de Solicitud', $_REQUEST);
			$this->Session->setFlash(__('Rechazo eliminado', true));
			$this->redirect(array('action'=>'index'));
		}
		$this->Session->setFlash(__('No se pudo eliminar el rechazo', true));
		$this->redirect(array('action' => 'index'));		
	}
}

?>