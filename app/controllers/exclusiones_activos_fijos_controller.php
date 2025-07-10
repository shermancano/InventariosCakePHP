<?php

class ExclusionesActivosFijosController extends AppController {
	var $name = 'ExclusionesActivosFijos';
	var $uses = array('ExclusionActivoFijo', 'DetalleExclusion', 'DetalleBaja', 'MotivoBaja', 'DependenciaVirtual');
	
	var $paginate = array(
		'ExclusionActivoFijo' => array(
			'order' => array('ExclusionActivoFijo.exaf_fecha' => 'desc')
		),
		'DetalleExclusion' => array(
			'limit' => 10,
			'order' => array('Producto.prod_nombre' => 'asc') 
		)
	);
	
	function index() {
		$this->ExclusionActivoFijo->recursive = 0;
		$centros_costos = $this->Session->read('userdata.CentroCosto.ceco_id');
		$exclusiones_activos_fijos = $this->paginate('ExclusionActivoFijo', array('AND' => array('ExclusionActivoFijo.ceco_id' => $centros_costos , 'ExclusionActivoFijo.tmov_id' => 5)));
		$this->set('exclusiones_activos_fijos', $exclusiones_activos_fijos);
		$this->set('ceco_id', $this->Session->read('userdata.CentroCosto.ceco_id'));
	}
	
	function view($id) {
		if (!$id) {
			$this->Session->setFlash(__('Id invalido', true));
			$this->redirect(array('action' => 'index'));
		}
		
		$this->ExclusionActivoFijo->recursive = 0;
		$entradas = $this->ExclusionActivoFijo->read(null, $id);
		$this->set('entrada', $entradas);
		
		$detalles = $this->paginate('DetalleExclusion', array('AND' => array('DetalleExclusion.exaf_id' => $id)));
		$this->set('detalles', $detalles);
	}
	
	function codigos_barra_exclusion() {
		$this->layout = "ajax";		
		$data = $this->data;
		print_r(json_encode($data));
		exit;	
	}
	
	function add() {
		if (!empty($this->data)) {			
			$tmov_id = 5; // Tipo de Movimiento "Exclusión" = 5			
			$ceco_id = $this->data['ExclusionActivoFijo']['ceco_id'];
			$this->data['ExclusionActivoFijo']['tmov_id'] = $tmov_id; 
			$this->data['ExclusionActivoFijo']['exaf_correlativo'] = $this->ExclusionActivoFijo->obtieneCorrelativo($ceco_id, $tmov_id);
			$this->data['ExclusionActivoFijo']['esre_id'] = 1;
			$this->data['ExclusionActivoFijo']['exaf_fecha'] = date('Y-m-d H:i:s');
		
			$dataSource = $this->ExclusionActivoFijo->getDataSource();
			$dataSource->begin($this->ExclusionActivoFijo);
			$this->ExclusionActivoFijo->create();
			
			if ($this->ExclusionActivoFijo->save($this->data['ExclusionActivoFijo'])) {
				$exaf_id = $this->ExclusionActivoFijo->id;
				
				$excluidos = array();
				$indice = 0;
				$cantidad_excluidos = 0;		
				
				// Armamos estructura de bajas
				foreach ($this->data['DetalleExclusion'] as $key => $row) {			
					if ($row['dete_check'] == 1) {
						$this->ExclusionActivoFijo->DetalleExclusion->create();
						
						$excluidos['DetalleExclusion'][$indice]['exaf_id'] = $exaf_id;
						$excluidos['DetalleExclusion'][$indice]['dete_codigo'] = $row['dete_codigo'];
						$excluidos['DetalleExclusion'][$indice]['prod_id'] = $row['prod_id'];
						$excluidos['DetalleExclusion'][$indice]['prop_id'] = $row['prop_id'];
						$excluidos['DetalleExclusion'][$indice]['situ_id'] = $row['situ_id'];
						$excluidos['DetalleExclusion'][$indice]['marc_id'] = $row['marc_id'];
						$excluidos['DetalleExclusion'][$indice]['colo_id'] = $row['colo_id'];
						$excluidos['DetalleExclusion'][$indice]['mode_id'] = $row['mode_id'];
						$excluidos['DetalleExclusion'][$indice]['dete_fecha_garantia'] = $row['dete_fecha_garantia'];
						$excluidos['DetalleExclusion'][$indice]['dete_precio'] = $row['dete_precio']; 
						$excluidos['DetalleExclusion'][$indice]['dete_depreciable'] = $row['dete_depreciable'];
						$excluidos['DetalleExclusion'][$indice]['dete_vida_util'] = $row['dete_vida_util'];
						$excluidos['DetalleExclusion'][$indice]['dete_fecha_adquisicion'] = $row['dete_fecha_adquisicion'];
						$excluidos['DetalleExclusion'][$indice]['dete_serie'] = $row['dete_serie'];
						$excluidos['DetalleExclusion'][$indice]['dete_valor_baja'] = 0; // Bien queda depreciado.
						$codigo_barra = $row['dete_codigo'];
						
						if (!$this->ExclusionActivoFijo->DetalleExclusion->save($excluidos['DetalleExclusion'][$indice])) {							
							$dataSource->rollback($this->ExclusionActivoFijo);							
							$this->Session->setFlash(__(utf8_encode('No se pudo guardar parte del detalle de la exclusión, por favor inténtelo nuevamente'), true));
							$this->redirect(array('action' => 'index'));
						}								
						
						// Eliminamos el bien de ubicaciones_activos_fijos y detalle_bajas
						$this->ExclusionActivoFijo->deleteCodigoBarra($codigo_barra);				
						$indice++;
						$cantidad_excluidos++;
					}				
				}
			
				if ($cantidad_excluidos > 0) {				
					$dataSource->commit($this->ExclusionActivoFijo);
					$this->Log->write($this->Session->read('userdata.Usuario.usua_id'), utf8_encode('Nueva Exclusión de Activo Fijo'), $_REQUEST);
					$this->Session->setFlash(__(utf8_encode('Las exclusiones han sido guardadas exitosamente.'), true));
					$this->redirect(array('action' => 'index'));
				} else {
					$dataSource->rollback($this->ExclusionActivoFijo);
					$this->Session->setFlash(__(utf8_encode('No se pudo guardar la exclusión, debe seleccionar los bienes del listado.'), true));
					$this->redirect(array('action' => 'add'));
				}							
			} else {
				$dataSource->rollback($this->ExclusionActivoFijo);
				$this->Session->setFlash(__(utf8_encode('No se pudo guardar la exclusión, por favor inténtelo nuevamente'), true));
				$this->redirect(array('action' => 'add'));
			}	
		}		
		
		$ceco_id = $this->Session->read('userdata.CentroCosto.ceco_id');
		$this->DetalleBaja->recursive = 0;
		$conds = array('AND' => array('BajaActivoFijo.ceco_id' => $ceco_id));
		$detalles = $this->paginate('DetalleBaja', $conds);
		$motivos = $this->MotivoBaja->find('list');
		$dependencias = $this->DependenciaVirtual->find('list');
		$findBodegas = $this->ExclusionActivoFijo->findBodegasBajas();
		$centros_costos = $this->ExclusionActivoFijo->CentroCosto->find('list', array('fields' => array('CentroCosto.ceco_id', 'CentroCosto.ceco_nombre')));
		$bodegas = array();
		foreach ($findBodegas as $row) {
			$row = array_pop($row);
			if ($row['ceco_id'] != $ceco_id) {
				$bodegas[$row['ceco_id']] = $row['ceco_nombre'];
			}
		}
	
		$this->set('detalles', $detalles);
		$this->set('motivos', $motivos);
		$this->set('dependencias', $dependencias);
		$this->set('bodegas', $bodegas);
		$this->set('centros_costos', $centros_costos);		
	}

	function comprobante_exclusion_pdf($exaf_id = null) {
		$this->layout = "ajax";
		
		try {
			$pdf = new HTML_ToPDF("http://".$_SERVER['HTTP_HOST']."/exclusiones_activos_fijos/comprobante_exclusion/".$exaf_id, "http://".$_SERVER['HTTP_HOST']);
			$pdf->setUnderlineLinks(true);
			$pdf->setScaleFactor('0.8');
			$pdf->setUseColor(true);
			$pdf->setFooter('center', 'Pagina $N');
			$pdf->setHeader('center', '&nbsp;');
			$fp = fopen($pdf->convert(), "r");
			
			ob_clean();
			header("Content-type: application/pdf; name=Comprobante_Exclusion_de_Inventario_".$exaf_id.".pdf");
			header("Content-disposition: attachment; filename=Comprobante_Exclusion_de_Inventario_".$exaf_id.".pdf");
			
			if (rewind($fp)) {
				fpassthru($fp);
			}
			fclose($fp);
			
		} catch (HTML_ToPDFException $e) {
			echo $e->getMessage();
		}
	}
	
	function comprobante_exclusion($exaf_id) {
		$this->layout = "ajax";
		$this->ExclusionActivoFijo->recursive = 0;
		$info = $this->ExclusionActivoFijo->find('first', array('conditions' => array('ExclusionActivoFijo.exaf_id' => $exaf_id)));
		
		$infoDetalleExclusion = $this->ExclusionActivoFijo->DetalleExclusion->find('all', array('conditions' => array('DetalleExclusion.exaf_id' => $exaf_id), 'order' => 'Producto.prod_nombre asc'));
		$param_iva = $this->Configuracion->find('first', array('conditions' => array('Configuracion.conf_id' => 'param_iva')));
		
		if (sizeof($param_iva) > 0 && is_array($param_iva)) {
			$valor_iva = $param_iva['Configuracion']['conf_valor'];
		} else {
			$valor_iva = 0;
		}
		
		$this->set("info" ,$info);
		$this->set("infoDetalleExclusion", $infoDetalleExclusion);
		$this->set('valor_iva', $valor_iva);
		
		$logo = $this->Configuracion->obtieneLogo();
		$fp = fopen($_SERVER['DOCUMENT_ROOT']."/app/webroot/files/logo.png", "w");
		fputs($fp, base64_decode($logo));
		fclose($fp);
	}
	
}
?>