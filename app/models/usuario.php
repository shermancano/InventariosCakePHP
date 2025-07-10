<?php
class Usuario extends AppModel {
	var $name = 'Usuario';
	var $primaryKey = 'usua_id';
	
	var $belongsTo = array(
		'Perfil' => array(
			'className' => 'Perfil',
			'foreignKey' => 'perf_id',
			'conditions' => '',
			'fields' => '',
			'order' => ''
		),
		'EstadoRegistro' => array(
			'className' => 'EstadoRegistro',
			'foreignKey' => 'esre_id',
			'conditions' => '',
			'fields' => '',
			'order' => ''
		)
	);
	
	var $validate = array(
	 	'usua_nombre' => array('rule' => '/[\w\s]+/', 'required' => true, 'message' => 'Debe ingresar un  nombre')
	   ,'usua_username' => array('rule' => 'isUnique', 'required' => true, 'on' => 'create', 'message' => 'El usuario ya existe/Debe ingresar el usuario')
	   ,'usua_email' => array('rule' => 'email', 'required' => true, 'message' => 'Debe ingresar un correo electronico valido')
	   ,'usua_password' => array('rule' => '/[\w\s\-\_]+/', 'required' => true, 'on' => 'create', 'message' => 'Debe ingresar una clave')
	   ,'usua_password2' => array('rule' => array('checkPasswords'), 'required' => true, 'on' => 'create', 'message' => 'Debe reiterar la clave')
	);
	
	function checkPasswords() {
		$pass1 = $this->data['Usuario']['usua_password'];
		$pass2 = $this->data['Usuario']['usua_password2'];
		
		if ($pass2 == "" || $pass2 == null) {
			$this->validationErrors['usua_password2'] = "Debe reiterar la clave";
			return false;
		}
		
		if ($pass1 != $pass2) {
			$this->validationErrors['usua_password'] = "Las claves deben coincidir";
			return false;
		} else {
			return true;	
		}
	}
	
	function searchUsuario($string) {
		$string = trim($string);
		
		$sql = "select usua_id, usua_nombre from usuarios where lower(usua_nombre) like '%".strtolower($string)."%'";
		$res = $this->query($sql);
		return $res;
	}
	
	function actUltimaVisita($usua_id) {
		$ult_visita = date("Y-m-d H:i:s");
		$sql = "update usuarios
				set usua_ultimo_acceso = '".$ult_visita."'
				where usua_id = ".$usua_id;
		$res = $this->query($sql);
		
		return $ult_visita;
	}
}
?>