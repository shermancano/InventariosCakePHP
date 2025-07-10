<?php
class Log extends AppModel {
	var $name = 'Log';
	var $primaryKey = 'logs_id';
	var $useTable = 'logs';

	var $belongsTo = array(
		'Usuario' => array(
			'className' => 'Usuario',
			'foreignKey' => 'usua_id'
		)
	);
	
	function write($usua_id, $action, $data) {
		if (function_exists("apache_request_headers")) {
	
			$headers = apache_request_headers();
			if (array_key_exists('X-Forwarded-For', $headers)){
			  $ip = $headers['X-Forwarded-For'];
			} else {
			  $ip = $_SERVER["REMOTE_ADDR"];
			}
		} else {
			$ip = $_SERVER["REMOTE_ADDR"];
		}
	
		$this->data['Log'] = array('usua_id'         => $usua_id
								  ,'logs_accion'     => $action
								  ,'logs_parametros' => var_export($data, true)
								  ,'logs_fecha'      => date('Y-m-d H:i:s')
								  ,'logs_ip'		 => $ip);
		
		if (!$this->save($this->data)) {
			throw new Exception("No se puede guardar en el log");
		}
	}
}
?>
