<?php
class LogsController extends AppController {
	var $name = 'Logs';
	var $paginate = array(
		'Log' => array('order' => array('Log.logs_fecha' => 'desc'))
	);

	function index() {
		$this->Log->recursive = 0;
		
		if (isset($usua_id)){
			$info_usua = $this->Log->Usuario->find('first', array('conditions' => array('Usuario.usua_id' => $usua_id)));
			$usua_nombre = $info_usua['Usuario']['usua_nombre'];
			$this->set('usua_nombre', $usua_nombre);
			$this->set('usua_id', $usua_id);
			$logs = $this->paginate('Log', array('AND' => array('Log.usua_id' => $usua_id)));
		} else {
			$logs = $this->paginate();
		}
		$this->set('logs', $logs);
	}
}
?>