<?php
class ActivoFijoDocumento extends AppModel {
	var $name = 'ActivoFijoDocumento';
	var $primaryKey = 'acfd_id';
	var $useTable = 'activos_fijos_documentos';
	
	var $belongsTo = array(
		'ActivoFijo' => array(
			'className' => 'ActivoFijo',
			'foreignKey' => 'acfi_id'
		)
	);
}
?>