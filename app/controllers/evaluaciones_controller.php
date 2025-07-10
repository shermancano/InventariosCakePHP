<?php
class EvaluacionesController extends AppController {
	var $name = 'Evaluaciones';
	var $uses = array('Evaluacion', 'TipoItem');
	var $paginate = array(
		'Evaluacion' => array(
			'order' => array('Evaluacion.eval_id' => 'desc')
		)
	); 
	
	function index() {
		$this->Evaluacion->recursive = 2;
		$evaluaciones = $this->paginate();
		
		$info = array();
		foreach ($evaluaciones as $eval) {
			$eval['Evaluacion']['nota_final'] = $this->Evaluacion->getNotaFinal($eval['Evaluacion']['cont_id']);
			$info[] = $eval;
		}
		$evaluaciones = $info;
		$this->set('evaluaciones', $evaluaciones);
	}

	function add($cont_id = null) {
		if (!empty($this->data)) {
			$this->Evaluacion->create();
			$this->Evaluacion->set($this->data);
			
			if ($this->Evaluacion->validates()) {
				if ($this->Evaluacion->DetalleEvaluacion->saveDetalleEvaluacion($this->data)) {
					$this->Log->write($this->Session->read('userdata.Usuario.usua_id'), utf8_encode('Nueva Evaluación'), $_REQUEST);
					$this->Session->setFlash(__(utf8_encode('La evaluación ha sido guardada'), true));
					$this->redirect(array('action' => 'index'));
				} else {
					$this->Session->setFlash(__(utf8_encode('La evaluación no pudo ser guardada. Por favor, inténtelo nuevamente'), true));
				}
			}
		}
		
		if ($cont_id != null) {
			$info_cont = $this->Evaluacion->Contrato->find('first', array('fields' => array('Contrato.cont_id', 'Contrato.cont_nombre'), 'conditions' => array('Contrato.cont_id' => $cont_id)));
			$this->set('cont_id', $info_cont['Contrato']['cont_id']);
			$this->set('cont_nombre', $info_cont['Contrato']['cont_nombre']);
		}
		
		$info_tipos_item = $this->TipoItem->find('all', array('recursive' => 1, 'order' => 'tiit_id'));
		$this->set('tipos_item', $info_tipos_item);
	}

	function edit($id = null) {
		if (!$id && empty($this->data)) {
			$this->Session->setFlash(__('ID invalido', true));
			$this->redirect(array('action' => 'index'));
		}
		if (!empty($this->data)) {
			$this->Evaluacion->set($this->data);
			
			if ($this->Evaluacion->validates()) {
				if ($this->Evaluacion->DetalleEvaluacion->updateDetalleEvaluacion($this->data)) {
					$this->Log->write($this->Session->read('userdata.Usuario.usua_id'), utf8_encode('Modificación Evaluación'), $_REQUEST);
					$this->Session->setFlash(__(utf8_encode('La evaluación ha sido guardada'), true));
					$this->redirect(array('action' => 'index'));
				} else {
					$this->Session->setFlash(__(utf8_encode('La evaluación no pudo ser guardada. Por favor, inténtelo nuevamente'), true));
				}
			}
		}
		if (empty($this->data)) {
			$this->Evaluacion->recursive = 2;
			$this->data = $this->Evaluacion->read(null, $id);
		}
		
		$edit_info = $this->Evaluacion->DetalleEvaluacion->findEditInfo($id);
		$nota_final = $this->Evaluacion->getNotaFinal($this->data['Evaluacion']['cont_id']);
		$this->set('nota_final', $nota_final);
		$this->set('eval_id', $id);
		$this->set('edit_info', $edit_info);
	}

	function delete($id = null) {
		if (!$id) {
			$this->Session->setFlash(__('ID invalido', true));
			$this->redirect(array('action'=>'index'));
		}
		if ($this->Evaluacion->delete($id)) {
			$this->Log->write($this->Session->read('userdata.Usuario.usua_id'), utf8_encode('Eliminación Evaluación'), $_REQUEST);	
			$this->Session->setFlash(__('Evaluacion eliminada', true));
			$this->redirect(array('action'=>'index'));
		}
		$this->Session->setFlash(__('No se pudo eliminar la evaluacion', true));
		$this->redirect(array('action' => 'index'));
	}
	
	function resumen() {}
	
	function getResumen($eval_id = null) {
		$this->layout = "ajax";
		$info_res = $this->Evaluacion->getResumen($eval_id);
		$this->set('info_res', $info_res);
	}
	
	function searchEvaluaciones() {
		$this->layout = "ajax";
		$strings = explode(" ", stripslashes($_GET['term']));
		$info_eval = $this->Evaluacion->searchEvaluacion($strings);
		$evaluaciones = array();
	
		if (sizeof($info_eval) > 0) {
			foreach ($info_eval as $eval) {
				$eval = array_pop($eval);
				$evaluaciones[] = array("value" => $eval['cont_nombre'], "label" => $eval['cont_nombre'], 'eval_id' => $eval['eval_id']);
			}
		}
		echo json_encode($evaluaciones);
	}
	
	function excel($eval_id) {
		$this->layout = "ajax";
		$info_res = json_decode($this->Evaluacion->getResumen($eval_id));
		$this->set('info_res', $info_res);
		header("Content-type: application/vnd.ms-excel; name=evaluacion_".$eval_id.".xls");
		header("Content-disposition: attachment; filename=evaluacion_".$eval_id.".xls");
	}
	
	function pdf($eval_id = null) {
		$this->layout = "ajax";

		try {
			$pdf =& new HTML_ToPDF("http://".$_SERVER['HTTP_HOST']."/evaluaciones/pdf_html/".$eval_id, "http://".$_SERVER['HTTP_HOST']);
			$pdf->setUnderlineLinks(true);
			$pdf->setScaleFactor('0.8');
			$pdf->setUseColor(true);
			$pdf->setFooter('center', '&nbsp;');
			$pdf->setHeader('center', '&nbsp;');
			$fp = fopen($pdf->convert(), "r");
			
			header("Content-type: application/pdf; name=evaluacion_".$eval_id.".pdf");
			header("Content-disposition: attachment; filename=evaluacion_".$eval_id.".pdf");
			
			if (rewind($fp)) {
				fpassthru($fp);
			}
			fclose($fp);
			
		} catch (HTML_ToPDFException $e) {
			echo $e->getMessage();
		}
	}
	
	function pdf_html($eval_id) {
		$this->layout = "ajax";
		$info_res = json_decode($this->Evaluacion->getResumen($eval_id));
		$this->set('info_res', $info_res);
	}
}
?>
