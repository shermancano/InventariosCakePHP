<?php
class PermisosController extends AppController {
	var $name = 'Permisos';
	var $uses = array('Permiso');
	
	function index() {
		if (!empty($this->data)) {
			if ($this->Permiso->guardaTodo($this->data)) {
				$this->Log->write($this->Session->read('userdata.Usuario.usua_id'), utf8_encode('Modificación de Permisos'), $_REQUEST);
				$this->Session->setFlash(__(utf8_encode('Permisos guardados exitosamente.'), true));
				$this->redirect(array('action' => 'index'));
			} else {
				$this->Session->setFlash(__(utf8_encode('No se pudieron guardar los permisos. Por favor, inténtelo nuevamente'), true));
				$this->redirect(array('action' => 'index'));
			}
		}
	
		$permisos = $this->Permiso->obtieneTodo($this->data);
		$contPath = Configure::read('controllerPath');
		$info = array();
		// metodos de cake
		$no_methods = array('beforeFilter', 'ccArrayToCcVector', 'ccArrayToHTML', 'beforeRender'
						   ,'sendMail', 'constructClasses', 'startupProcess', 'shutdownProcess', 'loadModel'
						   ,'httpCodes', 'redirect', 'header', 'set', 'setAction', 'limpiaVars', 'validate'
						   ,'validateErrors', 'isAuthorized', 'render', 'referer', 'disableCache', 'flash'
						   ,'disableCache', 'postConditions', 'paginate', 'afterFilter', 'Object', 'toString'
						   ,'requestAction', 'dispatchMethod', 'cakeError', 'log');
		
		$i = 0;
		foreach (glob($contPath."/*.php") as $controller) {
			include_once($controller);
			
			$className = basename($controller);
		
			// sacamos el .php
			list($className, $ext) = preg_split("/\./", $className);
			$controller = $className;
			$className = Inflector::classify($className);
			
			// sacamos el controlador de login (debe ser visto por todos)
			if ($className == "LoginController") continue;
			
			$obj = new $className;
			$methods = get_class_methods($obj);
			$methods_ = array();
			
			// sacamos metodos que no sirven
			foreach ($methods as $method) {
				if (!preg_match("/^(\_|\__)/", $method) && !preg_match("/(main|genera|pdf|excel|html|get|find|search|ajax|demo)/", $method)) {
					if (!in_array($method, $no_methods)) {
						$methods_[] = $method;
					}
				}
			}
			
			if (sizeof($methods_) > 0) {
				$className = substr($className, 0, -10);
				$info[$i]['className'] = $className;
				$info[$i]['path'] = "/".substr($controller, 0, -11);
				$info[$i]['methods'] = $methods_;
				$i++;
			}
		}
		
		$perfiles = $this->Permiso->Perfil->find('all', array('order' => array('Perfil.perf_nombre asc')));
		$this->set("perfiles", $perfiles);
		$this->set("info", $info);
		$this->set("permisos", $permisos);
	}
}

?>