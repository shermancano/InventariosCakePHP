<?php
class Depreciacion extends AppModel {
	var $name = 'Depreciacion';
	var $useTable = 'depreciaciones';
	var $primaryKey = 'depr_id';
	
	var $hasMany = array(
		'DetalleDepreciacion' => array(
			'className' => 'DetalleDepreciacion',
			'foreignKey' => 'depr_id'
		)
	);
	
	var $validate = array(
		'depr_ipc' => array(
			'rule' => 'numeric', 
			'required' => true, 
			'message' => 'Debe ingresar valor IPC'
		),
		'depr_ano' => array(
			'checkYear' => array(
				'rule' => array('checkYear'), 
				'required' => true, 
				'message' => 'Debe ingresar el periodo de depreciación'
			),
			'checkUnique' => array(
				'rule' => array('checkUnique'), 
				'required' => true, 
				'message' => 'El año ingresado ya se encuentra depreciado.'
			)
		)
	);
	
	function checkYear() {
		$year = $this->data['Depreciacion']['depr_ano']['year'];
		
		if (!is_numeric($year)) {
			return false;	
		} else {
			return true;	
		}
	}
	
	function checkUnique() {
		$year = $this->data['Depreciacion']['depr_ano']['year'];
		$countDepreciacion = $this->find('count', array('conditions' => array('Depreciacion.depr_ano' => $year)));
		
		if ($countDepreciacion > 0) {
			return false;
		} else {
			return true;
		}
	}
	
	function calculaDepreciacion($ipc, $ano) {
		$sql = "select calcula_depreciacion(".$ipc.", ".$ano.")";
		$res = $this->query($sql);
		
		if (is_array($res)) {
			return true;
		} else {
			return false;
		}
	}
	
	function eliminaDepreciacion($depr_id) {
		$sql = "select elimina_depreciacion(".$depr_id.")";
		$res = $this->query($sql);
		
		if (is_array($res)) {
			return true;
		} else {
			return false;
		}
	}
}
?>