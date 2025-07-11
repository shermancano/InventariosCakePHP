<?php
class LogsController extends AppController {
	var $name = 'Logs';
	var $paginate = array(
		'Log' => array('order' => array('Log.logs_fecha' => 'desc'))
	);

	function index() {
		$this->Log->recursive = 0;

		if (!empty($this->data)) {
			$this->params['named']['page'] = 1;
			$criterio = trim($this->data['Logs']['busqueda']);
			$opcion = $this->data['Logs']['opcion'];
			$accion = $this->data['Logs']['busqueda_accion'];
			$this->Session->write('userdata.criterio_log', $criterio);
			$this->Session->write('userdata.opcion_log', $opcion);
			$this->Session->write('userdata.accion_log', $accion);

			if ($opcion == 1) {
				$conds = array(
					'AND' => array(
						'Log.logs_parametros ilike' => '%'.$criterio.'%'					
					)
				);
			} else {
				$conds = array(
					'AND' => array(
						'Log.logs_accion' => $accion					
					)
				);
			}
			
			$logs = $this->paginate('Log', $conds);
		} else {
			$criterio = '';
			$opcion = '';
			$accion = '';
			$conds = null;

			if (isset($this->params['named']['page'])) {				
				$criterio = $this->Session->read('userdata.criterio_log');
				$opcion = $this->Session->read('userdata.opcion_log');
				$accion = $this->Session->read('userdata.accion_log');

				if (isset($opcion)) {
					if ($opcion == 1) {
						$conds = array(
							'AND' => array(
								'Log.logs_parametros ilike' => '%'.$criterio.'%'					
							)
						);
					} else {
						$conds = array(
							'AND' => array(
								'Log.logs_accion' => $accion					
							)
						);
					}
				}				
			} else {
				// Eliminamos session cuando se presiona paginate sin filtros (index)
				$this->Session->delete('userdata.criterio_log');
				$this->Session->delete('userdata.opcion_log');
				$this->Session->delete('userdata.accion_log');
			}

			if (isset($usua_id)){
				$info_usua = $this->Log->Usuario->find('first', array('conditions' => array('Usuario.usua_id' => $usua_id)));
				$usua_nombre = $info_usua['Usuario']['usua_nombre'];
				$this->set('usua_nombre', $usua_nombre);
				$this->set('usua_id', $usua_id);
				$logs = $this->paginate('Log', array('AND' => array('Log.usua_id' => $usua_id)), $conds);
			} else {
				$logs = $this->paginate('Log', $conds);
			}
		}
		$acciones = $this->Log->getAcciones();
		$this->set('logs', $logs);
		$this->set('acciones', $acciones);
		$this->set('criterio', $criterio);
		$this->set('opcion', $opcion);
		$this->set('accion', $accion);
	}
}
?>
