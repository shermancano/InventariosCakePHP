<?php
set_time_limit(0);
class ReportesController extends AppController {
	var $name = 'Reportes';
	var $uses = array('TipoBien', 'Reporte', 'Responsable', 'CentroCosto', 'Producto', 'UbicacionActivoFijo', 'ActivoFijo', 'Existencia', 'CuentaContable', 'Configuracion', 'TrazabilidadActivoFijo', 'Depreciacion', 'ActivoFijoMantencion', 'Financiamiento');
	
	function stock() {			
		$centros_costos = $this->Session->read('userdata.selectCC');
		$this->set('centros_costos', $centros_costos);
		$tipos_bienes = $this->TipoBien->find('list', array('fields' => array('tibi_id', 'tibi_nombre')));
		$this->set('tipos_bienes', $tipos_bienes);
	}
	
	function stock_pdf($ceco_id, $tibi_id, $prod_id) {
		$this->layout = "ajax";
		if (!$ceco_id) {
			$this->Session->setFlash(__('ID invalido', true));
			$this->redirect(array('action'=>'index_entrada'));
			$this->redirect(array('action'=>'index_entrada'));
		}
		
		// sacamos info del centro de costo
		$conditions = array("ceco_id" => $ceco_id);
		$info_cc = $this->CentroCosto->read(null, $ceco_id);
		
		$logo = $this->Configuracion->obtieneLogo();
		$fp = @fopen($_SERVER['DOCUMENT_ROOT']."/app/webroot/files/logo.png", "w");
		 
		if($fp==true){
			fputs($fp, base64_decode($logo));
			fclose($fp);
			
			try {
				$pdf = new HTML_ToPDF("http://".$_SERVER['HTTP_HOST']."/reportes/stock_pdf_html/".$ceco_id."/".$tibi_id."/".$prod_id, "http://".$_SERVER['HTTP_HOST']);
				$pdf->setUnderlineLinks(true);
				$pdf->setScaleFactor('0.8');
				$pdf->setUseColor(true);
				$pdf->setFooter('center', 'Página $N');
				$pdf->setHeader('center', '&nbsp;');
				//$pdf->setUseCss(true);
				//$pdf->setAdditionalCSS('* {margin:0; padding:0;}');
										
				$fp = fopen($pdf->convert(), "r");
				
				$ceco_nombre = str_replace(" ", "_", $info_cc['CentroCosto']['ceco_nombre']);
				
				header("Content-type: application/pdf; name=Stock_Existencias_".$ceco_nombre."_".date('d_m_Y_H_i_s').".pdf");
				header("Content-disposition: attachment; filename=Stock_Existencias_".$ceco_nombre."_".date('d_m_Y_H_i_s').".pdf");
				
				if (rewind($fp)) {
					fpassthru($fp);
				}
				fclose($fp);
				
			} catch (HTML_ToPDFException $e) {
				echo $e->getMessage();
			}
		}else{
			$this->Session->setFlash(__('No se puede generar el logo para el reporte', true));
			$this->redirect(array('action' => 'stock'));
		}
	}
	
	function stock_pdf_html($ceco_id, $tibi_id, $prod_id) {
		$this->layout = 'ajax';
		
		$cc_hijos = $this->ccArrayToCcVector($this->CentroCosto->findAllChildren($ceco_id));
		$conditions = array("ceco_id" => $cc_hijos);
		
		// sacamos info del centro de costo
		$info_cc = $this->CentroCosto->read(null, $ceco_id);
		
		// condicionamos filtro de tipo de bien
		if ($tibi_id != 'null') {
			$conditions['tibi_id'] = $tibi_id;
		}
		
		// condicionamos filtro de producto
		if ($prod_id != 'null') {
			$conditions['prod_id'] = $prod_id;
		}
		
		$info = $this->Reporte->stock(array('conditions' => $conditions));
		$this->set('info', $info);
		$this->set('info_cc', $info_cc);
	}
	
	function stock_excel($ceco_id, $tibi_id, $prod_id) {
		$this->layout = 'ajax';
		
		$cc_hijos = $this->ccArrayToCcVector($this->CentroCosto->findAllChildren($ceco_id));
		$conditions = array("ceco_id" => $cc_hijos);
		
		// sacamos info del centro de costo
		$info_cc = $this->CentroCosto->read(null, $ceco_id);
		
		// condicionamos filtro de tipo de bien
		if ($tibi_id != 'null') {
			$conditions['tibi_id'] = $tibi_id;
		}
		
		// condicionamos filtro de producto
		if ($prod_id != 'null') {
			$conditions['prod_id'] = $prod_id;
		}
		
		$info = $this->Reporte->stock(array('conditions' => $conditions));
		
		$workbook = new Spreadsheet_Excel_Writer();		
		$worksheet = $workbook->addWorksheet('Libro 1');
		$worksheet->setOutline(true, true, true, true);
		
		// Cabeceras de tabla
		$format_head =& $workbook->addFormat();
		$format_head->setFgColor('yellow');
		$format_head->setBorder(1);
		
		$worksheet->write(10, 0, 'ID Producto', $format_head);
		$worksheet->write(10, 1, 'Nombre', $format_head);
		$worksheet->write(10, 2, 'Codigo Interno', $format_head);
		$worksheet->write(10, 3, 'Tipo de Bien', $format_head);
		$worksheet->write(10, 4, 'Familia', $format_head);
		$worksheet->write(10, 5, 'Grupo', $format_head);
		$worksheet->write(10, 6, 'Stock Crítico (por CS/CC)', $format_head);
		$worksheet->write(10, 7, 'Stock Total', $format_head);
		
		// Formato de celdas
		$format_cell = $workbook->addFormat();
		$format_cell->setBorder(1);
		$row_count = 11;
		
		$worksheet->write(7, 3, "REPORTE DE STOCK EXISTENCIAS ".utf8_decode($info_cc['CentroCosto']['ceco_nombre']));
		$worksheet->write(9, 7, date("d-m-Y H:i:s"));
		
		foreach ($info as $row) {
			$row = array_pop($row);
			$total = $row['total_entradas']-$row['total_traslados'];
			$worksheet->write($row_count, 0, $row['prod_id'], $format_cell);
			$worksheet->write($row_count, 1, utf8_decode($row['prod_nombre']), $format_cell);
			$worksheet->writeString($row_count, 2, $row['prod_codigo'], $format_cell);
			$worksheet->write($row_count, 3, utf8_decode($row['tibi_nombre']), $format_cell);
			$worksheet->write($row_count, 4, utf8_decode($row['fami_nombre']), $format_cell);
			$worksheet->write($row_count, 5, utf8_decode($row['grup_nombre']), $format_cell);
			$worksheet->write($row_count, 6, $row['stcc_stock_critico'], $format_cell);
			$worksheet->write($row_count, 7, $total, $format_cell);
			$row_count++;
		}
		
		ob_clean();
		$ceco_nombre = str_replace(" ", "_", $info_cc['CentroCosto']['ceco_nombre']);
		$workbook->send('Stock_Existencias_'.$ceco_nombre."_".date('d_m_Y_H_i_s').'.xls');
		$workbook->close();
	}
	
	function stock_excel_activos_fijos($ceco_id, $tibi_id, $prod_id) {
		$this->layout = 'ajax';
	
		$cc_hijos = $this->ccArrayToCcVector($this->CentroCosto->findAllChildren($ceco_id));
		$conditions = array("ceco_id" => $cc_hijos);
		
		// sacamos info del centro de costo
		$info_cc = $this->CentroCosto->read(null, $ceco_id);
		
		// condicionamos filtro de tipo de bien
		if ($tibi_id != 'null') {
			$conditions['tibi_id'] = $tibi_id;
		}
		
		// condicionamos filtro de producto
		if ($prod_id != 'null') {
			$conditions['prod_id'] = $prod_id;
		}
		
		$info = $this->Reporte->stock(array('conditions' => $conditions));
		$param_iva = $this->Configuracion->find('first', array('conditions' => array('Configuracion.conf_id' => 'param_iva')));
		
		if (sizeof($param_iva) > 0 && is_array($param_iva)) {
			$valor_iva = $param_iva['Configuracion']['conf_valor'];
		} else {
			$valor_iva = 0;
		}
		
		$workbook = new Spreadsheet_Excel_Writer();		
		$worksheet = $workbook->addWorksheet('Libro 1');
		$worksheet->setOutline(true, true, true, true);
		
		// Cabeceras de tabla
		$format_head =& $workbook->addFormat();
		$format_head->setFgColor('yellow');
		$format_head->setBorder(1);
		
		$worksheet->write(10, 0, 'Código', $format_head);
		$worksheet->write(10, 1, 'Nombre', $format_head);
		$worksheet->write(10, 2, 'Grupo', $format_head);
		$worksheet->write(10, 3, 'Familia', $format_head);
		$worksheet->write(10, 4, 'Tipo de Bien', $format_head);
		$worksheet->write(10, 5, 'Propiedad', $format_head);
		$worksheet->write(10, 6, 'Situación', $format_head);
		$worksheet->write(10, 7, 'Marca', $format_head);
		$worksheet->write(10, 8, 'Color', $format_head);
		$worksheet->write(10, 9, 'Fecha Garantía', $format_head);
		$worksheet->write(10, 10, 'Precio', $format_head);
		$worksheet->write(10, 11, 'Serie', $format_head);
		$worksheet->write(10, 12, '¿Es Depreciable?', $format_head);
		$worksheet->write(10, 13, 'Vida Útil', $format_head);
		$worksheet->write(10, 14, 'Centro de Costo (Unidad)', $format_head);
		$worksheet->write(10, 15, 'Dependencia', $format_head);
		$worksheet->write(10, 16, 'Establecimiento', $format_head);
		
		
		// Formato de celdas
		$format_cell = $workbook->addFormat();
		$format_cell->setBorder(1);
		$row_count = 11;
		
		$worksheet->write(7, 3, "REPORTE DE STOCK ACTIVOS FIJOS ".utf8_decode($info_cc['CentroCosto']['ceco_nombre']));
		$worksheet->write(9, 11, date("d-m-Y H:i:s"));
		$sum_precio_total = 0;
		$sum_bienes = 0;
		
		foreach ($info as $row) {
			$row = array_pop($row);
			$worksheet->writeString($row_count, 0, $row['ubaf_codigo'], $format_cell);
			$worksheet->write($row_count, 1, utf8_decode($row['prod_nombre']), $format_cell);
			$worksheet->write($row_count, 2, utf8_decode($row['grup_nombre']), $format_cell);
			$worksheet->write($row_count, 3, utf8_decode($row['fami_nombre']), $format_cell);
			$worksheet->write($row_count, 4, utf8_decode($row['tibi_nombre']), $format_cell);
			$worksheet->write($row_count, 5, utf8_decode($row['prop_nombre']), $format_cell);
			$worksheet->write($row_count, 6, utf8_decode($row['situ_nombre']), $format_cell);
			$worksheet->write($row_count, 7, utf8_decode($row['marc_nombre']), $format_cell);
			$worksheet->write($row_count, 8, utf8_decode($row['colo_nombre']), $format_cell);
			$worksheet->write($row_count, 9, utf8_decode($row['ubaf_fecha_garantia']), $format_cell);
			$worksheet->write($row_count, 10, utf8_decode($row['ubaf_precio']), $format_cell);
			$worksheet->write($row_count, 11, utf8_decode($row['ubaf_serie']), $format_cell);
			
			if ($row['ubaf_depreciable'] == 1) {
				$row['ubaf_depreciable'] = "Si";			
			} else {
				$row['ubaf_depreciable'] = "No";
			}
				
			$worksheet->write($row_count, 12, utf8_decode($row['ubaf_depreciable']), $format_cell);
			$worksheet->write($row_count, 13, utf8_decode($row['ubaf_vida_util']), $format_cell);
			$worksheet->write($row_count, 14, utf8_decode($row['hijo']), $format_cell);
			$worksheet->write($row_count, 15, utf8_decode($row['padre']), $format_cell);
			$worksheet->write($row_count, 16, utf8_decode($row['abuelo']), $format_cell);
			$sum_precio_total += $row['ubaf_precio'];
			$sum_bienes += 1;
			$row_count++;
		}
		
		$worksheet->write($row_count+1, 12, 'Total Unidades', $format_head);
		$worksheet->write($row_count+1, 13, $sum_bienes, $format_cell);
		$worksheet->write($row_count+1, 15, 'Total Neto', $format_head);
		$worksheet->write($row_count+2, 15, 'IVA ('.$valor_iva.'%)', $format_head);
		$worksheet->write($row_count+3, 15, 'Total', $format_head);
		$worksheet->write($row_count+1, 16, $sum_precio_total, $format_cell);
		$worksheet->write($row_count+2, 16, number_format(round(($sum_precio_total * $valor_iva) / 100), 0, ',', '.'), $format_cell);
		$worksheet->write($row_count+3, 16, $sum_precio_total, $format_cell);
		
		ob_clean();
		$ceco_nombre = str_replace(" ", "_", $info_cc['CentroCosto']['ceco_nombre']);
		$workbook->send('Stock_Activos_Fijos_'.$ceco_nombre."_".date('d_m_Y_H_i_s').'.xls');
		$workbook->close();
	}
	
	function productos() {
		$tipos_bienes = $this->TipoBien->find('list', array('fields' => array('tibi_id', 'tibi_nombre')));
		$this->set('tipos_bienes', $tipos_bienes);
	}
	
	function productos_excel($tibi_id) {
		$this->layout = 'ajax';
		
		$conditions = array();
		if ($tibi_id != 'null') {
			$conditions = array('Producto.tibi_id' => $tibi_id);
		}
		
		$this->Producto->recursive = 2;
		$info = $this->Producto->find('all', array('order' => 'Producto.prod_nombre', 'conditions' => $conditions));
		$workbook = new Spreadsheet_Excel_Writer();		
		$worksheet = $workbook->addWorksheet('Libro 1');
		$worksheet->setOutline(true, true, true, true);
		
		// Cabeceras de tabla
		$format_head =& $workbook->addFormat();
		$format_head->setFgColor('yellow');
		$format_head->setBorder(1);
		
		$worksheet->write(10, 0, 'ID Producto', $format_head);
		$worksheet->write(10, 1, 'Código', $format_head);
		$worksheet->write(10, 2, 'Nombre', $format_head);
		$worksheet->write(10, 3, 'Familia', $format_head);
		$worksheet->write(10, 4, 'Grupo', $format_head);
		$worksheet->write(10, 5, 'Tipo de Bien', $format_head);
		$worksheet->write(10, 6, 'Tipo de Unidad', $format_head);
		
		// Formato de celdas
		$format_cell = $workbook->addFormat();
		$format_cell->setBorder(1);
		$row_count = 11;
		
		$worksheet->write(8, 3, "CATALOGOS DE PRODUCTOS");
		$worksheet->write(9, 6, date("d-m-Y H:i:s"));
		
		foreach ($info as $row) {
			$worksheet->write($row_count, 0, $row['Producto']['prod_id'], $format_cell);
			$worksheet->writeString($row_count, 1, utf8_decode($row['Producto']['prod_codigo']), $format_cell);
			$worksheet->write($row_count, 2, utf8_decode($row['Producto']['prod_nombre']), $format_cell);
			$worksheet->write($row_count, 3, utf8_decode($row['Grupo']['Familia']['fami_nombre']), $format_cell);
			$worksheet->write($row_count, 4, utf8_decode($row['Grupo']['grup_nombre']), $format_cell);
			$worksheet->write($row_count, 5, utf8_decode($row['TipoBien']['tibi_nombre']), $format_cell);
			$worksheet->write($row_count, 6, utf8_decode($row['Unidad']['unid_nombre']), $format_cell);
			$row_count++;
		}
		
		ob_clean();
		$workbook->send('Productos_'.date('d_m_Y_H_i_s').'.xls');
		$workbook->close();
	}
	
	function productos_pdf($tibi_id) {
		$this->layout = "ajax";
		$logo = $this->Configuracion->obtieneLogo();
		$fp = @fopen($_SERVER['DOCUMENT_ROOT']."/app/webroot/files/logo.png", "w");
		
		if($fp==true){
			fputs($fp, base64_decode($logo));
			fclose($fp);
			
			try {
				$pdf = new HTML_ToPDF("http://".$_SERVER['HTTP_HOST']."/reportes/productos_pdf_html/".$tibi_id, "http://".$_SERVER['HTTP_HOST']);
				$pdf->setUnderlineLinks(true);
				$pdf->setScaleFactor('0.8');
				$pdf->setUseColor(true);
				$pdf->setFooter('center', 'Página $N');
				$pdf->setHeader('center', '&nbsp;');
				$pdf->setUseCss(false);
				$pdf->setAdditionalCSS('.body_pdf {margin:0; padding:0;}');
				
				$fp = fopen($pdf->convert(), "r");
				
				header("Content-type: application/pdf; name=Productos_".date('d_m_Y_H_i_s').".pdf");
				header("Content-disposition: attachment; filename=Productos_".date('d_m_Y_H_i_s').".pdf");
				
				if (rewind($fp)) {
					fpassthru($fp);
				}
				fclose($fp);
				
			} catch (HTML_ToPDFException $e) {
				echo $e->getMessage();
			}
		}else{
			$this->Session->setFlash(__('No se puede generar el logo para el reporte', true));
			$this->redirect(array('action' => 'productos'));
		}
	}
	
	function productos_pdf_html($tibi_id) {
		$this->layout = 'ajax';
		
		$conditions = array();
		if ($tibi_id != 'null') {
			$conditions = array('Producto.tibi_id' => $tibi_id);
		}
		
		$this->Producto->recursive = 2;
		$info = $this->Producto->find('all', array('order' => 'Producto.prod_nombre', 'conditions' => $conditions));
		$this->set('info', $info);
	}
	
	function existencias() {
		$centros_costos = $this->Session->read('userdata.selectCC');
		$this->set('centros_costos', $centros_costos);
	}
	
	function activos_fijos() {
		$centros_costos = $this->Session->read('userdata.selectCC');
		$this->set('centros_costos', $centros_costos);
	}
	
	function activos_fijos_general() {
		$centros_costos = $this->Session->read('userdata.selectCC');
		$this->set('centros_costos', $centros_costos);
	}
	
	function existencias_excel($ceco_id, $prod_id) {
		$this->layout = 'ajax';
		$conditions = array("ceco_id" => $ceco_id);
		
		// sacamos info del centro de costo
		$info_cc = $this->CentroCosto->read(null, $ceco_id);
		
		// condicionamos filtro de producto
		if ($prod_id != 'null') {
			$conditions['prod_id'] = $prod_id;
		}
		$info = $this->Reporte->existencias(array('conditions' => $conditions));
		$param_iva = $this->Configuracion->find('first', array('conditions' => array('Configuracion.conf_id' => 'param_iva')));
		
		if (sizeof($param_iva) > 0 && is_array($param_iva)) {
			$valor_iva = $param_iva['Configuracion']['conf_valor'];
		} else {
			$valor_iva = 0;
		}
		
		$this->Producto->recursive = 2;
		$workbook = new Spreadsheet_Excel_Writer();		
		$worksheet = $workbook->addWorksheet('Libro 1');
		$worksheet->setOutline(true, true, true, true);
		
		// Cabeceras de tabla
		$format_head =& $workbook->addFormat();
		$format_head->setFgColor('yellow');
		$format_head->setBorder(1);

		$worksheet->write(10, 0, 'Nombre', $format_head);

		$worksheet->write(10, 1, 'Codigo', $format_head);
		$worksheet->write(10, 2, 'Tipo de Bien', $format_head);
		$worksheet->write(10, 3, 'Familia', $format_head);
		$worksheet->write(10, 4, 'Grupo', $format_head);
		$worksheet->write(10, 5, 'Stock Critico (CS/CC)', $format_head);
		$worksheet->write(10, 6, 'Serie', $format_head);
		$worksheet->write(10, 7, 'Fecha Vencimiento', $format_head);
		$worksheet->write(10, 8, 'Precio', $format_head);
		$worksheet->write(10, 9, 'Stock Total', $format_head);
		$worksheet->write(10, 10, 'Precio Total', $format_head);
		
		// Formato de celdas
		$format_cell = $workbook->addFormat();
		$format_cell->setBorder(1);
		$row_count = 11;
		
		$worksheet->write(8, 3, "REPORTE DE EXISTENCIA ".utf8_decode($info_cc['CentroCosto']['ceco_nombre']));
		$worksheet->write(9, 10, date("d-m-Y H:i:s"));
		
		$sum_precio_total = 0;
		
		foreach ($info as $row) {
			$row = array_pop($row);
			$worksheet->write($row_count, 0, utf8_decode($row['prod_nombre']), $format_cell);
			$worksheet->writeString($row_count, 1, utf8_decode($row['prod_codigo']), $format_cell);
			$worksheet->write($row_count, 2, utf8_decode($row['tibi_nombre']), $format_cell);
			$worksheet->write($row_count, 3, utf8_decode($row['fami_nombre']), $format_cell);
			$worksheet->write($row_count, 4, utf8_decode($row['grup_nombre']), $format_cell);
			$worksheet->write($row_count, 5, $row['stcc_stock_critico'], $format_cell);
			$worksheet->write($row_count, 6, utf8_decode($row['deex_serie']), $format_cell);
			$worksheet->write($row_count, 7, date("d-m-Y", strtotime ($row['deex_fecha_vencimiento'])), $format_cell);
			$worksheet->write($row_count, 8, utf8_decode($row['deex_precio']), $format_cell);
			$worksheet->write($row_count, 9, $row['total_stock'], $format_cell);
			$worksheet->write($row_count, 10, $row['deex_precio']*$row['total_stock'], $format_cell);
			$sum_precio_total += $row['deex_precio']*$row['total_stock'];
			$row_count++;
		}
		
		$worksheet->write($row_count+1, 9, 'Total Neto', $format_head);
		$worksheet->write($row_count+2, 9, 'IVA ('.$valor_iva.'%)', $format_head);
		$worksheet->write($row_count+3, 9, 'Total', $format_head);
		$worksheet->write($row_count+1, 10, $sum_precio_total, $format_cell);
		$worksheet->write($row_count+2, 10, number_format(round(($sum_precio_total * $valor_iva) / 100), 0, ',', '.'), $format_cell);
		$worksheet->write($row_count+3, 10, $sum_precio_total, $format_cell);
		
		ob_clean();
		$ceco_nombre = str_replace(" ", "_", $info_cc['CentroCosto']['ceco_nombre']);
		$workbook->send('Existencias_'.$ceco_nombre."_".date('d_m_Y_H_i_s').'.xls');
		$workbook->close();
	}
	
	function existencias_pdf($ceco_id, $prod_id) {
		$this->layout = "ajax";
		if (!$ceco_id) {
			$this->Session->setFlash(__('ID invalido', true));
			$this->redirect(array('action'=>'index_entrada'));
		}
		
		// sacamos info del centro de costo
		$conditions = array("ceco_id" => $ceco_id);
		$info_cc = $this->CentroCosto->read(null, $ceco_id);
		
		$logo = $this->Configuracion->obtieneLogo();
		$fp = @fopen($_SERVER['DOCUMENT_ROOT']."/app/webroot/files/logo.png", "w");
		
		if($fp==true){
			fputs($fp, base64_decode($logo));
			fclose($fp);
			
			try {
				$pdf = new HTML_ToPDF("http://".$_SERVER['HTTP_HOST']."/reportes/existencias_pdf_html/".$ceco_id."/".$prod_id, "http://".$_SERVER['HTTP_HOST']);
				$pdf->setUnderlineLinks(true);
				$pdf->setScaleFactor('0.8');
				$pdf->setUseColor(true);
				$pdf->setFooter('center', 'Página $N');
				$pdf->setHeader('center', '&nbsp;');
				$pdf->setUseCss(true);
				$fp = fopen($pdf->convert(), "r");
				
				$ceco_nombre = str_replace(" ", "_", $info_cc['CentroCosto']['ceco_nombre']);
				
				header("Content-type: application/pdf; name=Existencia_".$ceco_nombre."_".date('d_m_Y_H_i_s').".pdf");
				header("Content-disposition: attachment; filename=Existencia_".$ceco_nombre."_".date('d_m_Y_H_i_s').".pdf");
				
				if (rewind($fp)) {
					fpassthru($fp);
				}
				fclose($fp);
				
			} catch (HTML_ToPDFException $e) {
				echo $e->getMessage();
			}
		}else{
			$this->Session->setFlash(__('No se puede generar el logo para el reporte', true));
			$this->redirect(array('action' => 'existencias'));
		}
	}
	
	function existencias_pdf_html($ceco_id, $prod_id) {
		$this->layout = 'ajax';
		$conditions = array("ceco_id" => $ceco_id);
		
		// sacamos info del centro de costo
		$info_cc = $this->CentroCosto->read(null, $ceco_id);
		
		// condicionamos filtro de producto
		if ($prod_id != 'null') {
			$conditions['prod_id'] = $prod_id;
		}
		
		$info = $this->Reporte->existencias(array('conditions' => $conditions));
		$param_iva = $this->Configuracion->find('first', array('conditions' => array('Configuracion.conf_id' => 'param_iva')));
		
		if (sizeof($param_iva) > 0 && is_array($param_iva)) {
			$valor_iva = $param_iva['Configuracion']['conf_valor'];
		} else {
			$valor_iva = 0;
		}
		
		$this->set('info', $info);
		$this->set('info_cc', $info_cc);
		$this->set('valor_iva', $valor_iva);
	}
	
	function activos_fijos_pdf($ceco_id, $prod_id) {
		$this->layout = "ajax";
		if (!$ceco_id) {
			$this->Session->setFlash(__('ID invalido', true));
			$this->redirect(array('action'=>'index_entrada'));
		}
		
		// sacamos info del centro de costo
		$conditions = array("ceco_id" => $ceco_id);
		$info_cc = $this->CentroCosto->read(null, $ceco_id);
		
		$logo = $this->Configuracion->obtieneLogo();
		$fp = @fopen($_SERVER['DOCUMENT_ROOT']."/app/webroot/files/logo.png", "w");
		
		if($fp==true){
			fputs($fp, base64_decode($logo));
			fclose($fp);
			
			try {
				$pdf = new HTML_ToPDF("http://".$_SERVER['HTTP_HOST']."/reportes/activos_fijos_pdf_html/".$ceco_id."/".$prod_id, "http://".$_SERVER['HTTP_HOST']);
				$pdf->setUnderlineLinks(true);
				$pdf->setScaleFactor('0.8');
				$pdf->setUseColor(true);
				$pdf->setFooter('center', 'Página $N');
				$pdf->setHeader('center', '&nbsp;');
				$pdf->setUseCss(true);
				$fp = fopen($pdf->convert(), "r");
				
				$ceco_nombre = str_replace(" ", "_", $info_cc['CentroCosto']['ceco_nombre']);
				
				header("Content-type: application/pdf; name=Activo_Fijo_".$ceco_nombre."_".date('d_m_Y_H_i_s').".pdf");
				header("Content-disposition: attachment; filename=Activo_Fijo_".$ceco_nombre."_".date('d_m_Y_H_i_s').".pdf");
				
				if (rewind($fp)) {
					fpassthru($fp);
				}
				fclose($fp);
				
			} catch (HTML_ToPDFException $e) {
				echo $e->getMessage();
			}
		}else{
			$this->Session->setFlash(__('No se puede generar el logo para el reporte', true));
			$this->redirect(array('action' => 'existencias'));
		}
	}
	
	function activos_fijos_pdf_html($ceco_id, $prod_id = null) {
		$this->layout = 'ajax';
		$hijos = $this->ccArrayToCcVector($this->CentroCosto->findAllChildren($ceco_id));
		$conditions = array("ceco_id" => $hijos);
		
		// sacamos info del centro de costo
		$info_cc = $this->CentroCosto->read(null, $ceco_id);
		
		// condicionamos filtro de producto
		if ($prod_id != 'null') {
			$conditions['prod_id'] = $prod_id;
		}
		
		$info = $this->Reporte->activos_fijos(array('conditions' => $conditions));
		$param_iva = $this->Configuracion->find('first', array('conditions' => array('Configuracion.conf_id' => 'param_iva')));
		
		if (sizeof($param_iva) > 0 && is_array($param_iva)) {
			$valor_iva = $param_iva['Configuracion']['conf_valor'];
		} else {
			$valor_iva = 0;
		}
		
		$this->set('info', $info);
		$this->set('info_cc', $info_cc);
		$this->set('valor_iva', $valor_iva);
	}
	
	function activos_fijos_excel($ceco_id, $prod_id) {
		$this->layout = 'ajax';
		
		$hijos = $this->ccArrayToCcVector($this->CentroCosto->findAllChildren($ceco_id));
		$conditions = array("ceco_id" => $hijos);
	
		// sacamos info del centro de costo
		$info_cc = $this->CentroCosto->read(null, $ceco_id);
		
		// condicionamos filtro de producto
		if ($prod_id != 'null') {
			$conditions['prod_id'] = $prod_id;
		}
		$info = $this->Reporte->activos_fijos(array('conditions' => $conditions));

		$param_iva = $this->Configuracion->find('first', array('conditions' => array('Configuracion.conf_id' => 'param_iva')));
		
		if (sizeof($param_iva) > 0 && is_array($param_iva)) {
			$valor_iva = $param_iva['Configuracion']['conf_valor'];
		} else {
			$valor_iva = 0;
		}
		
		$this->Producto->recursive = 2;
		$workbook = new Spreadsheet_Excel_Writer();		
		$worksheet = $workbook->addWorksheet('Libro 1');
		$worksheet->setOutline(true, true, true, true);
		
		// Cabeceras de tabla
		$format_head =& $workbook->addFormat();
		$format_head->setFgColor('yellow');
		$format_head->setBorder(1);

		$worksheet->write(10, 0, 'Nombre', $format_head);
		$worksheet->write(10, 1, 'Tipo de Bien', $format_head);
		$worksheet->write(10, 2, 'Familia', $format_head);
		$worksheet->write(10, 3, 'Grupo', $format_head);
		$worksheet->write(10, 4, 'Precio', $format_head);
		$worksheet->write(10, 5, 'Propiedad', $format_head);
		$worksheet->write(10, 6, 'Situación', $format_head);
		$worksheet->write(10, 7, 'Marca', $format_head);
		$worksheet->write(10, 8, 'Color', $format_head);
		$worksheet->write(10, 9, 'Modelo', $format_head);
		$worksheet->write(10, 10, 'Serie', $format_head);
		$worksheet->write(10, 11, 'Centro de Costo (Unidad)', $format_head);
		$worksheet->write(10, 12, 'Dependencia', $format_head);
		$worksheet->write(10, 13, 'Establecimiento', $format_head);
		$worksheet->write(10, 14, 'Stock Total', $format_head);
		$worksheet->write(10, 15, 'Valor Total', $format_head);
		
		
		// Formato de celdas
		$format_cell = $workbook->addFormat();
		$format_cell->setBorder(1);
		$row_count = 11;
		
		$worksheet->write(8, 3, "REPORTE DE ACTIVOS FIJOS ".utf8_decode($info_cc['CentroCosto']['ceco_nombre']));
		$worksheet->write(9, 14, date("d-m-Y H:i:s"));
		
		$sum_precio_total = 0;
		$sum_bienes = 0;
		
		foreach ($info as $row) {
			$row = array_pop($row);
			$worksheet->write($row_count, 0, utf8_decode($row['prod_nombre']), $format_cell);
			$worksheet->write($row_count, 1, utf8_decode($row['tibi_nombre']), $format_cell);
			$worksheet->write($row_count, 2, utf8_decode($row['fami_nombre']), $format_cell);
			$worksheet->write($row_count, 3, utf8_decode($row['grup_nombre']), $format_cell);
			$worksheet->write($row_count, 4, $row['ubaf_precio'], $format_cell);
			$worksheet->write($row_count, 5, utf8_decode($row['prop_nombre']), $format_cell);
			$worksheet->write($row_count, 6, utf8_decode($row['situ_nombre']), $format_cell);
			$worksheet->write($row_count, 7, utf8_decode($row['marc_nombre']), $format_cell);
			$worksheet->write($row_count, 8, utf8_decode($row['colo_nombre']), $format_cell);
			$worksheet->write($row_count, 9, utf8_decode($row['mode_nombre']), $format_cell);
			$worksheet->write($row_count, 10, utf8_decode($row['ubaf_serie']), $format_cell);
			$worksheet->write($row_count, 11, utf8_decode($row['hijo']), $format_cell);
			$worksheet->write($row_count, 12, utf8_decode($row['padre']), $format_cell);
			$worksheet->write($row_count, 13, utf8_decode($row['abuelo']), $format_cell);
			$worksheet->write($row_count, 14, $row['total'], $format_cell);
			$worksheet->write($row_count, 15, $row['ubaf_precio'] * $row['total'], $format_cell);
			$sum_precio_total += $row['ubaf_precio']*$row['total'];
			$sum_bienes += $row['total'];
			$row_count++;
		}
		
		$worksheet->write($row_count+1, 11, 'Total Unidades', $format_head);
		$worksheet->write($row_count+1, 12, $sum_bienes, $format_cell);
		$worksheet->write($row_count+1, 14, 'Total Neto', $format_head);
		$worksheet->write($row_count+2, 14, 'IVA ('.$valor_iva.'%)', $format_head);
		$worksheet->write($row_count+3, 14, 'Total', $format_head);
		$worksheet->write($row_count+1, 15, $sum_precio_total, $format_cell);
		$worksheet->write($row_count+2, 15, number_format(round(($sum_precio_total * $valor_iva) / 100), 0, ',', '.'), $format_cell);
		$worksheet->write($row_count+3, 15, $sum_precio_total, $format_cell);
		
		ob_clean();
		$ceco_nombre = str_replace(" ", "_", $info_cc['CentroCosto']['ceco_nombre']);
		$workbook->send('Activos_Fijos_'.$ceco_nombre."_".date('d_m_Y_H_i_s').'.xls');
		$workbook->close();
	}	
	
	function activos_fijos_general_pdf() {
		$this->layout = "ajax";
		
		$logo = $this->Configuracion->obtieneLogo();
		$fp = @fopen($_SERVER['DOCUMENT_ROOT']."/app/webroot/files/logo.png", "w");
		
		if($fp==true){
			fputs($fp, base64_decode($logo));
			fclose($fp);
			
			try {
				$pdf = new HTML_ToPDF("http://".$_SERVER['HTTP_HOST']."/reportes/activos_fijos_general_pdf_html", "http://".$_SERVER['HTTP_HOST']);
				$pdf->setUnderlineLinks(true);
				$pdf->setScaleFactor('0.8');
				$pdf->setUseColor(true);
				$pdf->setFooter('center', 'Página $N');
				$pdf->setHeader('center', '&nbsp;');
				$pdf->setUseCss(true);
				$fp = fopen($pdf->convert(), "r");
				
				ob_clean();
				header("Content-type: application/pdf; name=Activo_Fijo_General_".date('d_m_Y_H_i_s').".pdf");
				header("Content-disposition: attachment; filename=Activo_Fijo_General_".date('d_m_Y_H_i_s').".pdf");
				
				if (rewind($fp)) {
					fpassthru($fp);
				}
				fclose($fp);
				
			} catch (HTML_ToPDFException $e) {
				echo $e->getMessage();
			}
		} else {
			$this->Session->setFlash(__('No se puede generar el logo para el reporte', true));
			$this->redirect(array('action' => 'activos_fijos_general'));
		}
	}
	
	function activos_fijos_general_pdf_html() {
		$this->layout = 'ajax';
		
		// info general
		$info = $this->Reporte->activosFijosGeneral();
		$param_iva = $this->Configuracion->find('first', array('conditions' => array('Configuracion.conf_id' => 'param_iva')));
		
		if (sizeof($param_iva) > 0 && is_array($param_iva)) {
			$valor_iva = $param_iva['Configuracion']['conf_valor'];
		} else {
			$valor_iva = 0;
		}
		
		// agrupar por centro de costo
		$info_ = array();
		foreach ($info as $row) {
			$row = array_pop($row);
			$info_[$row['ceco_id']][] = $row;
		}
		
		$this->set('info', $info_);
		$this->set('valor_iva', $valor_iva);
	}
	
	function activos_fijos_general_excel($multiple, $ceco_id) {
		$this->layout = 'ajax';
		
		$cc_hijos = $this->ccArrayToCcVector($this->CentroCosto->findAllChildren($ceco_id));
		$ceco_id = $cc_hijos;

		// info general
		$info = $this->Reporte->activosFijosGeneral($ceco_id);
		$param_iva = $this->Configuracion->find('first', array('conditions' => array('Configuracion.conf_id' => 'param_iva')));
		
		if (sizeof($param_iva) > 0 && is_array($param_iva)) {
			$valor_iva = $param_iva['Configuracion']['conf_valor'];
		} else {
			$valor_iva = 0;
		}
		
		// agrupar por centro de costo
		$info_ = array();
		foreach ($info as $row) {
			$row = array_pop($row);
			$info_[$row['ceco_id']][] = $row;
		}
	
		$workbook = new Spreadsheet_Excel_Writer();
		
		// Formato de cabeceras
		$format_head = $workbook->addFormat();
		$format_head->setFgColor('yellow');
		$format_head->setBorder(1);
		
		// Formato de celdas
		$format_cell = $workbook->addFormat();
		$format_cell->setBorder(1);
		
		// para control de multiples hojas
		if ($multiple == 1) {
			foreach ($info_ as $ceco) {
				$ceco_nombre = trim($ceco[0]['ceco_nombre']);
				$ceco_id = $ceco[0]['ceco_id'];
				
				$worksheet = $workbook->addWorksheet();
				$worksheet->write(6, 0, "Ubicación");
				$worksheet->write(6, 1, utf8_decode($this->CentroCosto->findUbicacion($ceco_id)));
				
				$worksheet->write(8, 3, "REPORTE GENERAL DE ACTIVOS FIJOS ".utf8_decode($ceco_nombre));
				$worksheet->write(9, 10, date("d-m-Y H:i:s"));
				$worksheet->write(10, 0, 'Nombre', $format_head);
				$worksheet->write(10, 1, 'Tipo de Bien', $format_head);
				$worksheet->write(10, 2, 'Familia', $format_head);
				$worksheet->write(10, 3, 'Grupo', $format_head);
				$worksheet->write(10, 4, 'Precio', $format_head);
				$worksheet->write(10, 5, 'Propiedad', $format_head);
				$worksheet->write(10, 6, 'Situación', $format_head);
				$worksheet->write(10, 7, 'Marca', $format_head);
				$worksheet->write(10, 8, 'Color', $format_head);
				$worksheet->write(10, 9, 'Modelo', $format_head);
				$worksheet->write(10, 10, 'Serie', $format_head);
				$worksheet->write(10, 11, 'Stock Total', $format_head);
				$worksheet->write(10, 12, 'Valor Total', $format_head);
				$worksheet->write(10, 13, 'Centro de Costo (Unidad)', $format_head);
				$worksheet->write(10, 14, 'Dependencia', $format_head);
				$worksheet->write(10, 15, 'Establecimiento', $format_head);
				
				$row_count = 11;
				$sum_precio_total = 0;
				$suma_bienes = 0;
				
				foreach ($ceco as $row) {
					$worksheet->write($row_count, 0, utf8_decode($row['prod_nombre']), $format_cell);
					$worksheet->write($row_count, 1, utf8_decode($row['tibi_nombre']), $format_cell);
					$worksheet->write($row_count, 2, utf8_decode($row['fami_nombre']), $format_cell);
					$worksheet->write($row_count, 3, utf8_decode($row['grup_nombre']), $format_cell);
					$worksheet->write($row_count, 4, $row['ubaf_precio'], $format_cell);
					$worksheet->write($row_count, 5, utf8_decode($row['prop_nombre']), $format_cell);
					$worksheet->write($row_count, 6, utf8_decode($row['situ_nombre']), $format_cell);
					$worksheet->write($row_count, 7, utf8_decode($row['marc_nombre']), $format_cell);
					$worksheet->write($row_count, 8, utf8_decode($row['colo_nombre']), $format_cell);
					$worksheet->write($row_count, 9, utf8_decode($row['mode_nombre']), $format_cell);
					$worksheet->write($row_count, 10, utf8_decode($row['ubaf_serie']), $format_cell);
					$worksheet->write($row_count, 11, $row['total'], $format_cell);
					$worksheet->write($row_count, 12, $row['ubaf_precio'] * $row['total'], $format_cell);
					$worksheet->write($row_count, 13, utf8_decode($row['ceco_nombre']), $format_cell);
					$worksheet->write($row_count, 14, utf8_decode($row['dependencia']), $format_cell);
					$worksheet->write($row_count, 15, utf8_decode($row['establecimiento']), $format_cell);
					$sum_precio_total += $row['ubaf_precio']*$row['total'];
					$suma_bienes += $row['total'];
					$row_count++;
				}
				
				$worksheet->write($row_count+1, 11, 'Total Unidades', $format_head);
				$worksheet->write($row_count+1, 12, $suma_bienes, $format_cell);
				$worksheet->write($row_count+1, 14, 'Total Neto', $format_head);
				$worksheet->write($row_count+2, 14, 'IVA ('.$valor_iva.'%)', $format_head);
				$worksheet->write($row_count+3, 14, 'Total', $format_head);
				$worksheet->write($row_count+1, 15, $sum_precio_total, $format_cell);
				$worksheet->write($row_count+2, 15, number_format(round(($sum_precio_total * $valor_iva) / 100), 0, ',', '.'), $format_cell);
				$worksheet->write($row_count+3, 15, $sum_precio_total, $format_cell);
			}
		
		} else if ($multiple == 0) {
			// tomamos en cuenta el $info = $this->Reporte->activosFijosGeneral();
			// no el agrupado por centro de costo
			$worksheet = $workbook->addWorksheet();
			$worksheet->write(8, 3, "REPORTE GENERAL DE ACTIVOS FIJOS");
			$worksheet->write(9, 10, date("d-m-Y H:i:s"));
			$worksheet->write(10, 0, 'Nombre', $format_head);
			$worksheet->write(10, 1, 'Tipo de Bien', $format_head);
			$worksheet->write(10, 2, 'Familia', $format_head);
			$worksheet->write(10, 3, 'Grupo', $format_head);
			$worksheet->write(10, 4, 'Precio', $format_head);
			$worksheet->write(10, 5, 'Propiedad', $format_head);
			$worksheet->write(10, 6, 'Situación', $format_head);
			$worksheet->write(10, 7, 'Marca', $format_head);
			$worksheet->write(10, 8, 'Color', $format_head);
			$worksheet->write(10, 9, 'Modelo', $format_head);
			$worksheet->write(10, 10, 'Serie', $format_head);
			$worksheet->write(10, 11, 'Stock Total', $format_head);
			$worksheet->write(10, 12, 'Valor Total', $format_head);
			$worksheet->write(10, 13, 'Centro de Costo (Unidad)', $format_head);
			$worksheet->write(10, 14, 'Dependencia', $format_head);
			$worksheet->write(10, 15, 'Establecimiento', $format_head);
			$row_count = 11;
			$sum_precio_total = 0;
			$sum_bienes = 0;
			
			foreach ($info as $row) {
				$row = array_pop($row);
				$worksheet->write($row_count, 0, utf8_decode($row['prod_nombre']), $format_cell);
				$worksheet->write($row_count, 1, utf8_decode($row['tibi_nombre']), $format_cell);
				$worksheet->write($row_count, 2, utf8_decode($row['fami_nombre']), $format_cell);
				$worksheet->write($row_count, 3, utf8_decode($row['grup_nombre']), $format_cell);
				$worksheet->write($row_count, 4, $row['ubaf_precio'], $format_cell);
				$worksheet->write($row_count, 5, utf8_decode($row['prop_nombre']), $format_cell);
				$worksheet->write($row_count, 6, utf8_decode($row['situ_nombre']), $format_cell);
				$worksheet->write($row_count, 7, utf8_decode($row['marc_nombre']), $format_cell);
				$worksheet->write($row_count, 8, utf8_decode($row['colo_nombre']), $format_cell);
				$worksheet->write($row_count, 9, utf8_decode($row['mode_nombre']), $format_cell);
				$worksheet->write($row_count, 10, utf8_decode($row['ubaf_serie']), $format_cell);
				$worksheet->write($row_count, 11, $row['total'], $format_cell);
				$worksheet->write($row_count, 12, $row['ubaf_precio'] * $row['total'], $format_cell);
				$worksheet->write($row_count, 13, utf8_decode($row['ceco_nombre']), $format_cell);
				$worksheet->write($row_count, 14, utf8_decode($row['dependencia']), $format_cell);
				$worksheet->write($row_count, 15, utf8_decode($row['establecimiento']), $format_cell);
				$sum_precio_total += $row['ubaf_precio']*$row['total'];
				$sum_bienes += $row['total'];
				$row_count++;
			}
			
			$worksheet->write($row_count+1, 11, 'Total Unidades', $format_head);
			$worksheet->write($row_count+1, 12, $sum_bienes, $format_cell);
			$worksheet->write($row_count+1, 14, 'Total Neto', $format_head);
			$worksheet->write($row_count+2, 14, 'IVA ('.$valor_iva.'%)', $format_head);
			$worksheet->write($row_count+3, 14, 'Total', $format_head);
			$worksheet->write($row_count+1, 15, $sum_precio_total, $format_cell);
			$worksheet->write($row_count+2, 15, number_format(round(($sum_precio_total * $valor_iva) / 100), 0, ',', '.'), $format_cell);
			$worksheet->write($row_count+3, 15, $sum_precio_total, $format_cell);
		}
		
		ob_clean();
		$workbook->send('Activos_Fijos_General_'.date('d_m_Y_H_i_s').'.xls');
		$workbook->close();
	}
	
	function stock_pdf_activos_fijos($ceco_id, $tibi_id, $prod_id) {
		$this->layout = "ajax";
		if (!$ceco_id) {
			$this->Session->setFlash(__('ID invalido', true));
			$this->redirect(array('action'=>'index_entrada'));
		}
		
		// sacamos info del centro de costo
		$conditions = array("ceco_id" => $ceco_id);
		$info_cc = $this->CentroCosto->read(null, $ceco_id);
		
		$logo = $this->Configuracion->obtieneLogo();
		$fp = @fopen($_SERVER['DOCUMENT_ROOT']."/app/webroot/files/logo.png", "w");
		
		if($fp==true){
			fputs($fp, base64_decode($logo));
			fclose($fp);
			try {
				$pdf = new HTML_ToPDF("http://".$_SERVER['HTTP_HOST']."/reportes/stock_pdf_activos_fijos_html/".$ceco_id."/".$tibi_id."/".$prod_id, "http://".$_SERVER['HTTP_HOST']);
				$pdf->setUnderlineLinks(true);
				$pdf->setScaleFactor('0.8');
				$pdf->setUseColor(true);
				$pdf->setFooter('center', 'Página $N');
				$pdf->setHeader('center', '&nbsp;');
				$fp = fopen($pdf->convert(), "r");
				
				$ceco_nombre = str_replace(" ", "_", $info_cc['CentroCosto']['ceco_nombre']);
				
				header("Content-type: application/pdf; name=Stock_Activos_Fijos_".$ceco_nombre."_".date('d_m_Y_H_i_s').".pdf");
				header("Content-disposition: attachment; filename=Stock_Activos_Fijos_".$ceco_nombre."_".date('d_m_Y_H_i_s').".pdf");
				
				if (rewind($fp)) {
					fpassthru($fp);
				}
				fclose($fp);
				
			} catch (HTML_ToPDFException $e) {
				echo $e->getMessage();
			}
		}else{
			$this->Session->setFlash(__('No se puede generar el logo para el reporte', true));
			$this->redirect(array('action' => 'stock'));
		}
	}
	
	function stock_pdf_activos_fijos_html($ceco_id, $tibi_id, $prod_id) {
		$this->layout = 'ajax';
		$cc_hijos = $this->ccArrayToCcVector($this->CentroCosto->findAllChildren($ceco_id));
		$conditions = array("ceco_id" => $cc_hijos);
		
		// sacamos info del centro de costo
		$info_cc = $this->CentroCosto->read(null, $ceco_id);
		
		// condicionamos filtro de tipo de bien
		if ($tibi_id != 'null') {
			$conditions['tibi_id'] = $tibi_id;
		}
		
		// condicionamos filtro de producto
		if ($prod_id != 'null') {
			$conditions['prod_id'] = $prod_id;
		}
		
		$info = $this->Reporte->stock(array('conditions' => $conditions));
		$this->set('info', $info);
		$this->set('info_cc', $info_cc);
		
	}
	
	function plancheta_activo_fijo_pdf($ceco_id) {
		$this->layout = "ajax";		
		if (!$ceco_id) {
			$this->Session->setFlash(__('ID invalido', true));
			$this->redirect(array('action'=>'plancheta'));
		}
		
		ini_set('memory_limit', '2048M');
		// sacamos info del centro de costo
		$conditions = array("ceco_id" => $ceco_id);
		$info_cc = $this->CentroCosto->read(null, $ceco_id);
		
		$logo = $this->Configuracion->obtieneLogo();
		$fp = @fopen($_SERVER['DOCUMENT_ROOT']."/app/webroot/files/logo.png", "w");
		
		if($fp==true){
			fputs($fp, base64_decode($logo));			
			fclose($fp);				
						
			/*
			try {
				fputs($fp, base64_decode($logo));
				fclose($fp);
				
				$pdf = new HTML_ToPDF("http://".$_SERVER['HTTP_HOST']."/reportes/plancheta_activo_fijo_pdf_html/".$ceco_id, "http://".$_SERVER['HTTP_HOST']);
				$pdf->setUnderlineLinks(true);
				$pdf->setScaleFactor('0.8');
				$pdf->setUseColor(true);
				$pdf->setFooter('center', 'Página $N');
				$pdf->setHeader('center', '&nbsp;');
				$fp = fopen($pdf->convert(), "r");
				
				$ceco_nombre = str_replace(" ", "_", $info_cc['CentroCosto']['ceco_nombre']);
				
				header("Content-type: application/pdf; name=Plancheta_Activo_Fijo_".$ceco_nombre."_".date('d_m_Y_H_i_s').".pdf");
				header("Content-disposition: attachment; filename=Plancheta_Activo_Fijo_".$ceco_nombre."_".date('d_m_Y_H_i_s').".pdf");
				
				if (rewind($fp)) {
					fpassthru($fp);
				}
				fclose($fp);
					
			} catch (HTML_ToPDFException $e) {
				echo $e->getMessage();
			}
			*/
			
			try {					
				$html = file_get_contents("http://".$_SERVER['HTTP_HOST']."/reportes/plancheta_activo_fijo_pdf_html/".$ceco_id);				
				$dompdf = new DOMPDF();
				$dompdf->load_html($html);
				$dompdf->render();									
				$dompdf->stream("Plancheta_Activo_Fijo_".date('d_m_Y_H_i_s').".pdf");				
								
			} catch (DOMPDF_Exception $e) {
				echo $e->getMessage();
			}

		} else {
			$this->Session->setFlash(__('No se puede generar el logo para el reporte', true));
			$this->redirect(array('action' => 'stock'));
		}
	}
	
	function plancheta_activo_fijo_pdf_html($ceco_id) {
		$this->layout = 'ajax';
		ini_set('memory_limit', '2048M');
		// sacamos info del centro de costo
		$info_cc = $this->CentroCosto->read(null, $ceco_id);
		
		// obtenemos activos fijos
		$activos_fijos = $this->UbicacionActivoFijo->generar_plancheta_af($ceco_id);
		$conditions = array("CentroCosto.ceco_id" => $ceco_id, "Responsable.esre_id" => 1);
		$responsables = $this->Responsable->find('all', array('order' => 'Responsable.resp_id', 'conditions' => $conditions));
		$ubicacion = $this->Responsable->CentroCosto->findUbicacion($ceco_id);
		$busca_responsables = $this->Responsable->CentroCosto->buscaResponsable($ceco_id);
		
		$this->set('info_cc', $info_cc);
		$this->set('responsables', $responsables);
		$this->set('activos_fijos', $activos_fijos);
		$this->set('ubicacion', $ubicacion);
		$this->set('busca_responsables', $busca_responsables);
		ob_clean();
	}
	
	function transito() {
		$tipos_bienes = $this->TipoBien->find('list', array('fields' => array('tibi_id', 'tibi_nombre')));
		$this->set('tipos_bienes', $tipos_bienes);
	}
	
	function transito_excel($tibi_id) {
		$this->layout = 'ajax';
		$workbook = new Spreadsheet_Excel_Writer();		
		$worksheet = $workbook->addWorksheet('Libro 1');
		$worksheet->setOutline(true, true, true, true);
	
		// Cabeceras de tabla
		$format_head = $workbook->addFormat();
		$format_head->setFgColor('yellow');
		$format_head->setBorder(1);
		
		// Formato de celdas
		$format_cell = $workbook->addFormat();
		$format_cell->setBorder(1);
		
		if ($tibi_id == 1) {
			$info = $this->ActivoFijo->itemsTransito();
			
			$worksheet->write(10, 0, 'ID Producto', $format_head);
			$worksheet->write(10, 1, 'Código', $format_head);
			$worksheet->write(10, 2, 'Nombre', $format_head);
			$worksheet->write(10, 3, 'Tipo de Bien', $format_head);
			$worksheet->write(10, 4, 'Propiedad', $format_head);
			$worksheet->write(10, 5, 'Situación', $format_head);
			$worksheet->write(10, 6, 'Marca', $format_head);
			$worksheet->write(10, 7, 'Color', $format_head);
			$worksheet->write(10, 8, 'Fecha de Garantía', $format_head);
			$worksheet->write(10, 9, 'Precio', $format_head);
			$worksheet->write(10, 10, '¿Es Depreciable?', $format_head);
			$worksheet->write(10, 11, 'Vida Útil', $format_head);
			$worksheet->write(10, 12, 'Tipo de Movimiento', $format_head);
			$worksheet->write(10, 13, 'Centro de Costo Padre', $format_head);
			$worksheet->write(10, 14, 'Centro de Costo', $format_head);
			$worksheet->write(10, 15, 'Centro de Costo Hijo', $format_head);
		
			$row_count = 11;
			$worksheet->write(7, 3, "REPORTE DE TRANSITO DE ACTIVOS FIJOS (ITEMS NO RECEPCIONADOS)");
			$worksheet->write(9, 15, date("d-m-Y H:i:s"));
		
			foreach ($info as $row) {
				$row = array_pop($row);
				$worksheet->write($row_count, 0, $row['prod_id'], $format_cell);
				$worksheet->writeString($row_count, 1, $row['deaf_codigo'], $format_cell);
				$worksheet->write($row_count, 2, utf8_decode($row['prod_nombre']), $format_cell);
				$worksheet->write($row_count, 3, utf8_decode($row['tibi_nombre']), $format_cell);
				$worksheet->write($row_count, 4, utf8_decode($row['prop_nombre']), $format_cell);
				$worksheet->write($row_count, 5, utf8_decode($row['situ_nombre']), $format_cell);
				$worksheet->write($row_count, 6, utf8_decode($row['marc_nombre']), $format_cell);
				$worksheet->write($row_count, 7, utf8_decode($row['colo_nombre']), $format_cell);
			
				if (isset($row['deaf_fecha_garantia'])) {
					$row['deaf_fecha_garantia'] = date("d-m-Y", strtotime($row['deaf_fecha_garantia']));
				} else {
					$row['deaf_fecha_garantia'] = null;
				}
			
				$worksheet->write($row_count, 8, $row['deaf_fecha_garantia'], $format_cell);
				$worksheet->write($row_count, 9, $row['deaf_precio'], $format_cell);
				
				if (isset($row['deaf_depreciable'])) {
					if ($row['deaf_depreciable'] == 1) {
						$row['deaf_depreciable'] = "Si";
					} else {
						$row['deaf_depreciable'] = "No";
					}
				} else {
					$row['deaf_depreciable'] = null;
				}
			
				$worksheet->write($row_count, 10, $row['deaf_depreciable'], $format_cell);
				$worksheet->write($row_count, 11, $row['deaf_vida_util'], $format_cell);
				$worksheet->write($row_count, 12, $row['tmov_descripcion'], $format_cell);
				$worksheet->write($row_count, 13, $row['ceco_padre'], $format_cell);
				$worksheet->write($row_count, 14, $row['ceco_nombre'], $format_cell);
				$worksheet->write($row_count, 15, $row['ceco_hijo'], $format_cell);
				$row_count++;
			}
			
			ob_clean();
			$workbook->send('Transitos_no_recepcionados_activos_fijos_'.date('d_m_Y_H_i_s').'.xls');
			$workbook->close();
			
		} else if ($tibi_id == 3) {
			$info = $this->Existencia->itemsTransito();
			
			$worksheet->write(10, 0, 'ID Producto', $format_head);
			$worksheet->write(10, 1, 'Código', $format_head);
			$worksheet->write(10, 2, 'Nombre', $format_head);
			$worksheet->write(10, 3, 'Tipo de Bien', $format_head);
			$worksheet->write(10, 4, 'Familia', $format_head);
			$worksheet->write(10, 5, 'Grupo', $format_head);
			$worksheet->write(10, 6, 'Cantidad', $format_head);
			$worksheet->write(10, 7, 'Precio', $format_head);
			$worksheet->write(10, 8, 'Serie', $format_head);
			$worksheet->write(10, 9, 'Fecha de Vencimiento', $format_head);
			$worksheet->write(10, 10, 'Tipo de Movimiento', $format_head);
			$worksheet->write(10, 11, 'Centro de Costo Padre', $format_head);
			$worksheet->write(10, 12, 'Centro de Costo', $format_head);
			$worksheet->write(10, 13, 'Centro de Costo Hijo', $format_head);
			
			$row_count = 11;
			$worksheet->write(7, 3, "REPORTE DE TRANSITO DE EXISTENCIAS (ITEMS NO RECEPCIONADOS)");
			$worksheet->write(9, 13, date("d-m-Y H:i:s"));
			
			foreach ($info as $row) {
				$row = array_pop($row);
				$worksheet->write($row_count, 0, $row['prod_id'], $format_cell);
				$worksheet->writeString($row_count, 1, $row['prod_codigo'], $format_cell);
				$worksheet->write($row_count, 2, utf8_decode($row['prod_nombre']), $format_cell);
				$worksheet->write($row_count, 3, utf8_decode($row['tibi_nombre']), $format_cell);
				$worksheet->write($row_count, 4, utf8_decode($row['fami_nombre']), $format_cell);
				$worksheet->write($row_count, 5, utf8_decode($row['grup_nombre']), $format_cell);
				$worksheet->write($row_count, 6, $row['deex_cantidad'], $format_cell);
				$worksheet->write($row_count, 7, $row['deex_precio'], $format_cell);
				$worksheet->writeString($row_count, 8, utf8_decode($row['deex_serie']), $format_cell);
				$worksheet->write($row_count, 9, date("d-m-Y", strtotime($row['deex_fecha_vencimiento'])), $format_cell);
				$worksheet->write($row_count, 10, utf8_decode($row['tmov_descripcion']), $format_cell);
				$worksheet->write($row_count, 11, utf8_decode($row['ceco_padre']), $format_cell);
				$worksheet->write($row_count, 12, utf8_decode($row['ceco_nombre']), $format_cell);
				$worksheet->write($row_count, 13, utf8_decode($row['ceco_hijo']), $format_cell);
				$row_count++;
			}
			
			ob_clean();
			$workbook->send('Transitos_no_recepcionados_existencias_'.date('d_m_Y_H_i_s').'.xls');
			$workbook->close();
		}
	}
	
	function transito_pdf($tibi_id) {
		$this->layout = 'ajax';
		
		if (!$tibi_id) {
			$this->Session->setFlash(__('ID invalido', true));
			$this->redirect(array('action' => 'transito'));
		}
		
		$logo = $this->Configuracion->obtieneLogo();
		$fp = @fopen($_SERVER['DOCUMENT_ROOT']."/app/webroot/files/logo.png", "w");
		
		if($fp==true) {
			try {
				fputs($fp, base64_decode($logo));
				fclose($fp);
		
				$url = "http://".$_SERVER['HTTP_HOST']."/reportes/transito_pdf_html/".$tibi_id;
				$pdf = new HTML_ToPDF($url, "http://".$_SERVER['HTTP_HOST']);
				$pdf->setUnderlineLinks(true);
				$pdf->setScaleFactor('0.8');
				$pdf->setUseColor(true);
				$pdf->setFooter('center', 'Página $N');
				$pdf->setHeader('center', '&nbsp;');
				$fp = fopen($pdf->convert(), "r");
				
				if ($tibi_id == 1) {
					$nom_arch = "Transitos_no_recepcionados_activos_fijos";
				} elseif ($tibi_id == 3) {
					$nom_arch = "Transitos_no_recepcionados_existencias";
				}
				
				header("Content-type: application/pdf; name=".$nom_arch."_".date('d_m_Y_H_i_s').".pdf");
				header("Content-disposition: attachment; filename=".$nom_arch."_".date('d_m_Y_H_i_s').".pdf");
				
				if (rewind($fp)) {
					fpassthru($fp);
				}
				fclose($fp);
				
			} catch (HTML_ToPDFException $e) {
				echo $e->getMessage();
			}
		}
	}
	
	function transito_pdf_html($tibi_id) {
		$this->layout = 'ajax';
		
		if ($tibi_id == 1) {
			$info = $this->ActivoFijo->itemsTransito();			
		} else if ($tibi_id == 3) {
			$info = $this->Existencia->itemsTransito();
		}
		
		$this->set('info', $info);
		$this->set('tibi_id', $tibi_id);
	}
	
	function gastos_cta_contable() {
		if (!empty($this->data)) {
			$cuco_id = $this->data['Reporte']['cuco_id'];
			$ceco_id = $this->data['Reporte']['ceco_id'];
			
			if (empty($ceco_id)) {
				$ceco_id = $this->data['Reporte']['ceco_id'];
			} else if (!empty($ceco_id)){
				$hijos = $this->ccArrayToCcVector($this->CentroCosto->findAllChildren($ceco_id));
				$ceco_id = $hijos;
			}
			
			$info = $this->CuentaContable->gastosCtaContable($cuco_id, $ceco_id);			
			$info_cuco = $this->CuentaContable->find('first', array('conditions' => array('cuco_id' => $cuco_id)));
			$cuco_nombre = $info_cuco['CuentaContable']['cuco_nombre'];			
			$param_iva = $this->Configuracion->find('first', array('conditions' => array('Configuracion.conf_id' => 'param_iva')));
		
			if (sizeof($param_iva) > 0 && is_array($param_iva)) {
				$valor_iva = $param_iva['Configuracion']['conf_valor'];
			} else {
				$valor_iva = 0;
			}
			
			$workbook = new Spreadsheet_Excel_Writer();
			
			// Cabeceras de tabla
			$format_head = $workbook->addFormat();
			$format_head->setFgColor('yellow');
			$format_head->setBorder(1);
		
			// Formato de celdas
			$format_cell = $workbook->addFormat();
			$format_cell->setBorder(1);
			
			foreach ($info as $key => $val) {
				if (sizeof($val) > 0) {
					$worksheet = $workbook->addWorksheet($key);
					$worksheet->setOutline(true, true, true, true);
					
					if ($key == "ACTIVOS FIJOS") {
						$worksheet->write(10, 0, 'ID Producto', $format_head);
						$worksheet->write(10, 1, 'Código', $format_head);
						$worksheet->write(10, 2, 'Nombre', $format_head);
						$worksheet->write(10, 3, 'Grupo', $format_head);
						$worksheet->write(10, 4, 'Familia', $format_head);
						$worksheet->write(10, 5, 'Tipo de Bien', $format_head);
						$worksheet->write(10, 6, 'Propiedad', $format_head);
						$worksheet->write(10, 7, 'Situación', $format_head);
						$worksheet->write(10, 8, 'Marca', $format_head);
						$worksheet->write(10, 9, 'Color', $format_head);
						$worksheet->write(10, 10, 'Fecha de Garantía', $format_head);
						$worksheet->write(10, 11, 'Precio', $format_head);
						$worksheet->write(10, 12, '¿Es Depreciable?', $format_head);
						$worksheet->write(10, 13, 'Vida Útil', $format_head);
						$worksheet->write(10, 14, 'Centro de Costo (Unidad)', $format_head);
						$worksheet->write(10, 15, 'Dependencia', $format_head);
						$worksheet->write(10, 16, 'Establecimiento', $format_head);
		
						$row_count = 11;
						
						$worksheet->write(7, 3, "GASTOS DE ACTIVOS FIJOS PARA CUENTA CONTABLE ".$cuco_nombre);
						$worksheet->write(9, 12, date("d-m-Y H:i:s"));
						$total = 0;
						$sum_bienes = 0;
						
						foreach ($val as $row) {
							$row = array_pop($row);
							$worksheet->write($row_count, 0, $row['prod_id'], $format_cell);
							$worksheet->writeString($row_count, 1, $row['ubaf_codigo'], $format_cell);
							$worksheet->write($row_count, 2, utf8_decode($row['prod_nombre']), $format_cell);
							$worksheet->write($row_count, 3, utf8_decode($row['grup_nombre']), $format_cell);
							$worksheet->write($row_count, 4, utf8_decode($row['fami_nombre']), $format_cell);
							$worksheet->write($row_count, 5, utf8_decode($row['tibi_nombre']), $format_cell);
							$worksheet->write($row_count, 6, utf8_decode($row['prop_nombre']), $format_cell);
							$worksheet->write($row_count, 7, utf8_decode($row['situ_nombre']), $format_cell);
							$worksheet->write($row_count, 8, utf8_decode($row['marc_nombre']), $format_cell);
							$worksheet->write($row_count, 9, utf8_decode($row['colo_nombre']), $format_cell);
						
							if (isset($row['deaf_fecha_garantia'])) {
								$row['deaf_fecha_garantia'] = date("d-m-Y", strtotime($row['deaf_fecha_garantia']));
							} else {
								$row['deaf_fecha_garantia'] = null;
							}
			
							$worksheet->write($row_count, 10, $row['ubaf_fecha_garantia'], $format_cell);
							$worksheet->write($row_count, 11, $row['ubaf_precio'], $format_cell);
							
							if (isset($row['deaf_depreciable'])) {
								if ($row['ubaf_depreciable'] == 1) {
									$row['ubaf_depreciable'] = "Si";
								} else {
									$row['ubaf_depreciable'] = "No";
								}
							} else {
								$row['ubaf_depreciable'] = null;
							}
						
							$worksheet->write($row_count, 12, $row['ubaf_depreciable'], $format_cell);
							$worksheet->write($row_count, 13, $row['ubaf_vida_util'], $format_cell);
							$worksheet->write($row_count, 14, utf8_decode($row['ceco_nombre']), $format_cell);
							$worksheet->write($row_count, 15, utf8_decode($row['padre']), $format_cell);
							$worksheet->write($row_count, 16, utf8_decode($row['abuelo']), $format_cell);
							$row_count++;
							
							$total += $row['ubaf_precio'];
							$sum_bienes += 1;
						}
						
						$worksheet->write($row_count+2, 12, "Total Unidades: ", $format_head);
						$worksheet->write($row_count+2, 13, $sum_bienes, $format_cell);
						$worksheet->write($row_count+2, 15, "Total Neto: ", $format_head);
						$worksheet->write($row_count+2, 16, $total, $format_cell);
						$worksheet->write($row_count+3, 15, "IVA (".$valor_iva."%): ", $format_head);
						$worksheet->write($row_count+3, 16, number_format(round(($total * $valor_iva) / 100), 0, ',', '.'), $format_cell);
						$worksheet->write($row_count+4, 15, "Total: ", $format_head);
						$worksheet->write($row_count+4, 16, $total, $format_cell);
						
					} elseif ($key == "EXISTENCIAS") {
						
						$worksheet->write(10, 0, 'ID Producto', $format_head);
						$worksheet->write(10, 1, 'Código', $format_head);
						$worksheet->write(10, 2, 'Nombre', $format_head);
						$worksheet->write(10, 3, 'Tipo de Bien', $format_head);
						$worksheet->write(10, 4, 'Familia', $format_head);
						$worksheet->write(10, 5, 'Grupo', $format_head);
						$worksheet->write(10, 6, 'Cantidad', $format_head);
						$worksheet->write(10, 7, 'Precio', $format_head);
						$worksheet->write(10, 8, 'Serie', $format_head);
						$worksheet->write(10, 9, 'Fecha de Vencimiento', $format_head);
						$worksheet->write(10, 10, 'Centro de Costo', $format_head);
						
						$row_count = 11;
						
						$worksheet->write(7, 3, "GASTOS DE EXISTENCIAS PARA CUENTA CONTABLE ".$cuco_nombre);
						$worksheet->write(9, 10, date("d-m-Y H:i:s"));
						$total = 0;
			
						foreach ($val as $row) {
							$row = array_pop($row);
							$worksheet->write($row_count, 0, $row['prod_id'], $format_cell);
							$worksheet->writeString($row_count, 1, $row['prod_codigo'], $format_cell);
							$worksheet->write($row_count, 2, utf8_decode($row['prod_nombre']), $format_cell);
							$worksheet->write($row_count, 3, utf8_decode($row['tibi_nombre']), $format_cell);
							$worksheet->write($row_count, 4, utf8_decode($row['fami_nombre']), $format_cell);
							$worksheet->write($row_count, 5, utf8_decode($row['grup_nombre']), $format_cell);
							$worksheet->write($row_count, 6, $row['deex_cantidad'], $format_cell);
							$worksheet->write($row_count, 7, $row['deex_precio'], $format_cell);
							$worksheet->writeString($row_count, 8, utf8_decode($row['deex_serie']), $format_cell);
							$worksheet->write($row_count, 9, date("d-m-Y", strtotime($row['deex_fecha_vencimiento'])), $format_cell);
							$worksheet->write($row_count, 10, utf8_decode($row['ceco_nombre']), $format_cell);
							
							$total += ($row['deex_cantidad']*$row['deex_precio']);
							$row_count++;
						}
						
						$worksheet->write($row_count+2, 9, "Total Neto: ", $format_head);
						$worksheet->write($row_count+2, 10, $total, $format_cell);
						$worksheet->write($row_count+3, 9, "IVA (".$valor_iva."%): ", $format_head);
						$worksheet->write($row_count+3, 10, number_format(round(($total * $valor_iva) / 100), 0, ',', '.'), $format_cell);
						$worksheet->write($row_count+4, 9, "Total: ", $format_head);
						$worksheet->write($row_count+4, 10, $total, $format_cell);
					}
				}
			}
			
			ob_clean();
			$workbook->send('Gastos_cuenta_contable_'.$cuco_nombre.'_'.date('d_m_Y_H_i_s').'.xls');
			$workbook->close();
			exit;
		}
	
		$centros_costos = $this->Session->read('userdata.selectCC');
		$this->set('centros_costos', $centros_costos);
		$ctas_contables = $this->CuentaContable->find('list', array('fields' => array('cuco_id', 'cuco_nombre'), 'order' => array('cuco_nombre' => 'asc')));
		$this->set('ctas_contables', $ctas_contables);
	}
	
	function traslados_por_fecha() {
		$centros_costos = $this->Session->read('userdata.selectCC');
		$this->set('centros_costos', $centros_costos);
		
		$centros_costos_destino = $this->Session->read('userdata.selectCCAll');
		$this->set('centros_costos_destino', $centros_costos_destino);

		
		$tipos_bienes = $this->TipoBien->find('list', array('fields' => 'tibi_nombre'));
		$this->set('tipos_bienes', $tipos_bienes);
	}
	
	function traslados_por_fechas_existencias_pdf($ceco_id, $ceco_id_hijo, $tibi_id, $fecha_desde, $fecha_hasta) {
		$this->layout = "ajax";
		if (!$ceco_id) {
			$this->Session->setFlash(__('ID invalido', true));
			$this->redirect(array('action'=>'plancheta'));
		}		
		// sacamos info del centro de costo
		
		$info_cc = $this->CentroCosto->read(null, $ceco_id);
		
		$logo = $this->Configuracion->obtieneLogo();
		$fp = @fopen($_SERVER['DOCUMENT_ROOT']."/app/webroot/files/logo.png", "w");
		
		if($fp==true){
			try {
				fputs($fp, base64_decode($logo));
				fclose($fp);
				
				$pdf = new HTML_ToPDF("http://".$_SERVER['HTTP_HOST']."/reportes/traslados_por_fechas_existencias_pdf_html/".$ceco_id."/".$ceco_id_hijo."/".$tibi_id."/".$fecha_desde."/".$fecha_hasta."/", "http://".$_SERVER['HTTP_HOST']);
				$pdf->setUnderlineLinks(true);
				$pdf->setScaleFactor('0.8');
				$pdf->setUseColor(true);
				$pdf->setFooter('center', 'Página $N');
				$pdf->setHeader('center', '&nbsp;');
				$fp = fopen($pdf->convert(), "r");
				
				$ceco_nombre = str_replace(" ", "_", $info_cc['CentroCosto']['ceco_nombre']);
				
				ob_clean();
				header("Content-type: application/pdf; name=Traslados_Existencias_".$ceco_nombre."_".date('d_m_Y_H_i_s').".pdf");
				header("Content-disposition: attachment; filename=Traslados_Existencias_".$ceco_nombre."_".date('d_m_Y_H_i_s').".pdf");
				
				if (rewind($fp)) {
					fpassthru($fp);
				}
				fclose($fp);
					
			} catch (HTML_ToPDFException $e) {
				echo $e->getMessage();
			}
		} else {
			$this->Session->setFlash(__('No se puede generar el logo para el reporte', true));
			$this->redirect(array('action' => 'traslados_por_fecha'));
		}
	}
	
	function traslados_por_fechas_existencias_pdf_html($ceco_id, $ceco_id_hijo, $tibi_id, $fecha_desde, $fecha_hasta) {
		$this->layout = 'ajax';
		
		// sacamos info del centro de costo
		$info_cc = $this->CentroCosto->read(null, $ceco_id);
		
		// condicionamos filtro de centro de costo destino
		if ($ceco_id_hijo != 'null') {
			$ceco_id_hijo = $ceco_id_hijo;
		}
		
		$info = $this->Reporte->TrasladosPorFecha($ceco_id, $ceco_id_hijo, $tibi_id, $fecha_desde, $fecha_hasta);
		$this->set('info', $info);
		$this->set('info_cc', $info_cc);
		$this->set('fecha_desde', $fecha_desde);
		$this->set('fecha_hasta', $fecha_hasta);
	}
	
	function traslados_por_fechas_existencias_excel($ceco_id, $ceco_id_hijo, $tibi_id, $fecha_desde, $fecha_hasta) {
		$this->layout = 'ajax';
				
		// sacamos info del centro de costo
		$info_cc = $this->CentroCosto->read(null, $ceco_id);
		
		// condicionamos filtro de centro de costo destino
		if ($ceco_id_hijo != 'null') {
			$ceco_id_hijo = $ceco_id_hijo;
		}
		
		$info = $this->Reporte->TrasladosPorFecha($ceco_id, $ceco_id_hijo, $tibi_id, $fecha_desde, $fecha_hasta);
		
		$workbook = new Spreadsheet_Excel_Writer();		
		$worksheet = $workbook->addWorksheet('Libro 1');
		$worksheet->setOutline(true, true, true, true);
		
		// Cabeceras de tabla
		$format_head =& $workbook->addFormat();
		$format_head->setFgColor('yellow');
		$format_head->setBorder(1);
		
		$worksheet->write(7, 0, 'Fecha desde:', $format_head);
		$worksheet->write(8, 0, 'Fecha hasta:', $format_head);		
		$worksheet->write(10, 0, 'Producto', $format_head);
		$worksheet->write(10, 1, 'Tipo Bien', $format_head);
		$worksheet->write(10, 2, 'Centro de Costo/Salud Destino', $format_head);
		$worksheet->write(10, 3, 'Fecha', $format_head);
		$worksheet->write(10, 4, 'Serie', $format_head);
		$worksheet->write(10, 5, 'Precio', $format_head);
		$worksheet->write(10, 6, 'Cantidad', $format_head);
		$worksheet->write(10, 7, 'Fecha Vencimiento', $format_head);
				
		// Formato de celdas
		$format_cell = $workbook->addFormat();
		$format_cell->setBorder(1);
		$row_count = 11;
		
		$worksheet->write(7, 1, $fecha_desde, $format_cell);
		$worksheet->write(8, 1, $fecha_hasta, $format_cell);
		$worksheet->write(7, 3, "REPORTE TRASLADOS DE EXISTENCIAS ".utf8_decode($info_cc['CentroCosto']['ceco_nombre']));
		$worksheet->write(9, 7, date("d-m-Y H:i:s"));
		
		foreach ($info as $row) {
			$row = array_pop($row);
			$worksheet->write($row_count, 0, utf8_decode($row['prod_nombre']), $format_cell);
			$worksheet->write($row_count, 1, utf8_decode($row['tibi_nombre']), $format_cell);
			$worksheet->write($row_count, 2, utf8_decode($row['ceco_nombre']), $format_cell);
			$worksheet->write($row_count, 3, date('d-m-Y', strtotime($row['exis_fecha'])), $format_cell);			
			$worksheet->write($row_count, 4, utf8_decode($row['deex_serie']), $format_cell);
			$worksheet->write($row_count, 5, $row['deex_precio'], $format_cell);
			$worksheet->write($row_count, 6, $row['deex_cantidad'], $format_cell);
			
			if ($row['deex_fecha_vencimiento'] != null) {
				$row['deex_fecha_vencimiento'] = date("d-m-Y", strtotime($row['deex_fecha_vencimiento']));
			} else {
				$row['deex_fecha_vencimiento'] = null;
			}		
		
			$worksheet->write($row_count, 7, $row['deex_fecha_vencimiento'], $format_cell);
			$row_count++;
		}
		
		ob_clean();
		$ceco_nombre = str_replace(" ", "_", $info_cc['CentroCosto']['ceco_nombre']);
		$workbook->send('Traslados_Existencias_'.$ceco_nombre."_".date('d_m_Y_H_i_s').'.xls');
		$workbook->close();
	}
	
	function traslados_por_fechas_activos_fijos_pdf($ceco_id, $ceco_id_hijo, $tibi_id, $fecha_desde, $fecha_hasta) {
		$this->layout = "ajax";
		if (!$ceco_id) {
			$this->Session->setFlash(__('ID invalido', true));
			$this->redirect(array('action'=>'plancheta'));
		}		
		// sacamos info del centro de costo
		
		$info_cc = $this->CentroCosto->read(null, $ceco_id);
		
		$logo = $this->Configuracion->obtieneLogo();
		$fp = @fopen($_SERVER['DOCUMENT_ROOT']."/app/webroot/files/logo.png", "w");
		
		if($fp==true){
			try {
				fputs($fp, base64_decode($logo));
				fclose($fp);
				
				$pdf = new HTML_ToPDF("http://".$_SERVER['HTTP_HOST']."/reportes/traslados_por_fechas_activos_fijos_pdf_html/".$ceco_id."/".$ceco_id_hijo."/".$tibi_id."/".$fecha_desde."/".$fecha_hasta."/", "http://".$_SERVER['HTTP_HOST']);
				$pdf->setUnderlineLinks(true);
				$pdf->setScaleFactor('0.8');
				$pdf->setUseColor(true);
				$pdf->setFooter('center', 'Página $N');
				$pdf->setHeader('center', '&nbsp;');
				$fp = fopen($pdf->convert(), "r");
				
				$ceco_nombre = str_replace(" ", "_", $info_cc['CentroCosto']['ceco_nombre']);
				
				ob_clean();
				header("Content-type: application/pdf; name=Traslados_Activo_Fijo_".$ceco_nombre."_".date('d_m_Y_H_i_s').".pdf");
				header("Content-disposition: attachment; filename=Traslados_Activo_Fijo_".$ceco_nombre."_".date('d_m_Y_H_i_s').".pdf");
				
				if (rewind($fp)) {
					fpassthru($fp);
				}
				fclose($fp);
					
			} catch (HTML_ToPDFException $e) {
				echo $e->getMessage();
			}
		} else {
			$this->Session->setFlash(__('No se puede generar el logo para el reporte', true));
			$this->redirect(array('action' => 'traslados_por_fecha'));
		}
	}
	
	function traslados_por_fechas_activos_fijos_pdf_html($ceco_id, $ceco_id_hijo, $tibi_id, $fecha_desde, $fecha_hasta) {
		$this->layout = 'ajax';
		
		// sacamos info del centro de costo
		$info_cc = $this->CentroCosto->read(null, $ceco_id);
		
		// condicionamos filtro de centro de costo destino
		if ($ceco_id_hijo != 'null') {
			$ceco_id_hijo = $ceco_id_hijo;
		}
		
		$info = $this->Reporte->TrasladosPorFecha($ceco_id, $ceco_id_hijo, $tibi_id, $fecha_desde, $fecha_hasta);
		$this->set('info', $info);
		$this->set('info_cc', $info_cc);
	}
	
	function traslados_por_fechas_activos_fijos_excel($ceco_id, $ceco_id_hijo, $tibi_id, $fecha_desde, $fecha_hasta) {
		$this->layout = 'ajax';
				
		// sacamos info del centro de costo
		$info_cc = $this->CentroCosto->read(null, $ceco_id);
		
		// condicionamos filtro de centro de costo destino
		if ($ceco_id_hijo != 'null') {
			$ceco_id_hijo = $ceco_id_hijo;
		}
		
		$info = $this->Reporte->TrasladosPorFecha($ceco_id, $ceco_id_hijo, $tibi_id, $fecha_desde, $fecha_hasta);
		
		$workbook = new Spreadsheet_Excel_Writer();		
		$worksheet = $workbook->addWorksheet('Libro 1');
		$worksheet->setOutline(true, true, true, true);
		
		// Cabeceras de tabla
		$format_head =& $workbook->addFormat();
		$format_head->setFgColor('yellow');
		$format_head->setBorder(1);
		
		$worksheet->write(10, 0, 'Código', $format_head);
		$worksheet->write(10, 1, 'Nombre', $format_head);
		$worksheet->write(10, 2, 'Centro de Costo/Salud Destino', $format_head);
		$worksheet->write(10, 3, 'Tipo Bien', $format_head);
		$worksheet->write(10, 4, 'Fecha', $format_head);
		$worksheet->write(10, 5, 'Marca', $format_head);
		$worksheet->write(10, 6, 'Propiedad', $format_head);
		$worksheet->write(10, 7, 'Color', $format_head);
		$worksheet->write(10, 8, 'Situación', $format_head);
		$worksheet->write(10, 9, 'Modelo', $format_head);
		$worksheet->write(10, 10, 'Serie', $format_head);
		$worksheet->write(10, 11, 'Fecha Adquisición', $format_head);
		$worksheet->write(10, 12, 'Fecha Garantía', $format_head);
		
		// Formato de celdas
		$format_cell = $workbook->addFormat();
		$format_cell->setBorder(1);
		$row_count = 11;
		
		$worksheet->write(7, 3, "REPORTE TRASLADOS DE ACTIVOS FIJOS ".utf8_decode($info_cc['CentroCosto']['ceco_nombre']));
		$worksheet->write(9, 11, date("d-m-Y H:i:s"));
		
		foreach ($info as $row) {
			$row = array_pop($row);
			$worksheet->writeString($row_count, 0, $row['deaf_codigo'], $format_cell);
			$worksheet->write($row_count, 1, utf8_decode($row['prod_nombre']), $format_cell);
			$worksheet->write($row_count, 2, utf8_decode($row['ceco_nombre']), $format_cell);
			$worksheet->write($row_count, 3, utf8_decode($row['tibi_nombre']), $format_cell);
			$worksheet->write($row_count, 4, date('d-m-Y', strtotime($row['acfi_fecha'])), $format_cell);
			
			if (isset($row['marc_nombre'])) {
				$row['marc_nombre'];
			}
			
			$worksheet->write($row_count, 5, utf8_decode($row['marc_nombre']), $format_cell);
			$worksheet->write($row_count, 6, utf8_decode($row['prop_nombre']), $format_cell);
			
			if (isset($row['colo_nombre'])) {
				$row['colo_nombre'];
			}
			
			$worksheet->write($row_count, 7, utf8_decode($row['colo_nombre']), $format_cell);
			$worksheet->write($row_count, 8, utf8_decode($row['situ_nombre']), $format_cell);
			
			if (isset($row['mode_nombre'])) {
				$row['mode_nombre'];
			}
						
			$worksheet->write($row_count, 9, utf8_decode($row['mode_nombre']), $format_cell);
			
			if (isset($row['deaf_serie'])) {
				$row['deaf_serie'];
			}
			
			$worksheet->write($row_count, 10, utf8_decode($row['deaf_serie']), $format_cell);
			$worksheet->write($row_count, 11, date('d-m-Y', strtotime($row['acfi_fecha'])), $format_cell);
			
			if ($row['deaf_fecha_garantia'] != null) {
				$row['deaf_fecha_garantia'] = date("d-m-Y", strtotime($row['deaf_fecha_garantia']));
			} else {
				$row['deaf_fecha_garantia'] = null;
			}		
		
			$worksheet->write($row_count, 12, $row['deaf_fecha_garantia'], $format_cell);
			$row_count++;
		}
		
		ob_clean();
		$ceco_nombre = str_replace(" ", "_", $info_cc['CentroCosto']['ceco_nombre']);
		$workbook->send('Traslados_Activo_Fijo_'.$ceco_nombre."_".date('d_m_Y_H_i_s').'.xls');
		$workbook->close();
	}
	
	function stock_centro_costo() {
		
		$centros_costos = $this->Session->read('userdata.selectCC');
		$this->set('centros_costos', $centros_costos);
		
	}
	
	function stock_centro_costo_pdf($ceco_id) {
		$this->layout = "ajax";
		
		// sacamos info del centro de costo		
		$info_cc = $this->CentroCosto->read(null, $ceco_id);
		
		$logo = $this->Configuracion->obtieneLogo();
		$fp = @fopen($_SERVER['DOCUMENT_ROOT']."/app/webroot/files/logo.png", "w");
		
		if($fp==true){
			try {
				fputs($fp, base64_decode($logo));
				fclose($fp);
				
				$pdf = new HTML_ToPDF("http://".$_SERVER['HTTP_HOST']."/reportes/stock_centro_costo_pdf_html/".$ceco_id."/", "http://".$_SERVER['HTTP_HOST']);
				$pdf->setUnderlineLinks(true);
				$pdf->setScaleFactor('0.8');
				$pdf->setUseColor(true);
				$pdf->setFooter('center', 'Página $N');
				$pdf->setHeader('center', '&nbsp;');
				$fp = fopen($pdf->convert(), "r");
				
				$ceco_nombre = str_replace(" ", "_", $info_cc['CentroCosto']['ceco_nombre']);
				ob_clean();
				
				header("Content-type: application/pdf; name=Stock_Centro_Costo_".$ceco_nombre."_".date('d_m_Y_H_i_s').".pdf");
				header("Content-disposition: attachment; filename=Stock_Centro_Costo_".$ceco_nombre."_".date('d_m_Y_H_i_s').".pdf");
				
				if (rewind($fp)) {
					fpassthru($fp);
				}
				fclose($fp);
					
			} catch (HTML_ToPDFException $e) {
				echo $e->getMessage();
			}
		} else {
			$this->Session->setFlash(__('No se puede generar el logo para el reporte', true));
			$this->redirect(array('action' => 'stock_centro_costo'));
		}
	}
	
	function stock_centro_costo_pdf_html($ceco_id) {
		$this->layout = 'ajax';
		
		$ccs = $this->ccArrayToCcVector($this->CentroCosto->findAllChildren($ceco_id));
		$info = $this->Reporte->stockCentroCosto($ccs);
		$info_cc = $this->CentroCosto->find('first', array('conditions' => array('CentroCosto.ceco_id' => $ceco_id)));
		$ceco_nombre = $info_cc['CentroCosto']['ceco_nombre'];
		$this->set('info', $info);
		$this->set('ceco_nombre_', $ceco_nombre);
	}
	
	function stock_centro_costo_general_pdf($ceco_id) {
		$this->layout = "ajax";
		
		// sacamos info del centro de costo		
		$info_cc = $this->CentroCosto->read(null, $ceco_id);
		
		$logo = $this->Configuracion->obtieneLogo();
		$fp = @fopen($_SERVER['DOCUMENT_ROOT']."/app/webroot/files/logo.png", "w");
		
		if($fp==true){
			try {
				fputs($fp, base64_decode($logo));
				fclose($fp);
				
				$pdf = new HTML_ToPDF("http://".$_SERVER['HTTP_HOST']."/reportes/stock_centro_costo_general_pdf_html/".$ceco_id."/", "http://".$_SERVER['HTTP_HOST']);
				$pdf->setUnderlineLinks(true);
				$pdf->setScaleFactor('0.8');
				$pdf->setUseColor(true);
				$pdf->setFooter('center', 'Página $N');
				$pdf->setHeader('center', '&nbsp;');
				$fp = fopen($pdf->convert(), "r");
				
				$ceco_nombre = str_replace(" ", "_", $info_cc['CentroCosto']['ceco_nombre']);
				ob_clean();
				
				header("Content-type: application/pdf; name=Stock_Centro_Costo_General_".$ceco_nombre."_".date('d_m_Y_H_i_s').".pdf");
				header("Content-disposition: attachment; filename=Stock_Centro_Costo_General_".$ceco_nombre."_".date('d_m_Y_H_i_s').".pdf");
				
				if (rewind($fp)) {
					fpassthru($fp);
				}
				fclose($fp);
					
			} catch (HTML_ToPDFException $e) {
				echo $e->getMessage();
			}
		} else {
			$this->Session->setFlash(__('No se puede generar el logo para el reporte', true));
			$this->redirect(array('action' => 'stock_centro_costo'));
		}
	}
	
	function stock_centro_costo_general_pdf_html($ceco_id) {
		$this->layout = 'ajax';
		
		$ccs = $this->ccArrayToCcVector($this->CentroCosto->findAllChildren($ceco_id));
		$info = $this->Reporte->stockCentroCostoGeneral($ccs);
		$info_cc = $this->CentroCosto->find('first', array('conditions' => array('CentroCosto.ceco_id' => $ceco_id)));
		$ceco_nombre = $info_cc['CentroCosto']['ceco_nombre'];
		$this->set('info', $info);
		$this->set('ceco_nombre_', $ceco_nombre);
	}
	
	function stock_centro_costo_excel($ceco_id) {
		$this->layout = 'ajax';
				
		// sacamos info del centro de costo
		$info_cc = $this->CentroCosto->read(null, $ceco_id);
		
		$ccs = $this->ccArrayToCcVector($this->CentroCosto->findAllChildren($ceco_id));
		$info = $this->Reporte->stockCentroCosto($ccs);
		
		$workbook = new Spreadsheet_Excel_Writer();		
		$worksheet = $workbook->addWorksheet('Libro 1');
		$worksheet->setOutline(true, true, true, true);
		
		// Cabeceras de tabla
		$format_head =& $workbook->addFormat();
		$format_head->setFgColor('yellow');
		$format_head->setBorder(1);
		
		$worksheet->write(3, 0, 'Centro Costo', $format_head);
		$worksheet->write(3, 1, 'Descripción', $format_head);
		$worksheet->write(3, 2, 'Stock Total', $format_head);
		
				
		// Formato de celdas
		$format_cell = $workbook->addFormat();
		$format_cell->setBorder(1);
		$row_count = 4;
		
		$worksheet->write(1, 0, "STOCK ACTIVOS FIJOS ".utf8_decode($info_cc['CentroCosto']['ceco_nombre']));
		$worksheet->write(1, 3, date("d-m-Y H:i:s"));
		
		foreach ($info as $row) {
			$row = array_pop($row);
			$worksheet->write($row_count, 0, utf8_decode($row['ceco_nombre']), $format_cell);
			$worksheet->write($row_count, 1, utf8_decode($row['prod_nombre']), $format_cell);
			$worksheet->write($row_count, 2, $row['total'], $format_cell);
			$row_count++;
		}
		
		ob_clean();
		$ceco_nombre = str_replace(" ", "_", $info_cc['CentroCosto']['ceco_nombre']);
		$workbook->send('Stock_Centro_Costo_'.$ceco_nombre."_".date('d_m_Y_H_i_s').'.xls');
		$workbook->close();
		
	}
	
	function stock_centro_costo_general_excel($ceco_id) {
		$this->layout = 'ajax';
				
		// sacamos info del centro de costo
		$info_cc = $this->CentroCosto->read(null, $ceco_id);
		
		$ccs = $this->ccArrayToCcVector($this->CentroCosto->findAllChildren($ceco_id));
		$info = $this->Reporte->stockCentroCostoGeneral($ccs);
		
		$workbook = new Spreadsheet_Excel_Writer();		
		$worksheet = $workbook->addWorksheet('Libro 1');
		$worksheet->setOutline(true, true, true, true);
		
		// Cabeceras de tabla
		$format_head =& $workbook->addFormat();
		$format_head->setFgColor('yellow');
		$format_head->setBorder(1);
		
		$worksheet->write(3, 0, 'Descripción', $format_head);
		$worksheet->write(3, 1, 'Stock Total', $format_head);
				
		// Formato de celdas
		$format_cell = $workbook->addFormat();
		$format_cell->setBorder(1);
		$row_count = 4;
		
		$worksheet->write(1, 0, "STOCK GENERAL ACTIVOS FIJOS ".utf8_decode($info_cc['CentroCosto']['ceco_nombre']));
		$worksheet->write(1, 2, date("d-m-Y H:i:s"));
		
		foreach ($info as $row) {
			$row = array_pop($row);
			$worksheet->write($row_count, 0, utf8_decode($row['prod_nombre']), $format_cell);
			$worksheet->write($row_count, 1, $row['total'], $format_cell);
			$row_count++;
		}
		
		ob_clean();
		$ceco_nombre = str_replace(" ", "_", $info_cc['CentroCosto']['ceco_nombre']);
		$workbook->send('Stock_Centro_Costo_'.$ceco_nombre."_".date('d_m_Y_H_i_s').'.xls');
		$workbook->close();
		
	}
	
	function bajas_activos_fijos() {
		$centros_costos = $this->Session->read('userdata.selectCC');
		$this->set('centros_costos', $centros_costos);
	}
	
	function bajas_activos_fijos_excel($ceco_id = null, $prod_id = null, $fecha_desde = null, $fecha_hasta = null) {
		$this->layout = 'ajax';
	
		// sacamos info del centro de costo
		$info_cc = $this->CentroCosto->read(null, $ceco_id);
		
		$ccs = $this->ccArrayToCcVector($this->CentroCosto->findAllChildren($ceco_id));
		$info = $this->Reporte->bajasCentrosCostos($ccs, $prod_id, $fecha_desde, $fecha_hasta);
		
		$workbook = new Spreadsheet_Excel_Writer();		
		$worksheet = $workbook->addWorksheet('Libro 1');
		$worksheet->setOutline(true, true, true, true);
		
		// Cabeceras de tabla
		$format_head =& $workbook->addFormat();
		$format_head->setFgColor('yellow');
		$format_head->setBorder(1);
		
		$worksheet->write(3, 0, 'Fecha Baja', $format_head);
		$worksheet->write(3, 1, 'Código', $format_head);
		$worksheet->write(3, 2, 'Producto', $format_head);
		$worksheet->write(3, 3, 'Precio', $format_head);
		$worksheet->write(3, 4, 'Depreciable', $format_head);
		$worksheet->write(3, 5, 'Vida Útil', $format_head);
		$worksheet->write(3, 6, 'Número de Documento', $format_head);
		$worksheet->write(3, 7, 'Dependencia Virtual', $format_head);
		$worksheet->write(3, 8, 'Motivo de Baja', $format_head);
		$worksheet->write(3, 9, 'Centro Costo (Unidad)', $format_head);
		$worksheet->write(3, 10, 'Dependencia', $format_head);
		$worksheet->write(3, 11, 'Establecimiento', $format_head);
		$worksheet->write(3, 12, 'Observación', $format_head);
				
		// Formato de celdas
		$format_cell = $workbook->addFormat();
		$format_cell->setBorder(1);
		$row_count = 4;
		
		$worksheet->write(1, 0, "BAJAS ACTIVOS FIJOS".' '.utf8_decode($info_cc['CentroCosto']['ceco_nombre']));
		$worksheet->write(1, 5, date("d-m-Y H:i:s"));
		
		foreach ($info as $row) {
			$row = array_pop($row);
			$worksheet->write($row_count, 0, date('d-m-Y', strtotime($row['baaf_fecha'])), $format_cell);
			$worksheet->writeString($row_count, 1, $row['deba_codigo'], $format_cell);
			$worksheet->write($row_count, 2, utf8_decode($row['prod_nombre']), $format_cell);
			$worksheet->write($row_count, 3, $row['deba_precio'], $format_cell);
			if ($row['deba_depreciable'] == 1) {
				$row['deba_depreciable'] = 'Si';
			} else {
				$row['deba_depreciable'] = 'No';
			}
			$worksheet->write($row_count, 4, $row['deba_depreciable'], $format_cell);
			$worksheet->write($row_count, 5, $row['deba_vida_util'], $format_cell);
			$worksheet->write($row_count, 6, $row['baaf_numero_documento'], $format_cell);
			$worksheet->write($row_count, 7, utf8_decode($row['devi_nombre']), $format_cell);
			$worksheet->write($row_count, 8, utf8_decode($row['moba_nombre']), $format_cell);
			$worksheet->write($row_count, 9, utf8_decode($row['unidad']), $format_cell);
			$worksheet->write($row_count, 10, utf8_decode($row['dependencia']), $format_cell);
			$worksheet->write($row_count, 11, utf8_decode($row['establecimiento']), $format_cell);
			$worksheet->write($row_count, 12, utf8_decode($row['baaf_observacion']), $format_cell);
			$row_count++;
		}
		
		ob_clean();
		$ceco_nombre = str_replace(" ", "_", $info_cc['CentroCosto']['ceco_nombre']);
		$workbook->send('Reporte_Bajas_Activos_Fijos_'.date('d_m_Y_H_i_s').'.xls');
		$workbook->close();
	}
	
	function trazabilidad_index() {
		if (!empty($this->data)) {
			$codigos_barra = $this->data;
			$this->excel_trazabilidad($codigos_barra);
		}
		
		set_time_limit(0);
		ini_set('memory_limit', '2048M');
		$trazabilidad = $this->TrazabilidadActivoFijo->find('count');	
		
		if ($trazabilidad == 0) {
			$ubicaciones = $this->UbicacionActivoFijo->find('all', array('fields' => array('UbicacionActivoFijo.*')));	
			$this->TrazabilidadActivoFijo->saveUbicacionToTrazabilidad($ubicaciones);
		}
		
		$ceco_id = $this->Session->read('userdata.CentroCosto.ceco_id');
		$this->set('ceco_id', $ceco_id);
		ob_clean();
	}
	
	function excel_trazabilidad($codigos_barra) {
		$this->layout = 'ajax';		
		$data = $this->TrazabilidadActivoFijo->obtieneTrazabilidadActivoFijo($codigos_barra);		
		//$datos_producto = $this->TrazabilidadActivoFijo->find('first', array('fields' => array('TrazabilidadActivoFijo.traf_codigo', 'Producto.prod_nombre'), 'conditions' => array('TrazabilidadActivoFijo.traf_codigo' => $codigo_barra)));
				
		$workbook = new Spreadsheet_Excel_Writer();		
		$worksheet = $workbook->addWorksheet('Libro 1');
		$worksheet->setOutline(true, true, true, true);
		
		// Cabeceras de tabla
		$format_head =& $workbook->addFormat();
		$format_head->setFgColor('yellow');
		$format_head->setBorder(1);
		
		$worksheet->write(9, 0, 'Código', $format_head);
		$worksheet->write(9, 1, 'Nombre', $format_head);
		$worksheet->write(9, 2, 'Grupo', $format_head);	
		$worksheet->write(9, 3, 'Familia', $format_head);	
		$worksheet->write(9, 4, 'Movimiento', $format_head);
		$worksheet->write(9, 5, 'Fecha', $format_head);				
		$worksheet->write(9, 6, 'Propiedad', $format_head);
		$worksheet->write(9, 7, 'Situación', $format_head);
		$worksheet->write(9, 8, 'Marca', $format_head);
		$worksheet->write(9, 9, 'Color', $format_head);
		$worksheet->write(9, 10, 'Fecha Garantía', $format_head);
		$worksheet->write(9, 11, 'Valor', $format_head);
		$worksheet->write(9, 12, 'Serie', $format_head);
		$worksheet->write(9, 13, '¿Es Depreciable?', $format_head);
		$worksheet->write(9, 14, 'Vida Útil', $format_head);
		$worksheet->write(9, 15, 'Centro de Costo (Unidad)', $format_head);
		$worksheet->write(9, 16, 'Dependencia', $format_head);

		$worksheet->write(9, 17, 'Establecimiento', $format_head);					
		
		// Formato de celdas
		$format_cell = $workbook->addFormat();
		$format_cell->setBorder(1);
		$row_count = 10;
		
		$worksheet->write(2, 3, "REPORTE DE TRAZABILIDAD");
		//$worksheet->write(4, 0, "Fecha", $format_head);
		//$worksheet->write(5, 0, "Código", $format_head);
		//$worksheet->write(6, 0, "Nombre", $format_head);
		//$worksheet->write(4, 1, date('d-m-Y H:i:s'), $format_cell);
		//$worksheet->writeString(5, 1, $datos_producto['TrazabilidadActivoFijo']['traf_codigo'], $format_cell);
		//$worksheet->write(6, 1, $datos_producto['Producto']['prod_nombre'], $format_cell);
		
		foreach ($data as $row) {
			$row = array_pop($row);			
			$worksheet->writeString($row_count, 0, $row['traf_codigo'], $format_cell);
			$worksheet->write($row_count, 1, utf8_decode($row['prod_nombre']), $format_cell);
			$worksheet->write($row_count, 2, utf8_decode($row['grup_nombre']), $format_cell);
			$worksheet->write($row_count, 3, utf8_decode($row['fami_nombre']), $format_cell);
			$worksheet->write($row_count, 4, utf8_decode($row['tmov_descripcion']), $format_cell);
			$worksheet->write($row_count, 5, date('d-m-Y H:i:s', strtotime($row['traf_fecha'])), $format_cell);
			$worksheet->write($row_count, 6, utf8_decode($row['prop_nombre']), $format_cell);
			$worksheet->write($row_count, 7, utf8_decode($row['situ_nombre']), $format_cell);
			$worksheet->write($row_count, 8, utf8_decode($row['marc_nombre']), $format_cell);
			$worksheet->write($row_count, 9, utf8_decode($row['marc_nombre']), $format_cell);
			$worksheet->write($row_count, 10, utf8_decode($row['marc_nombre']), $format_cell);
			$worksheet->write($row_count, 11, utf8_decode($row['traf_precio']), $format_cell);
			$worksheet->write($row_count, 12, utf8_decode($row['marc_nombre']), $format_cell);			
			$worksheet->write($row_count, 13, utf8_decode($row['traf_depreciable']), $format_cell);
			$worksheet->write($row_count, 14, utf8_decode($row['traf_vida_util']), $format_cell);
			$worksheet->write($row_count, 15, utf8_decode($row['centro_costo']), $format_cell);
			$worksheet->write($row_count, 16, utf8_decode($row['dependencia']), $format_cell);
			$worksheet->write($row_count, 17, utf8_decode($row['establecimiento']), $format_cell);
								
			$row_count++;
		}
		
		ob_clean();
		$workbook->send('Stock_Activos_Fijos_'.date('d_m_Y_H_i_s').'.xls');
		$workbook->close();
	}
	
	function depreciacion_producto() {
		if (!empty($this->data)) {
			$codigo_barra = $this->data['Reporte']['ubaf_codigo'];			
			$this->excel_depreciacion_producto($codigo_barra);
		}
		
		$ceco_id = $this->Session->read('userdata.CentroCosto.ceco_id');
		$this->set('ceco_id', $ceco_id);
	}
	
	function excel_depreciacion_producto($codigo_barra) {
		$this->layout = 'ajax';
		ini_set("memory_limit", "1024M");
		
		$logo = $this->Configuracion->obtieneLogo();
		$imageFile = $_SERVER['DOCUMENT_ROOT']."/app/webroot/files/logo.png";
		$fp = @fopen($_SERVER['DOCUMENT_ROOT']."/app/webroot/files/logo.png", "w");
		
		$workbook = new Spreadsheet_Excel_Writer();		
		
		// Cabeceras de tabla
		$format_head = $workbook->addFormat();
		$format_head->setFgColor('yellow');
		$format_head->setBorder(1);
		$format_head->setAlign('justify');
		
		// Formato Titulo
		$format_titulo = $workbook->addFormat();
		$format_titulo->setBold(1);
		
		// Formato de celdas
		$format_cell = $workbook->addFormat();
		$format_cell->setBorder(1);
		
		// Formato de celdas alineado
		$format_cell_align = $workbook->addFormat();
		$format_cell_align->setBorder(1);
		$format_cell_align->setAlign('right');
		
		// info
		$info = $this->Depreciacion->DetalleDepreciacion->obtieneAllByProducto($codigo_barra);
		
		$worksheet = $workbook->addWorksheet();
		$worksheet->setOutline(true, true, true, true);
		$worksheet->setColumn(0, 0, 30);
		$worksheet->setColumn(1, 1, 20);
		$worksheet->setColumn(2, 21, 13);
		$worksheet->setRow(3, 39);
		
		// Titulo
		$worksheet->write(1, 0, "REPORTE DEPRECIACIÓN POR PRODUCTO", $format_titulo);
		
		$worksheet->write(3, 0, "Producto", $format_head);
		$worksheet->write(3, 1, "Código de barra", $format_head);
		$worksheet->write(3, 2, "Propiedad", $format_head);
		$worksheet->write(3, 3, "Situación", $format_head);
		$worksheet->write(3, 4, "Marca", $format_head);
		$worksheet->write(3, 5, "Color", $format_head);
		$worksheet->write(3, 6, "Fecha de Garantía", $format_head);
		$worksheet->write(3, 7, "Fecha de adquisición", $format_head);
		$worksheet->write(3, 8, "Valor Libro", $format_head);
		$worksheet->write(3, 9, "IPC", $format_head);
		$worksheet->write(3, 10, "Corrección Monetaria", $format_head);
		$worksheet->write(3, 11, "Valor Corregido", $format_head);
		$worksheet->write(3, 12, "Valor Actual", $format_head);
		$worksheet->write(3, 13, "Depreciación Acumulada Anterior", $format_head);
		$worksheet->write(3, 14, "Corrección Monetaria (depreciación)", $format_head);
		$worksheet->write(3, 15, "Valor Corregido (depreciación)", $format_head);
		$worksheet->write(3, 16, "Vida Útil (próxima ejercicio)", $format_head);
		$worksheet->write(3, 17, "Valor a Depreciar", $format_head);
		$worksheet->write(3, 18, "Depreciación", $format_head);
		$worksheet->write(3, 19, "Depreciación Acumulada", $format_head);
		$worksheet->write(3, 20, "Valor Neto", $format_head);
		$worksheet->write(3, 21, "Año", $format_head);
		$row_count = 4;			
		
		foreach ($info as $row_) {
			$row_ = array_pop($row_);						
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
			$worksheet->write($row_count, 16, $row_['dede_vida_util_prox'], $format_cell);
			$worksheet->writeString($row_count, 17, number_format($row_['depr_valor_a_depreciar'], 0, ',', '.'), $format_cell_align);
			$worksheet->writeString($row_count, 18, number_format($row_['dede_depr_valor'], 0, ',', '.'), $format_cell_align);
			$worksheet->writeString($row_count, 19, number_format($row_['dede_depr_acumulada'], 0, ',', '.'), $format_cell_align);
			$worksheet->writeString($row_count, 20, number_format($row_['dede_valor_actualizado'], 0, ',', '.'), $format_cell_align);
			$worksheet->writeString($row_count, 21, $row_['depr_ano'], $format_cell_align);			
			$row_count++;
		}
		
		ob_clean();
		$workbook->send('Depreciacion_'.$codigo_barra.'.xls');
		$workbook->close();
		exit;
	}
	
	function mantenciones() {
		$ceco_id = $this->Session->read('userdata.CentroCosto.ceco_id');
		$this->set('ceco_id', $ceco_id);
	}
	
	function mantenciones_excel($ubaf_codigo) {
		$this->layout = 'ajax';
		
		$logo = $this->Configuracion->obtieneLogo();
		$imageFile = $_SERVER['DOCUMENT_ROOT']."/app/webroot/files/logo.png";
		$fp = @fopen($_SERVER['DOCUMENT_ROOT']."/app/webroot/files/logo.png", "w");
		
		$workbook = new Spreadsheet_Excel_Writer();		
		
		// Cabeceras de tabla
		$format_head = $workbook->addFormat();
		$format_head->setFgColor('yellow');
		$format_head->setBorder(1);
		//$format_head->setAlign('justify');
		
		// Formato Titulo
		$format_titulo = $workbook->addFormat();
		$format_titulo->setBold(1);
		
		// Formato de celdas
		$format_cell = $workbook->addFormat();
		$format_cell->setBorder(1);
		
		// Formato de celdas 2
		$format_cell2 = $workbook->addFormat();
		$format_cell2->setBorder(0);
		$format_cell2->setAlign('left');
		
		// Formato de celdas alineado
		$format_cell_align = $workbook->addFormat();
		$format_cell_align->setBorder(1);
		$format_cell_align->setAlign('right');
		
		// info
		$info = $this->ActivoFijoMantencion->findMantenciones($ubaf_codigo);
		$infoProducto = $this->UbicacionActivoFijo->find('first', array('fields' => array('Producto.prod_nombre'), 'conditions' => array('UbicacionActivoFijo.ubaf_codigo' => $ubaf_codigo)));
		$infoMantencion = $this->ActivoFijoMantencion->find('first', array('conditions' => array('ActivoFijoMantencion.ubaf_codigo' => $ubaf_codigo)));

		$worksheet = $workbook->addWorksheet();
		$worksheet->setOutline(true, true, true, true);
		$worksheet->setColumn(0, 0, 20);
		$worksheet->setColumn(0, 1, 28);
		$worksheet->setColumn(0, 2, 40);
		$worksheet->setColumn(0, 3, 11);
		$worksheet->setColumn(0, 4, 18);
		$worksheet->setColumn(0, 5, 13);
		$worksheet->setColumn(0, 6, 13);
		$worksheet->setColumn(0, 7, 15);
		$worksheet->setColumn(0, 8, 15);
		$worksheet->setColumn(0, 9, 20);
		
		// Titulo
		$worksheet->write(0, 1, "REPORTE DE MANTENIMIENTO", $format_titulo);
		
		//Datos del bien
		$worksheet->write(2, 0, "Nombre de Producto", $format_titulo);
		$worksheet->write(2, 1, utf8_decode($infoProducto['Producto']['prod_nombre']), $format_cell2);
		$worksheet->write(3, 0, "Código", $format_titulo);
		$worksheet->writeString(3, 1, $ubaf_codigo, $format_cell2);
		$worksheet->write(4, 0, "Marca", $format_titulo);
		$worksheet->write(4, 1, utf8_decode($infoMantencion['ActivoFijoMantencion']['afma_marca']), $format_cell2);
		$worksheet->write(5, 0, "Modelo", $format_titulo);
		$worksheet->write(5, 1, utf8_decode($infoMantencion['ActivoFijoMantencion']['afma_modelo']), $format_cell2);
		$worksheet->write(6, 0, "Año", $format_titulo);
		$worksheet->write(6, 1, $infoMantencion['ActivoFijoMantencion']['afma_ano'], $format_cell2);	
		$worksheet->write(7, 0, "Patente", $format_titulo);
		$worksheet->write(7, 1, utf8_decode($infoMantencion['ActivoFijoMantencion']['afma_patente']), $format_cell2);
		$worksheet->write(8, 0, "Motor", $format_titulo);
		$worksheet->write(8, 1, utf8_decode($infoMantencion['ActivoFijoMantencion']['afma_motor']), $format_cell2);
		
		// Encabezado
		$worksheet->write(10, 0, "Número de Factura", $format_head);
		$worksheet->write(10, 1, "Fecha de Factura", $format_head);
		$worksheet->write(10, 2, "Trabajo y/o Servicio", $format_head);
		$worksheet->write(10, 3, "Kilometraje", $format_head);
		$worksheet->write(10, 4, "Nombre de Operador", $format_head);
		$worksheet->write(10, 5, "Valor", $format_head);
		$worksheet->write(10, 6, "Observación", $format_head);
		$worksheet->write(10, 7, "Fecha de Servicio", $format_head);			
		$worksheet->write(10, 8, "Proveedor", $format_head);
		$worksheet->write(10, 9, "Centro Costo", $format_head);		
		$row_count = 11;			
		$valor_total = 0;
			
		foreach ($info as $row_) {
			$row_ = array_pop($row_);
			$worksheet->write($row_count, 0, utf8_decode($row_['afma_numero_factura']), $format_cell_align);		
			$worksheet->write($row_count, 1, date("d-m-Y", strtotime($row_['afma_fecha_factura'])), $format_cell_align);						
			$worksheet->write($row_count, 2, utf8_decode($row_['dema_descripcion']), $format_cell);
			$worksheet->writeString($row_count, 3, number_format($row_['dema_kilometraje'], 0, ',', '.'), $format_cell_align);
			$worksheet->write($row_count, 4, utf8_decode($row_['dema_nombre_operador']), $format_cell);
			$worksheet->writeString($row_count, 5, number_format($row_['dema_valor']), $format_cell_align);
			$worksheet->write($row_count, 6, utf8_decode($row_['dema_observacion']), $format_cell);
			$worksheet->write($row_count, 7, date('d-m-Y', strtotime($row_['dema_fecha_servicio'])), $format_cell);			
			$worksheet->write($row_count, 8, utf8_decode($row_['prov_nombre']), $format_cell_align);
			$worksheet->write($row_count, 9, utf8_decode($row_['ceco_nombre']), $format_cell_align);				
			$valor_total = $valor_total + $row_['dema_valor'];
			$row_count++;
		}
		
		$worksheet->write($row_count + 1, 8, 'Valor Total', $format_cell_align);
		$worksheet->writeString($row_count + 1, 9, number_format($valor_total, 0, ',', '.'), $format_cell_align);
		
		ob_clean();
		$workbook->send('Reporte_Mantencion_'.$ubaf_codigo.'.xls');
		$workbook->close();
		exit;
	}
	
	function plancheta_activo_fijo_agrupado_pdf($ceco_id) {		
		$this->layout = "ajax";
		ini_set('memory_limit', '2048M');
		if (!$ceco_id) {
			$this->Session->setFlash(__('ID invalido', true));
			$this->redirect(array('action'=>'plancheta'));
		}
		// sacamos info del centro de costo
		$conditions = array("ceco_id" => $ceco_id);
		$info_cc = $this->CentroCosto->read(null, $ceco_id);
		
		$logo = $this->Configuracion->obtieneLogo();
		$fp = @fopen($_SERVER['DOCUMENT_ROOT']."/app/webroot/files/logo.png", "w");		
		
		if($fp==true){
			fputs($fp, base64_decode($logo));			
			fclose($fp);									
			try {						
				$html = file_get_contents("http://".$_SERVER['HTTP_HOST']."/reportes/plancheta_activo_fijo_agrupado_pdf_html/".$ceco_id);				
				$dompdf = new DOMPDF();
				$dompdf->load_html($html);
				$dompdf->render();				
				$dompdf->stream("Plancheta_Activo_Fijo_".date('d_m_Y_H_i_s').".pdf");
								
			} catch (DOMPDF_Exception $e) {
				echo $e->getMessage();
			}

		} else {
			$this->Session->setFlash(__('No se puede generar el logo para el reporte', true));
			$this->redirect(array('action' => 'stock'));
		}
	}
	
	function plancheta_activo_fijo_agrupado_pdf_html($ceco_id) {
		$this->layout = 'ajax';
		ini_set('memory_limit', '2048M');
		
		// sacamos info del centro de costo
		$info_cc = $this->CentroCosto->read(null, $ceco_id);
		$this->set('info_cc', $info_cc);
		
		// obtenemos activos fijos
		$activos_fijos = $this->UbicacionActivoFijo->planchetaAgrupada($ceco_id);
		
		$conditions = array("CentroCosto.ceco_id" => $ceco_id, "Responsable.esre_id" => 1);
		$responsables = $this->Responsable->find('all', array('order' => 'Responsable.resp_id', 'conditions' => $conditions));
		$ubicacion = $this->Responsable->CentroCosto->findUbicacion($ceco_id);
		$busca_responsables = $this->Responsable->CentroCosto->buscaResponsable($ceco_id);
		
		$this->set('responsables', $responsables);
		$this->set('activos_fijos', $activos_fijos);
		$this->set('ubicacion', $ubicacion);
		$this->set('busca_responsables', $busca_responsables);
		ob_clean();
	}
	
	function financiamiento() {
		if (!empty($this->data)) {
			$this->Financiamiento->set($this->data);
			
			if ($this->Financiamiento->validates()) {
				$fina_id = $this->data['Financiamiento']['fina_nombre'];
				$this->redirect(array('action' => 'financiamientos_excel', $fina_id));
			} else {
				$this->Session->setFlash(__('No se pudo continuar. Por favor intentelo nuevamente.', true));				
			}
		}
		$financiamientos = $this->Financiamiento->find('list');
		$this->set('financiamientos', $financiamientos);
	}
	
	function financiamientos_excel($fina_id) {
		$this->layout = 'ajax';
		set_time_limit(0);
		ini_set('memory_limit', '1024M');
		
		$info = $this->Reporte->reporte_financiamiento($fina_id);
		
		$workbook = new Spreadsheet_Excel_Writer();		
		$worksheet = $workbook->addWorksheet('Libro 1');
		$worksheet->setOutline(true, true, true, true);
		
		// Cabeceras de tabla
		$format_head =& $workbook->addFormat();
		$format_head->setFgColor('yellow');
		$format_head->setBorder(1);
		
		// Formato Titulo
		$format_titulo = $workbook->addFormat();
		$format_titulo->setBold(1);
		
		// Formato de celdas
		$format_cell = $workbook->addFormat();
		$format_cell->setBorder(1);
		
		// Titulo
		$worksheet->write(0, 1, "GASTOS HISTORICOS POR FUENTE DE FINANCIAMIENTO", $format_titulo);
		
		// Encabezado
		$worksheet->write(3, 0, "Código", $format_head);
		$worksheet->write(3, 1, "Nombre Producto", $format_head);
		$worksheet->write(3, 2, "Fecha", $format_head);
		$worksheet->write(3, 3, "Año", $format_head);
		$worksheet->write(3, 4, "Color", $format_head);
		$worksheet->write(3, 5, "Marca", $format_head);
		$worksheet->write(3, 6, "Propiedad", $format_head);
		$worksheet->write(3, 7, "Modelo", $format_head);			
		$worksheet->write(3, 8, "Situación", $format_head);
		$worksheet->write(3, 9, "Financiamiento", $format_head);
		$worksheet->write(3, 10, "Precio", $format_head);
		$worksheet->write(3, 11, "Depreciable", $format_head);
		$worksheet->write(3, 12, "Vida Útil", $format_head);
		$worksheet->write(3, 13, "Fecha de Garantia", $format_head);
		$worksheet->write(3, 14, "Fecha de Adquisición", $format_head);
		$worksheet->write(3, 15, "Serie", $format_head);
		$worksheet->write(3, 16, 'Centro de Costo (Unidad)', $format_head);
		$worksheet->write(3, 17, 'Dependencia', $format_head);
		$worksheet->write(3, 18, 'Establecimiento', $format_head);
		
		$row_count = 4;
		$total = 0;
		foreach ($info as $row) {
			$row = array_pop($row);
			
			$worksheet->writeString($row_count, 0, $row['deaf_codigo'], $format_cell);		
			$worksheet->write($row_count, 1, utf8_decode($row['prod_nombre']), $format_cell);
			$worksheet->write($row_count, 2, date('d-m-Y', strtotime($row['acfi_fecha'])), $format_cell);	
			$worksheet->write($row_count, 3, date('Y', strtotime($row['acfi_fecha'])), $format_cell);
			$worksheet->write($row_count, 4, utf8_decode($row['colo_nombre']), $format_cell);
			$worksheet->write($row_count, 5, utf8_decode($row['marc_nombre']), $format_cell);
			$worksheet->write($row_count, 6, utf8_decode($row['prop_nombre']), $format_cell);
			$worksheet->write($row_count, 7, utf8_decode($row['mode_nombre']), $format_cell);
			$worksheet->write($row_count, 8, utf8_decode($row['situ_nombre']), $format_cell);
			$worksheet->write($row_count, 9, utf8_decode($row['fina_nombre']), $format_cell);
			$worksheet->write($row_count, 10, $row['deaf_precio'], $format_cell);
			if ($row['deaf_depreciable'] == 1) {
				$row['deaf_depreciable'] = "Si";
			} else {
				$row['deaf_depreciable'] = "No";
			}			
			$worksheet->write($row_count, 11, $row['deaf_depreciable'], $format_cell);
			$worksheet->write($row_count, 12, $row['deaf_vida_util'], $format_cell);
			if (!empty($row['deaf_fecha_garantia'])) {
				$fecha_garantia = date('d-m-Y', strtotime($row['deaf_fecha_garantia']));
			} else {
				$fecha_garantia = "";
			}
			$worksheet->write($row_count, 13, $fecha_garantia, $format_cell);
			$worksheet->write($row_count, 14, date('d-m-Y', strtotime($row['deaf_fecha_adquisicion'])), $format_cell);
			$worksheet->write($row_count, 15, utf8_decode($row['deaf_serie']), $format_cell);
			$worksheet->write($row_count, 16, utf8_decode($row['hijo']), $format_cell);
			$worksheet->write($row_count, 17, utf8_decode($row['padre']), $format_cell);
			$worksheet->write($row_count, 18, utf8_decode($row['abuelo']), $format_cell);
			$total = $total + $row['deaf_precio'];
			$row_count++;			
		}
		
		$worksheet->writeString($row_count+2, 17, "Valor Total", $format_cell);
		$worksheet->writeString($row_count+2, 18, $total, $format_cell);
		
		ob_clean();
		$workbook->send('Reporte_Financiamiento.xls');
		$workbook->close();
		exit;
	}
}
?>
