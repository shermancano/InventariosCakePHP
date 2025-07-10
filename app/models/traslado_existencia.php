<?php
class TrasladoExistencia extends AppModel {
	var $name = 'TrasladoExistencia';
	var $useTable = 'existencias';
	var $primaryKey = 'exis_id';

	var $hasMany = array(
	    'DetalleExistencia' => array('className' => 'DetalleExistencia',
									 'foreignKey' => 'exis_id')
	);
	
	var $validate = array(
		 'ceco_nombre' => array('rule' => '/[\w\_]+/', 'required' => true, 'message' => 'Debe ingresar el origen')
		,'ceco_id_hijo' => array('rule' => array('checkCC'), 'message' => 'El destino debe ser distinto al origen')
	);
	
	function checkCC() {
		if (isset($this->data['TrasladoExistencia']['ceco_id']) && isset($this->data['TrasladoExistencia']['ceco_id_hijo'])) {
			$ceco_id = $this->data['TrasladoExistencia']['ceco_id'];
			$ceco_id_hijo = $this->data['TrasladoExistencia']['ceco_id_hijo'];
			
			if ($ceco_id == $ceco_id_hijo) {
				return false;
			} else {
				return true;
			}
		}
		return true;
	}
}
?>
