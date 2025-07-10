<?php
class DetallesActivosFijosController extends AppController {
	var $name = 'DetallesActivosFijos';
	var $uses = array('DetalleActivoFijo');
	
	function delete_ajax($id = null) {
		$this->layout = "ajax";
		
		if ($this->DetalleActivoFijo->delete($id)) {
			$ret = "ok";
		} else {
			$ret = "err";
		}
		
		$this->set('ret', $ret);
	}
}
?>