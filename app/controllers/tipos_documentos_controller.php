<?php
class TiposDocumentosController extends AppController {
	var $name = 'TiposDocumentos';
	var $uses = array('TipoDocumento');
	
	function index() {
		$this->TipoDocumento->recursive = 0;
		$documentos = $this->paginate();
		$this->set('documentos', $documentos);
	}
	
	function add() {
		if (!empty($this->data)) {
			$this->TipoDocumento->set($this->data);
			
			if ($this->TipoDocumento->validates()) {
				if ($this->TipoDocumento->save($this->data)) {
					$this->Log->write($this->Session->read('userdata.Usuario.usua_id'), 'Nuevo Tipo de Documento', $_REQUEST);
					$this->Session->setFlash(__('El tipo de documento ha sido guardado', true));
					$this->redirect(array('action' => 'index'));
				} else {
					$this->Session->setFlash(__(utf8_encode('No se pudo guardar el tipo de documento, por favor inténtelo nuevamente'), true));
				}
			}
		}
	}
	
	function edit($tido_id = null) {
		if (!$tido_id && empty($this->data)) {
			$this->Session->setFlash(__('ID invalido', true));
			$this->redirect(array('action' => 'index'));
		}
	
		if (!empty($this->data)) {
			$this->TipoDocumento->set($this->data);
			
			if ($this->TipoDocumento->validates()) {
				if ($this->TipoDocumento->save($this->data)) {
					$this->Log->write($this->Session->read('userdata.Usuario.usua_id'), utf8_encode('Modificación Tipo de Documento'), $_REQUEST);
					$this->Session->setFlash(__('El tipo de documento ha sido guardado', true));
					$this->redirect(array('action' => 'index'));
				} else {
					$this->Session->setFlash(__(utf8_encode('No se pudo guardar el tipo de documento, por favor inténtelo nuevamente'), true));
				}
			}
		}
		
		if (empty($this->data)) {
			$this->data = $this->TipoDocumento->read(null, $tido_id);
		}
	}
}
?>