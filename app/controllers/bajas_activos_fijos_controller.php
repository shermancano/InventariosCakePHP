<?php

class BajasActivosFijosController extends AppController {
	var $name = 'BajasActivosFijos';
	var $uses = array('BajaActivoFijo', 'ExclusionActivoFijo', 'UbicacionActivoFijo', 'MotivoBaja', 'DependenciaVirtual');
	
	var $paginate = array(
		'BajaActivoFijo' => array(
			'order' => array('BajaActivoFijo.baaf_fecha' => 'desc')
		),
		'DetalleBaja' => array(
			'limit' => 10,
			'order' => array('Producto.prod_nombre' => 'asc') 
		)
	);
	
	function index() {
		$this->BajaActivoFijo->recursive = 0;
		$centros_costos = $this->Session->read('userdata.CentroCosto.ceco_id');
		$bajas_activos_fijos = $this->paginate('BajaActivoFijo', array('AND' => array('BajaActivoFijo.ceco_id' => $centros_costos , 'BajaActivoFijo.tmov_id' => 3)));
		$this->set('bajas_activos_fijos', $bajas_activos_fijos);
		$this->set('ceco_id', $this->Session->read('userdata.CentroCosto.ceco_id'));
	}
	
	function view($id) {
		if (!$id) {
			$this->Session->setFlash(__('Id invalido', true));
			$this->redirect(array('action' => 'index'));
		}
		
		$this->BajaActivoFijo->recursive = 0;
		$entradas = $this->BajaActivoFijo->read(null, $id);
		$this->set('entrada', $entradas);
		
		$detalles = $this->paginate('DetalleBaja', array('AND' => array('DetalleBaja.baaf_id' => $id)));
		$this->set('detalles', $detalles);
	}
	
	function codigos_barra_bajas() {
		$this->layout = "ajax";		
		$data = $this->data;
		print_r(json_encode($data));
		exit;	
	}
	
	function add() {
		if (!empty($this->data)) {			
			$tmov_id = 3; // Tipo de Movimiento "Baja" = 3
			$ceco_id = $this->data['BajaActivoFijo']['ceco_id'];
			$this->data['BajaActivoFijo']['tmov_id'] = $tmov_id; 
			$this->data['BajaActivoFijo']['baaf_correlativo'] = $this->BajaActivoFijo->obtieneCorrelativo($ceco_id, $tmov_id);
			$this->data['BajaActivoFijo']['esre_id'] = 1;
			$this->data['BajaActivoFijo']['baaf_fecha'] = date('Y-m-d H:i:s');
		
			$dataSource = $this->BajaActivoFijo->getDataSource();
			$dataSource->begin($this->BajaActivoFijo);
			$this->BajaActivoFijo->create();
			
			if ($this->BajaActivoFijo->save($this->data['BajaActivoFijo'])) {
				$baaf_id = $this->BajaActivoFijo->id;
				
				$bajas = array();
				$indice = 0;
				$cantidad_bajas = 0;		
				
				// Armamos estructura de bajas
				foreach ($this->data['DetalleBaja'] as $key => $row) {			
					if ($row['deba_check'] == 1) {
						$this->BajaActivoFijo->DetalleBaja->create();
						
						$bajas['DetalleBaja'][$indice]['baaf_id'] = $baaf_id;
						$bajas['DetalleBaja'][$indice]['deba_codigo'] = $row['deba_codigo'];
						$bajas['DetalleBaja'][$indice]['prod_id'] = $row['prod_id'];
						$bajas['DetalleBaja'][$indice]['prop_id'] = $row['prop_id'];
						$bajas['DetalleBaja'][$indice]['situ_id'] = $row['situ_id'];
						$bajas['DetalleBaja'][$indice]['marc_id'] = $row['marc_id'];
						$bajas['DetalleBaja'][$indice]['colo_id'] = $row['colo_id'];
						$bajas['DetalleBaja'][$indice]['mode_id'] = $row['mode_id'];
						$bajas['DetalleBaja'][$indice]['deba_fecha_garantia'] = $row['deba_fecha_garantia'];
						$bajas['DetalleBaja'][$indice]['deba_precio'] = $row['deba_precio']; 
						$bajas['DetalleBaja'][$indice]['deba_depreciable'] = $row['deba_depreciable'];
						$bajas['DetalleBaja'][$indice]['deba_vida_util'] = $row['deba_vida_util'];
						$bajas['DetalleBaja'][$indice]['deba_fecha_adquisicion'] = $row['deba_fecha_adquisicion'];
						$bajas['DetalleBaja'][$indice]['deba_serie'] = $row['deba_serie'];
						$bajas['DetalleBaja'][$indice]['deba_valor_baja'] = 1; // Bien se deprecia a 1 peso.
						$codigo_barra = $row['deba_codigo'];
						
						if (!$this->BajaActivoFijo->DetalleBaja->save($bajas['DetalleBaja'][$indice])) {							
							$dataSource->rollback($this->BajaActivoFijo);							
							$this->Session->setFlash(__(utf8_encode('No se pudo guardar parte del detalle de la baja, por favor inténtelo nuevamente'), true));
							$this->redirect(array('action' => 'index'));
						}
						
						// Actualizamos el valor del bien a 1 peso.					
						$this->BajaActivoFijo->updateValorActivoFijo($codigo_barra, $ceco_id);					
						$indice++;
						$cantidad_bajas++;
					}				
				}
				
				if ($cantidad_bajas > 0) {				
					$dataSource->commit($this->BajaActivoFijo);
					$this->Log->write($this->Session->read('userdata.Usuario.usua_id'), utf8_encode('Nueva Baja de Activo Fijo'), $_REQUEST);
					$this->Session->setFlash(__(utf8_encode('Las bajas han sido guardadas exitosamente.'), true));
					$this->redirect(array('action' => 'index'));
				} else {
					$dataSource->rollback($this->BajaActivoFijo);
					$this->Session->setFlash(__(utf8_encode('No se pudo guardar la baja, debe seleccionar los bienes del listado.'), true));
					$this->redirect(array('action' => 'add'));
				}							
			} else {
				$dataSource->rollback($this->BajaActivoFijo);
				$this->Session->setFlash(__(utf8_encode('No se pudo guardar la baja, por favor inténtelo nuevamente'), true));
				$this->redirect(array('action' => 'add'));
			}	
		}		
		
		$ceco_id = $this->Session->read('userdata.CentroCosto.ceco_id');
		$this->UbicacionActivoFijo->recursive = 0;
		$conds = array('AND' => array('UbicacionActivoFijo.ceco_id' => $ceco_id));
		$detalles = $this->paginate('UbicacionActivoFijo', $conds);
		$motivos = $this->MotivoBaja->find('list');
		$dependencias = $this->DependenciaVirtual->find('list');
		$findBodegas = $this->BajaActivoFijo->findBodegasBajas();
		$centros_costos = array();
		foreach ($findBodegas as $row) {
			$centros_costos[$row[0]['ceco_id']] = $row[0]['ceco_nombre']; 
		}
		$this->set('detalles', $detalles);
		$this->set('motivos', $motivos);
		$this->set('dependencias', $dependencias);
		$this->set('centros_costos', $centros_costos);
	}

	function comprobante_baja_pdf($baaf_id = null) {
		$this->layout = "ajax";
		
		try {
			$pdf = new HTML_ToPDF("http://".$_SERVER['HTTP_HOST']."/bajas_activos_fijos/comprobante_baja/".$baaf_id, "http://".$_SERVER['HTTP_HOST']);
			$pdf->setUnderlineLinks(true);
			$pdf->setScaleFactor('0.8');
			$pdf->setUseColor(true);
			$pdf->setFooter('center', 'Pagina $N');
			$pdf->setHeader('center', '&nbsp;');
			$fp = fopen($pdf->convert(), "r");
			
			ob_clean();
			header("Content-type: application/pdf; name=Comprobante_Baja_de_Inventario_".$baaf_id.".pdf");
			header("Content-disposition: attachment; filename=Comprobante_Baja_de_Inventario_".$baaf_id.".pdf");
			
			if (rewind($fp)) {
				fpassthru($fp);
			}
			fclose($fp);
			
		} catch (HTML_ToPDFException $e) {
			echo $e->getMessage();
		}
	}
	
	function comprobante_baja($baaf_id) {
		$this->layout = "ajax";
		$this->BajaActivoFijo->recursive = 0;
		$info = $this->BajaActivoFijo->find('first', array('conditions' => array('BajaActivoFijo.baaf_id' => $baaf_id)));
		
		$info_deba = $this->BajaActivoFijo->DetalleBaja->find('all', array('conditions' => array('BajaActivoFijo.baaf_id' => $baaf_id), 'order' => 'Producto.prod_nombre asc'));
		$param_iva = $this->Configuracion->find('first', array('conditions' => array('Configuracion.conf_id' => 'param_iva')));
		
		if (sizeof($param_iva) > 0 && is_array($param_iva)) {
			$valor_iva = $param_iva['Configuracion']['conf_valor'];
		} else {
			$valor_iva = 0;
		}
		
		$this->set("info" ,$info);
		$this->set("info_deba", $info_deba);
		$this->set('valor_iva', $valor_iva);
		
		$logo = $this->Configuracion->obtieneLogo();
		$fp = fopen($_SERVER['DOCUMENT_ROOT']."/app/webroot/files/logo.png", "w");
		fputs($fp, base64_decode($logo));
		fclose($fp);
	}
}
?>