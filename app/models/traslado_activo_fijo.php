<?php
class TrasladoActivoFijo extends AppModel {
	var $name = 'TrasladoActivoFijo';
	var $useTable = 'activos_fijos';
	var $primaryKey = 'acfi_id';

	var $hasMany = array(
	    'DetalleActivoFijo' => array('className' => 'DetalleActivoFijo',
									 'foreignKey' => 'acfi_id')
	);
	
	var $validate = array(
		 'ceco_nombre' => array('rule' => '/[\w\_]+/', 'required' => true, 'message' => 'Debe ingresar el origen')
		,'ceco_id_hijo' => array('rule' => array('checkCC'), 'message' => 'El destino debe ser distinto al origen')
	);
	
	function checkCC() {
		if (isset($this->data['TrasladoActivoFijo']['ceco_id']) && isset($this->data['TrasladoActivoFijo']['ceco_id_hijo'])) {
			$ceco_id = $this->data['TrasladoActivoFijo']['ceco_id'];
			$ceco_id_hijo = $this->data['TrasladoActivoFijo']['ceco_id_hijo'];
			
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
