<?php
class Actividad extends AppModel {
	var $name = 'Actividad';
	var $primaryKey = 'acti_id';
	var $useTable = 'actividades';

	var $belongsTo = array(
		'Etapa' => array(
			'className' => 'Etapa',
			'foreignKey' => 'etap_id',
			'conditions' => '',
			'fields' => '',
			'order' => ''
		)
	);
}
?>
