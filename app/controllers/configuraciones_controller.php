<?php
class ConfiguracionesController extends AppController {
	var $name = 'Configuraciones';
	var $uses = array('Configuracion');
	
	function index() {
		if (!empty($this->data)) {
			$this->Configuracion->set($this->data);
			
			if ($this->Configuracion->validates()) {
				if ($this->Configuracion->save($this->data)) {
					$this->Log->write($this->Session->read('userdata.Usuario.usua_id'), utf8_encode('Modificacion de Configuraciones'), $_REQUEST);
					$this->Session->setFlash(__('La configuración ha sido guardada', true));
					$this->redirect(array('action' => 'index'));
				} else {
					$this->Session->setFlash(__('No se pudo guardar la configuración. Por favor inténtelo nuevamente', true));
				}
			} else {
				$this->Session->setFlash(__(('Existen errores en algunos campos, por favor corríjalos'), true));
			}
		}
		
		if (empty($this->data)) {
			$info = $this->Configuracion->find('all');
			$conf = array();
			
			foreach ($info as $row) {
				$conf[$row['Configuracion']['conf_id']] = $row['Configuracion']['conf_valor'];
			}
			
			$this->set('conf', $conf);
		}
		
		$barcode_types = array("code39" => "CODE39", "code128" => "CODE128", "int25" => "INT25", "postnet" => "POSTNET", "upca" => "UPCA");
		$this->set('barcode_types', $barcode_types);
		$booleans = array(0 => "No", 1 => "Si");
		$this->set('booleans', $booleans);
	}
}
?>