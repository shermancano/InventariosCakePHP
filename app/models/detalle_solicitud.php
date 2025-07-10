<?php
class DetalleSolicitud extends AppModel {
	var $name = 'DetalleSolicitud';	
	var $useTable = 'detalle_solicitudes';
	var $primaryKey = 'deso_id';
	
	var $belongsTo = array(
		'Solicitud' => array(
			'className' => 'Solicitud',
			'foreignKey' => 'soli_id',
			'conditions' => '',
			'fields' => '',
			'order' => ''
		),
		'Producto' => array(
			'className' => 'Producto',
			'foreignKey' => 'prod_id',
			'conditions' => '',
			'fields' => '',
			'order' => ''
		)
	);
	
	function save($data, $soli_id) {
		foreach ($data['DetalleSolicitud'] as $deso) {
			$info_ = array('DetalleSolicitud' => array('soli_id' => $soli_id
													  ,'prod_id' => $deso['prod_id']
													  ,'deso_cantidad' => $deso['deso_cantidad']));
			$this->create();
			
			if (!parent::save($info_)) {
				return false;
			}
		}
	
		return true;
	}
}
?>
