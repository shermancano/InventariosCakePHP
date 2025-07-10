<?php
class GastosController extends AppController {
	var $name = 'Gastos';

	function index() {
		$this->Gasto->recursive = 0;
		$this->set('gastos', $this->paginate());
	}

	function view($id = null) {
		if (!$id) {
			$this->Session->setFlash(__('ID invalido', true));
			$this->redirect(array('action' => 'index'));
		}
		$this->set('gasto', $this->Gasto->read(null, $id));
	}

	function add() {
		if (!empty($this->data)) {
			$this->Gasto->create();
			$this->Gasto->set($this->data);
			
			if ($this->Gasto->validates()) {
				if ($this->Gasto->save($this->data)) {
					$this->Log->write($this->Session->read('userdata.Usuario.usua_id'), 'Nuevo Gasto', $_REQUEST);
					$this->Session->setFlash(__('El gasto ha sido guardado', true));
					$this->redirect(array('action' => 'index'));
				} else {
					$this->Session->setFlash(__(utf8_encode('El gasto no pudo ser guardado, inténtelo nuevamente'), true));
				}
			}
		}
		
		$gastos = $this->Gasto->TipoGasto->find('all');
		$combo_gastos = array();
		
		foreach ($gastos as $gasto) {
			$combo_gastos[$gasto['TipoGasto']['tiga_id']] = $gasto['TipoGasto']['tiga_descripcion'];
		}
		$this->set('combo_gastos', $combo_gastos);
		
		$contratos = $this->Gasto->Contrato->find('all');
		$combo_contratos = array();
		
		foreach ($contratos as $contrato) {
			$combo_contratos[$contrato['Contrato']['cont_id']] = $contrato['Contrato']['cont_nombre'];
		}
		$this->set('combo_contratos', $combo_contratos);
		
		$tipos_montos = $this->Gasto->TipoMonto->find('all');
		$combo_tipo_montos = array();
		
		foreach ($tipos_montos as $tipo_monto) {
			$combo_tipo_montos[$tipo_monto['TipoMonto']['timo_id']] = $tipo_monto['TipoMonto']['timo_descripcion'];
		}
		$this->set('combo_tipo_montos', $combo_tipo_montos);
	}

	function edit($id = null) {
		if (!$id && empty($this->data)) {
			$this->Session->setFlash(__('Invalid gasto', true));
			$this->redirect(array('action' => 'index'));
		}
		if (!empty($this->data)) {
			$this->Gasto->set($this->data);
			
			if ($this->Gasto->validates()) {
				if ($this->Gasto->save($this->data)) {
					// actualizamos el monto_convertido
					// x este medio ya que el trigger lanza error de stack
					$gast_monto = $this->data['Gasto']['gast_monto'];
					$timo_id = $this->data['Gasto']['timo_id'];
					$gast_fecha = $this->data['Gasto']['gast_fecha'];
					$gast_id = $this->Gasto->id;
					
					if ($this->Gasto->updMontoConvertido($gast_id, $gast_monto, $timo_id, $gast_fecha)) {
						$this->Log->write($this->Session->read('userdata.Usuario.usua_id'), utf8_encode('Modificación Gasto'), $_REQUEST);
						$this->Session->setFlash(__('El gasto ha sido guardado', true));
						$this->redirect(array('action' => 'index'));
					} else {
						$this->Session->setFlash(__(utf8_encode('El gasto no pudo ser guardado, inténtelo nuevamente'), true));
					}
				} else {
					$this->Session->setFlash(__(utf8_encode('El gasto no pudo ser guardado, inténtelo nuevamente'), true));
				}
			}
		}
		if (empty($this->data)) {
			$this->data = $this->Gasto->read(null, $id);
		}
		
		$gastos = $this->Gasto->TipoGasto->find('all');
		$combo_gastos = array();
		
		foreach ($gastos as $gasto) {
			$combo_gastos[$gasto['TipoGasto']['tiga_id']] = $gasto['TipoGasto']['tiga_descripcion'];
		}
		$this->set('combo_gastos', $combo_gastos);
		
		$tipos_montos = $this->Gasto->TipoMonto->find('all');
		$combo_tipo_montos = array();
		
		foreach ($tipos_montos as $tipo_monto) {
			$combo_tipo_montos[$tipo_monto['TipoMonto']['timo_id']] = $tipo_monto['TipoMonto']['timo_descripcion'];
		}
		$this->set('combo_tipo_montos', $combo_tipo_montos);
	}

	function delete($id = null) {
		if (!$id) {
			$this->Session->setFlash(__('ID invalido', true));
			$this->redirect(array('action'=>'index'));
		}
		if ($this->Gasto->delete($id)) {
			$this->Log->write($this->Session->read('userdata.Usuario.usua_id'), utf8_encode('Eliminación Gasto'), $_REQUEST);
			$this->Session->setFlash(__('Gasto eliminado', true));
			$this->redirect(array('action'=>'index'));
		}
		$this->Session->setFlash(__('El gasto no se pudo eliminar', true));
		$this->redirect(array('action' => 'index'));
	}
	
	function resumen() {}
	
	function getResumen($cont_id = null) {
		$this->layout = "ajax";
		$info_res = $this->Gasto->getResumen($cont_id);
		$this->set('info_res', $info_res);
	}
	
	function excel($cont_id = null) {
		$this->layout = "ajax";
		$info_res = $this->Gasto->getResumen($cont_id);
		$info_res = json_decode($info_res);
		$this->set("info_res", $info_res);
		header("Content-type: application/vnd.ms-excel; name=resumen_gastos_".$cont_id.".xls");
		header("Content-disposition: attachment; filename=resumen_gastos_".$cont_id.".xls");
	}
	
	function pdf($cont_id = null) {
		$this->layout = "ajax";

		try {
			$pdf =& new HTML_ToPDF("http://".$_SERVER['HTTP_HOST']."/gastos/pdf_html/".$cont_id, "http://".$_SERVER['HTTP_HOST']);
			$pdf->setUnderlineLinks(true);
			$pdf->setScaleFactor('0.8');
			$pdf->setUseColor(true);
			$pdf->setFooter('center', '&nbsp;');
			$pdf->setHeader('center', '&nbsp;');
			$fp = fopen($pdf->convert(), "r");
			
			header("Content-type: application/pdf; name=resumen_gastos_".$cont_id.".pdf");
			header("Content-disposition: attachment; filename=resumen_gastos_".$cont_id.".pdf");
			
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
		$info_res = $this->Gasto->getResumen($cont_id);
		$info_res = json_decode($info_res);
		$this->set("info_res", $info_res);
	}
	
	function getFacturas($fecha, $cont_id) {
		$this->layout = "ajax";
		$facturas = $this->Gasto->getFacturasByFecha($fecha, $cont_id);
		$info_fact = array();
		
		if (sizeof($facturas) > 0) {
			foreach ($facturas as $fact) {
				$info_fact[] = array_pop($fact);
			}
			$facturas = $info_fact;
		}
		$this->set('facturas', json_encode($facturas));
	}
	
	function graficos() {}
	
	//busca contratos SOLO si tienen gastos
	function searchContratos() {
		$this->layout = "ajax";
		$strings = explode(" ", stripslashes($_GET['term']));
		$info_con = $this->Gasto->searchContrato($strings);
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