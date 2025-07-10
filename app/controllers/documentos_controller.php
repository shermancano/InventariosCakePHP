<?php
class DocumentosController extends AppController {
	var $name = 'Documentos';

	function index() {
		$this->Documento->recursive = 0;
		$this->set('documentos', $this->paginate());
	}

	function view($id = null) {
		$this->layout = "ajax";
		if (!$id) {
			$this->Session->setFlash(__('Id invalido', true));
			$this->redirect(array('action' => 'index'));
		}
		
		$info = $this->Documento->read(null, $id);
   		$info['Documento']['docu_nombre'] = str_replace(" ", "_", $info['Documento']['docu_nombre']);
   		
   		header('Content-Description: File Transfer');
    	header('Content-Type: application/octet-stream');
   		header('Content-Disposition: attachment; filename='.$info['Documento']['docu_nombre']);
   		header('Content-Transfer-Encoding: binary');
   		header('Expires: 0');
   		header('Cache-Control: must-revalidate, post-check=0, pre-check=0');
   		header('Pragma: public');
   		header('Content-Length: ' . filesize($info['Documento']['docu_path']));
   		readfile($info['Documento']['docu_path']);
   		exit;
	}

	function add() {
		if (!empty($this->data)) {
			$this->Documento->create();
			$cont_id = $this->data['Documento']['cont_id'];
			$data = array();
			$data['Documento'] = $this->data['Documento']['docs'];
			$this->Documento->set($this->data);
			
			if ($this->Documento->validates()) {
				if ($this->Documento->saveDocumento($cont_id, $data)) {
					$this->Log->write($this->Session->read('userdata.Usuario.usua_id'), 'Nuevo Documento', $_REQUEST);
					$this->Session->setFlash(__('El documento ha sido guardado', true));
					$this->redirect(array('action' => 'index'));
				} else {
					$this->Session->setFlash(__(utf8_encode('No se pudo guardar el documento. Por favor, inténtelo nuevamente'), true));
				}
			} else {
				$errors = $this->Documento->invalidFields();
				$this->set('file_error', $errors['docs']);
			}
		}
		
		$this->Documento->Contrato->recursive = -1;
		$info_cont = $this->Documento->Contrato->find('all', array('order' => 'Contrato.cont_nombre'));
		$arr_cont = array();
		
		foreach ($info_cont as $contrato) {
			$arr_cont[$contrato['Contrato']['cont_id']] = $contrato['Contrato']['cont_nombre'];
		}
		$this->set("contratos", $arr_cont);
	}

	function delete($id = null) {
		if (!$id) {
			$this->Session->setFlash(__('ID invalido', true));
			$this->redirect(array('action'=>'index'));
		}
		if ($this->Documento->delete($id)) {
			$this->Log->write($this->Session->read('userdata.Usuario.usua_id'), utf8_encode('Eliminación Documento'), $_REQUEST);
			$this->Session->setFlash(__('Documento eliminado', true));
			$this->redirect(array('action'=>'index'));
		}
		$this->Session->setFlash(__('El documento no se pudo eliminar', true));
		$this->redirect(array('action' => 'index'));
	}
}
?>