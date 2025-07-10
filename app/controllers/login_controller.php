<?php
class LoginController extends AppController {
	var $name = 'Login';
	var $uses = array('Usuario', 'Login', 'Responsable');
	
	function index() {
		if (!empty($this->data)) {
			$this->Login->create();
			$this->Login->set($this->data);
			
			if ($this->Login->validates()) {
				$username = $this->data['Login']['usua_username'];
				$password = "d41d8cd98f00b204e9800998ecf8427e"; //md5($this->data['Login']['usua_password']);
				$info = $this->Usuario->find('first', array('conditions' => array('usua_username' => $username, 'usua_password' => $password)));
				
				if (is_array($info)) {
					//verificamos que se encuentre el usuario habilitado
					if ($info['Usuario']['esre_id'] == 2) {
						$this->Session->setFlash(__(utf8_encode('Su usuario se encuentra deshabilitado. Por favor cont�ctese con el administrador del sistema.'), true));
					} else {
						//verificamos si pertenece a un Centro de Costo
						$usua_id = $info['Usuario']['usua_id'] ;
						$resp = $this->Responsable->find('first', array('fields' => array('CentroCosto.*'), 'conditions' => array('Responsable.usua_id' => $usua_id, 'Responsable.esre_id' => 1)));
						
						if (sizeof($resp) == 0) {
							$this->Session->setFlash(__(utf8_encode('No tiene ning�n Centro de Costo asociado a su cuenta. Por favor cont�ctese con el administrador del sistema.'), true));
						
						} else {
							$this->Session->write('userdata', $info);
							
							// sacamos los permisos
							$perf_id = $info['Perfil']['perf_id'];
							$permisos = $this->Usuario->Perfil->Permiso->obtieneTodoPorPerfil($perf_id);
							$this->Session->write('userdata.permisos', $permisos);
							
							// sacamos los permisos globales (para el menu)
							$globales = $this->Usuario->Perfil->Permiso->obtieneTodoGlobal($perf_id);
							$this->Session->write('userdata.menu', $globales);
							
							// actualizamos ultima visita
							$usua_id = $info['Usuario']['usua_id'];
							$ult_visita = $this->Usuario->actUltimaVisita($usua_id);
							$this->Session->write('userdata.Usuario.usua_ultimo_acceso', $ult_visita);
							$this->Log->write($this->Session->read('userdata.Usuario.usua_id'), utf8_encode('Inicio de Sesi�n'), $_REQUEST);
							$this->redirect(array('action' => 'selCentroCosto', 'controller' => 'usuarios'));
						}
					}
				} else {
					$this->Session->setFlash(__(utf8_encode('Nombre de usuario y/o contrase�a incorrecta.'), true));
				}
			}
		}
	}
	
	function logout() {
		$this->Log->write($this->Session->read('userdata.Usuario.usua_id'), utf8_encode('Cierre de Sesi�n'), $_REQUEST);
		$this->Session->destroy();
		$this->redirect(array('action' => 'index'));
	}
}

?>
