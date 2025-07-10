<?php

set_time_limit(0);

class DepreciacionesController extends AppController {
	var $name = 'Depreciaciones';
	var $uses = array('Depreciacion');
	var $paginate = array(
		'Depreciacion' => array(
			'order' => array('Depreciacion.depr_ano' => 'desc', 'Depreciacion.depr_revision' => 'desc')
		)
	);
	
	function index() {
		$this->Depreciacion->recursive = 0;
		$depreciaciones = $this->paginate();
		$this->set('depreciaciones', $depreciaciones);
	}
	
	function add($ipc = null, $ano = null) {
		if (isset($ipc) && !is_numeric($ipc)) {
			$this->Session->setFlash(__(utf8_encode('Valor no válido para IPC.'), true));
		} else {
			$this->set('ipc', $ipc);
		}
		
		if (isset($ano) && !is_numeric($ano)) {
			$this->Session->setFlash(__(utf8_encode('Valor no válido para año.'), true));
		} else {
			$this->set('ano', $ano);
		}
	
		if (!empty($this->data)) {
			$this->Depreciacion->create();
			$this->Depreciacion->set($this->data);
			
			if ($this->Depreciacion->validates()) {
				$ipc = $this->data['Depreciacion']['depr_ipc'];
				$ano = $this->data['Depreciacion']['depr_ano']['year'];
			
				if ($this->Depreciacion->calculaDepreciacion($ipc, $ano)) {
					$this->Log->write($this->Session->read('userdata.Usuario.usua_id'), "Nuevo Calculo de Depreciacion", $_REQUEST);
					$this->Session->setFlash(__(utf8_encode('Depreciación calculada con éxito.'), true));
					$this->redirect(array('action' => 'index'));
				} else {
					$this->Session->setFlash(__(utf8_encode('Ocurrió un error al calcular la depreciación, inténtelo nuevamente.'), true));
				}
			}
		}
	}
	
	function excel($depr_id) {
		$this->layout = 'ajax';
		ini_set("memory_limit", "1024M");
		
		$logo = $this->Configuracion->obtieneLogo();
		$imageFile = $_SERVER['DOCUMENT_ROOT']."/app/webroot/files/logo.png";
		$fp = @fopen($_SERVER['DOCUMENT_ROOT']."/app/webroot/files/logo.png", "w");
		
		if ($fp == true) {
			try {
				$fp = fopen($imageFile, "w");
				fputs($fp, base64_decode($logo));
				fclose($fp);
				
				// cambiamos el logo a bmp
				$im = new Imagick($imageFile);
				$im->setImageFormat("bmp");
				$imageFileConverted = $_SERVER['DOCUMENT_ROOT']."/app/webroot/files/logo.bmp";
				$im->writeImage($imageFileConverted);
			} catch (ImagickException $e) {
				echo $e->getMessage();
			}
		} else {
			$this->Session->setFlash(__('No se puede generar el logo para el reporte', true));
			$this->redirect(array('action' => 'index'));
		}
		
		$workbook = new Spreadsheet_Excel_Writer();		
		
		// Cabeceras de tabla
		$format_head = $workbook->addFormat();
		$format_head->setFgColor('yellow');
		$format_head->setBorder(1);
		$format_head->setAlign('justify');
		
		// Formato de celdas
		$format_cell = $workbook->addFormat();
		$format_cell->setBorder(1);
		
		// Formato de celdas alineado
		$format_cell_align = $workbook->addFormat();
		$format_cell_align->setBorder(1);
		$format_cell_align->setAlign('right');
		
		// info
		$info = $this->Depreciacion->DetalleDepreciacion->obtieneTodo($depr_id);
		$info_h = $this->Depreciacion->find('first', array('conditions' => array('Depreciacion.depr_id' => $depr_id)));
		
		// Totales
		$total_correc_monetaria = 0;
		$total_depr_correc_monetaria = 0;
		$total_depr_ejercicio = 0;
		
		foreach ($info as $fami => $row) {
			$worksheet = $workbook->addWorksheet();
			$worksheet->setOutline(true, true, true, true);
			$worksheet->setColumn(0, 0, 30);
			$worksheet->setColumn(1, 1, 20);
			$worksheet->setColumn(2, 20, 13);
			$worksheet->setRow(10, 39);
			
			// insertamos info
			$date = date("d-m-Y H:i:s", strtotime($info_h['Depreciacion']['depr_fecha']));
			$worksheet->write(1, 0, "Familia:");			
			$worksheet->write(1, 1, $fami);
			$worksheet->write(2, 0, "Año:");
			$worksheet->write(2, 1, $info_h['Depreciacion']['depr_ano']);
			$worksheet->write(3, 0, "Fecha de Operación:");
			$worksheet->write(3, 1, $date);
		
			// insertamos logo en libro excel
			//$worksheet->insertBitmap(0, 0, $imageFileConverted);
			
			$worksheet->write(10, 0, "Producto", $format_head);
			$worksheet->write(10, 1, "Código de barra", $format_head);
			$worksheet->write(10, 2, "Propiedad", $format_head);
			$worksheet->write(10, 3, "Situación", $format_head);
			$worksheet->write(10, 4, "Marca", $format_head);
			$worksheet->write(10, 5, "Color", $format_head);
			$worksheet->write(10, 6, "Fecha de Garantía", $format_head);
			$worksheet->write(10, 7, "Fecha de adquisición", $format_head);
			$worksheet->write(10, 8, "Valor Libro", $format_head);
			$worksheet->write(10, 9, "IPC", $format_head);
			$worksheet->write(10, 10, "Corrección Monetaria", $format_head);
			$worksheet->write(10, 11, "Valor Corregido", $format_head);
			$worksheet->write(10, 12, "Valor Actual", $format_head);
			$worksheet->write(10, 13, "Depreciación Acumulada Anterior", $format_head);
			$worksheet->write(10, 14, "Corrección Monetaria (depreciación)", $format_head);
			$worksheet->write(10, 15, "Valor Corregido (depreciación)", $format_head);
			$worksheet->write(10, 16, "Vida Útil (próxima ejercicio)", $format_head);
			$worksheet->write(10, 17, "Valor a Depreciar", $format_head);
			$worksheet->write(10, 18, "Depreciación", $format_head);
			$worksheet->write(10, 19, "Depreciación Acumulada", $format_head);
			$worksheet->write(10, 20, "Valor Neto", $format_head);
			$row_count = 11;
			$correc_monetaria = 0;
			$depr_correc_monetaria = 0;
			$depr_ejercicio = 0;
			
			foreach ($row as $row_) {
				ob_clean();
				$row_['dede_valor_actual'] = $row_['dede_valor_corregido'];
				//$row_['depr_valor_a_depreciar'] = ($row_['dede_valor_corregido']-$row_['dede_depr_acumulada_anterior']); 
				$row_['depr_valor_a_depreciar'] = $row_['dede_valor_corregido']; 
			
				$worksheet->write($row_count, 0, utf8_decode($row_['prod_nombre']), $format_cell);
				$worksheet->writeString($row_count, 1, $row_['ubaf_codigo'], $format_cell);
				$worksheet->write($row_count, 2, utf8_decode($row_['prop_nombre']), $format_cell);
				$worksheet->write($row_count, 3, utf8_decode($row_['situ_nombre']), $format_cell);
				$worksheet->write($row_count, 4, utf8_decode($row_['marc_nombre']), $format_cell);
				$worksheet->write($row_count, 5, utf8_decode($row_['colo_nombre']), $format_cell);
				
				if ($row_['ubaf_fecha_garantia'] != "") {
					$worksheet->write($row_count, 6, date("d-m-Y", strtotime($row_['ubaf_fecha_garantia'])), $format_cell);
				} else {
					$worksheet->write($row_count, 6, null, $format_cell);
				}
				
				$worksheet->write($row_count, 7, date("d-m-Y", strtotime($row_['ubaf_fecha_adquisicion'])), $format_cell);
				if ($row_['dede_valor_libro'] == 0) {
					$row_['dede_valor_libro'] = 1;
				}	
				
				$worksheet->writeString($row_count, 8, number_format($row_['dede_valor_libro'],0,',','.'), $format_cell_align);
				$worksheet->write($row_count, 9, $row_['depr_ipc'], $format_cell_align);
				$worksheet->writeString($row_count, 10, number_format($row_['dede_correc_monetaria'], 0, ',', '.'), $format_cell_align);
				$worksheet->writeString($row_count, 11, number_format($row_['dede_valor_corregido'], 0, ',', '.'), $format_cell_align);
				$worksheet->writeString($row_count, 12, number_format($row_['dede_valor_actual'], 0, ',', '.'), $format_cell_align);
				$worksheet->writeString($row_count, 13, number_format($row_['dede_depr_acumulada_anterior'], 0, ',', '.'), $format_cell_align);
				$worksheet->writeString($row_count, 14, number_format($row_['dede_depr_correc_monetaria'], 0, ',', '.'), $format_cell_align);
				$worksheet->writeString($row_count, 15, number_format($row_['dede_depr_valor_corregido'], 0, ',', '.'), $format_cell_align);
				if ($row_['dede_vida_util_prox'] == 0) {
					$row_['dede_vida_util_prox'] = 'F';
					$worksheet->writeNote($row_count, 16, 'Finalización vida útil');
				}	
				$worksheet->write($row_count, 16, $row_['dede_vida_util_prox'], $format_cell_align);
				$worksheet->writeString($row_count, 17, number_format($row_['depr_valor_a_depreciar'], 0, ',', '.'), $format_cell_align);
				$worksheet->writeString($row_count, 18, number_format($row_['dede_depr_valor'], 0, ',', '.'), $format_cell_align);
				$worksheet->writeString($row_count, 19, number_format($row_['dede_depr_acumulada'], 0, ',', '.'), $format_cell_align);
				$worksheet->writeString($row_count, 20, number_format($row_['dede_valor_actualizado'], 0, ',', '.'), $format_cell_align);
				//sumas
				$correc_monetaria += $row_['dede_correc_monetaria'];
				$depr_correc_monetaria += $row_['dede_depr_correc_monetaria'];
				$depr_ejercicio += $row_['dede_depr_valor'];
				$row_count++;
			}
			
			$total_correc_monetaria += $correc_monetaria;
			$total_depr_correc_monetaria += $depr_correc_monetaria;
			$total_depr_ejercicio += $depr_ejercicio;
		}
		
		// Se dibujan los asientos contables
		$worksheet = $workbook->addWorksheet('Asientos Contables');
		$worksheet->setOutline(true, true, true, true);
		$worksheet->setColumn(0, 0, 45);
		$worksheet->setColumn(1, 2, 15);
		
		$worksheet->write(1, 0, 'Actualización Bienes del Activo', $format_head);
		$worksheet->writeString(1, 1, number_format($total_correc_monetaria, 0, ',', '.'), $format_cell_align);
		$worksheet->write(1, 2, null, $format_cell_align);
		$worksheet->write(2, 0, 'Corrección Monetaria', $format_head);
		$worksheet->write(2, 1, null, $format_cell_align);
		$worksheet->writeString(2, 2, number_format($total_correc_monetaria, 0, ',', '.'), $format_cell_align);
		$worksheet->write(3, 0, '* Se corrige monetariamente los bienes.', $format_cell);
			
		$worksheet->write(5, 0, 'Corrección Monetaria', $format_head);
		$worksheet->writeString(5, 1, number_format($total_depr_correc_monetaria, 0, ',', '.'), $format_cell_align);
		$worksheet->write(5, 2, null, $format_cell_align);
		$worksheet->write(6, 0, 'Depreciación Acumulada Activo', $format_head);
		$worksheet->write(6, 1, null, $format_cell_align);
		$worksheet->writeString(6, 2, number_format($total_depr_correc_monetaria, 0, ',', '.'), $format_cell_align);
		$worksheet->write(7, 0, '* Se corrige monetariamente Depreciación Acumulada.', $format_cell);
		
		$worksheet->write(9, 0, 'Depreciación', $format_head);
		$worksheet->writeString(9, 1, number_format($total_depr_ejercicio, 0, ',', '.'), $format_cell_align);
		$worksheet->write(9, 2, null, $format_cell_align);
		$worksheet->write(10, 0, 'Depreciación Acumulada Bienes del Activo', $format_head);
		$worksheet->write(10, 1, null, $format_cell_align);
		$worksheet->writeString(10, 2, number_format($total_depr_ejercicio, 0, ',', '.'), $format_cell_align);
		$worksheet->write(11, 0, '* Depreciación del ejercicio.', $format_cell);
		
		ob_clean();
		$workbook->send('Depreciacion_'.$info_h['Depreciacion']['depr_revision'].'_'.$info_h['Depreciacion']['depr_ano'].'.xls');
		$workbook->close();
		exit;
	}
	
	function delete($id = null) {
		if (!$id) {
			$this->Session->setFlash(__('ID invalido', true));
			$this->redirect(array('action'=>'index'));
		}
		
		$ultimoAnoDepreciado = $this->Depreciacion->find('first', array('recursive' => 0, 'order' => 'Depreciacion.depr_ano desc', 'fields' => array('Depreciacion.depr_ano')));
		$infoDepreciacion = $this->Depreciacion->find('first', array('recursive' => 0, 'fields' => array('Depreciacion.depr_ano'), 'conditions' => array('Depreciacion.depr_id' => $id)));
		
		if ($ultimoAnoDepreciado['Depreciacion']['depr_ano'] == $infoDepreciacion['Depreciacion']['depr_ano']) {
			if ($this->Depreciacion->eliminaDepreciacion($id)) {
				if ($this->Depreciacion->delete($id)) {
					$this->Log->write($this->Session->read('userdata.Usuario.usua_id'), utf8_encode('Eliminación de Depreciación'), $_REQUEST);
					$this->Session->setFlash(__(utf8_encode('Depreciación eliminada.'), true));
					$this->redirect(array('action'=>'index'));
				}
			} else {
				$this->Session->setFlash(__(utf8_encode('Ocurrio un error al elminar la depreciación.'), true));
			}		
		} else {			
			$this->Session->setFlash(__(utf8_encode('No se pudo eliminar la depreciación. Solo esta permitido eliminar el último registro.'), true));
			$this->redirect(array('action' => 'index'));
		}	
	}
}

?>