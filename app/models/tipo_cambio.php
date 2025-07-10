<?php
class TipoCambio extends AppModel {
	var $name = 'TipoCambio';
	var $useTable = 'tipo_cambio';
	var $primaryKey = 'tica_id';
	
	var $belongsTo = array(
	 	 'TipoMonto' => array('className' => 'TipoMonto',
	 	                      'foreignKey' => 'timo_id')
	);
	
	var $validate = array(
		'tica_monto' => array('rule' => 'numeric', 'required' => true, 'message' => 'Debe ingresar el monto')
       ,'timo_id' => array('rule' => array('checkUnique'), 'on' => 'create', 'message' => 'Ya existe un valor para este tipo de monto en esta fecha')
	);
	
	function checkUnique() {
		$timo_id = $this->data['TipoCambio']['timo_id'];
		$tica_fecha = $this->data['TipoCambio']['tica_fecha'];
		
		if ($timo_id != 1) {
			$sql = "select count(*) as cont from tipo_cambio where timo_id = $timo_id and tica_fecha = '$tica_fecha'";
		} else {
			$sql = "select count(*) as cont from tipo_cambio where timo_id = $timo_id and to_char(tica_fecha, 'YYYYMM') = '".date("Ym", strtotime($tica_fecha))."'";	
		}
		
		$rs = $this->query($sql);
		$cont = $rs[0][0]['cont'];
		
		if ($cont == 0) {
			return true;	
		} else {
			return false;	
		}
	}
}
?>