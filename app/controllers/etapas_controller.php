<?php
class EtapasController extends AppController {
	var $name = 'Etapas';

	function index() {
		$this->Etapa->recursive = 0;
		$info_etapa = $this->paginate();
		$this->set('etapas', $info_etapa);
	}

	function add() {
		if (!empty($this->data)) {
			$this->Etapa->create();
			$this->Etapa->set($this->data);
			
			if ($this->Etapa->validates()) {
				$this->Etapa->begin();
				if ($this->Etapa->save($this->data)) {
					if (!isset($this->data['Actividad'])) {
						$this->Etapa->commit();
						$this->Log->write($this->Session->read('userdata.Usuario.usua_id'), 'Nueva Etapa', $_REQUEST);
						$this->Session->setFlash(__('La etapa ha sido guardada', true));
						$this->redirect(array('action' => 'index'));
						exit;
					}
					$etap_id = $this->Etapa->id;
					$info_acti = array();
					
					foreach ($this->data['Actividad'] as $acti) {
						$acti['etap_id'] = $etap_id;
							
						if ($acti['acti_fecha_presupuestada'] != "") {
							$acti['acti_fecha_presupuestada'] = date('Y-m-d', strtotime($acti['acti_fecha_presupuestada']));
						}
						
						if ($acti['acti_fecha_real'] != "") {
							$acti['acti_fecha_real'] = date('Y-m-d', strtotime($acti['acti_fecha_real']));
						}
						$info_acti[] = $acti;	
					}
					$this->data['Actividad'] = $info_acti;
					
					if ($this->Etapa->Actividad->saveAll($this->data['Actividad'])) {
						$this->Etapa->commit();
						$this->Log->write($this->Session->read('userdata.Usuario.usua_id'), 'Nueva Etapa', $_REQUEST);
						$this->Session->setFlash(__('La etapa ha sido guardada', true));
						$this->redirect(array('action' => 'index'));
					} else {
						$this->Etapa->rollback();
						$this->Session->setFlash(__(utf8_encode('No se pudo guardar la etapa, por favor inténtelo nuevamente'), true));
					}
				} else {
					$this->Etapa->rollback();
					$this->Session->setFlash(__(utf8_encode('No se pudo guardar la etapa, por favor inténtelo nuevamente'), true));
				}
			}
		}
	}

	function edit($id = null) {
		if (!$id && empty($this->data)) {
			$this->Session->setFlash(__('Invalid etapa', true));
			$this->redirect(array('action' => 'index'));
		}
		if (!empty($this->data)) {
			if ($this->Etapa->save($this->data)) {
				$info_acti = array();
					
				foreach ($this->data['Actividad'] as $acti) {
					$acti['etap_id'] = $this->data['Etapa']['etap_id'];
					
					if ($acti['acti_fecha_presupuestada'] != "") {
						$acti['acti_fecha_presupuestada'] = date('Y-m-d', strtotime($acti['acti_fecha_presupuestada']));
					}
					
					if ($acti['acti_fecha_real'] != "") {
						$acti['acti_fecha_real'] = date('Y-m-d', strtotime($acti['acti_fecha_real']));
					}
					$info_acti[] = $acti;	
				}
					
				$this->data['Actividad'] = $info_acti;
				
				if ($this->Etapa->Actividad->saveAll($this->data['Actividad'])) {
					$this->Log->write($this->Session->read('userdata.Usuario.usua_id'), utf8_encode('Modificación Etapa'), $_REQUEST);
					$this->Session->setFlash(__('La etapa ha sido guardada', true));
					$this->redirect(array('action' => 'index'));
				} else {
					$this->Session->setFlash(__(utf8_encode('No se pudo guardar la etapa, por favor inténtelo nuevamente'), true));
				}
			} else {
				$this->Session->setFlash(__(utf8_encode('No se pudo guardar la etapa, por favor inténtelo nuevamente'), true));
			}
		}
		if (empty($this->data)) {
			$this->data = $this->Etapa->read(null, $id);
		}
	}

	function delete($id = null) {
		if (!$id) {
			$this->Session->setFlash(__('Id invalido', true));
			$this->redirect(array('action'=>'index'));
		}
		if ($this->Etapa->delete($id)) {
			$this->Log->write($this->Session->read('userdata.Usuario.usua_id'), utf8_encode('Eliminación Etapa'), $_REQUEST);
			$this->Session->setFlash(__('Etapa eliminada', true));
			$this->redirect(array('action'=>'index'));
		}
		$this->Session->setFlash(__('No se pudo eliminar la etapa', true));
		$this->redirect(array('action' => 'index'));
	}
	
	function resumen() {}
	
	function getResumen($cont_id = null) {
		$this->layout = "ajax";
		$info_res = $this->Etapa->getResumen($cont_id);
		$this->set('info_res', $info_res);
	}
	
	function excel($cont_id = null) {
		$this->layout = "ajax";
		$info_res = $this->Etapa->getResumen($cont_id);
		$info_res = json_decode($info_res);
		
		$this->set("info_res", $info_res);
		header("Content-type: application/vnd.ms-excel; name=resumen_monitoreo_".$cont_id.".xls");
		header("Content-disposition: attachment; filename=resumen_monitoreo_".$cont_id.".xls");
	}
	
	function pdf($cont_id = null) {
		$this->layout = "ajax";

		try {
			$pdf =& new HTML_ToPDF("http://".$_SERVER['HTTP_HOST']."/etapas/pdf_html/".$cont_id, "http://".$_SERVER['HTTP_HOST']);
			$pdf->setUnderlineLinks(true);
			$pdf->setScaleFactor('0.8');
			$pdf->setUseColor(true);
			$pdf->setFooter('center', '&nbsp;');
			$pdf->setHeader('center', '&nbsp;');
			$fp = fopen($pdf->convert(), "r");
			
			header("Content-type: application/pdf; name=resumen_monitoreo_".$cont_id.".pdf");
			header("Content-disposition: attachment; filename=resumen_monitoreo_".$cont_id.".pdf");
			
			if (rewind($fp)) {
				fpassthru($fp);
			}
			fclose($fp);
			
		} catch (HTML_ToPDFException $e) {
			echo $e->getMessage();
		}
	}
	
	function pdf_html($cont_id) {
		$this->layout = "ajax";
		$info_res = $this->Etapa->getResumen($cont_id);
		$info_res = json_decode($info_res);
		$this->set("info_res", $info_res);
	}
	
	//busca contratos SOLO si tienen etapas
	function searchContratos() {
		$this->layout = "ajax";
		$strings = explode(" ", stripslashes($_GET['term']));
		$info_con = $this->Etapa->searchContrato($strings);
		$contratos = array();
	
		if (sizeof($info_con) > 0) {
			foreach ($info_con as $contrato) {
				$contrato = array_pop($contrato);
				$contratos[] = array("value" => $contrato['cont_nombre'], "label" => $contrato['cont_nombre'], 'cont_id' => $contrato['cont_id']);
			}
		}
		echo json_encode($contratos);
	}
}
?>