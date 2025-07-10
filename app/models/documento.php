<?php
class Documento extends AppModel {
	var $name = 'Documento';
	var $primaryKey = 'docu_id';
	
	var $belongsTo = array(
	 	 'Contrato' => array('className' => 'Contrato',
	 	 	 				 'foreignKey' => 'cont_id')
	);
	
	var $validate = array(
		'cont_nombre' => array('rule' => '/[\w\s]+/', 'required' => true, 'message' => 'Debe seleccionar un contrato')
	   ,'docs' => array('rule' => array('checkContFiles'), 'message' => 'Debe seleccionar al menos un archivo')
	);
	
	function checkContFiles($data) {
		foreach ($data['docs'] as $doc) {
			if ($doc['tmp_name'] == "") {
				$this->validationErrors['docs'] = "Debe seleccionar al menos un archivo";
				return false;
			}
		}
		return true;	
	}
	
	function saveDocumento($cont_id, $data) {
		$docsPath = Configure::read("docsPath");
		
		foreach ($data['Documento'] as $doc) {
			if ($doc['tmp_name'] == "") continue;
			$data_ = array();
			$this->create();
			
			if (is_uploaded_file($doc['tmp_name'])) {
				$fullPath = $docsPath."/".$doc['name'];
				
				if (move_uploaded_file($doc['tmp_name'], $fullPath)) {
					$data_['Documento']['cont_id'] = $cont_id;
					$data_['Documento']['docu_tipo'] = $doc['type'];
					$data_['Documento']['docu_nombre'] = $doc['name'];
					$data_['Documento']['docu_path'] = $fullPath;
					$data_['Documento']['cont_nombre'] = $cont_id;
					
					if (!$this->save($data_)) {
						return false;
					}
				} else {
					return false;
				}	
			} else {
				return false;	
			}
		}
		return true;
	}
}
?>
