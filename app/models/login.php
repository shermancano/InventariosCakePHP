<?php
class Login extends AppModel {
	var $name = 'Login';
	var $useTable = false;
	
	var $validate = array(
		'usua_username' => array('rule' => '/[\w\s]+/', 'required' => true, 'message' => 'Debe ingresar su nombre de usuario')
	   ,'usua_password' => array('rule' => '/[\w\s]+/', 'required' => true, 'message' => 'Debe ingresar la clave asociada al usuario')
	);
}
?>
