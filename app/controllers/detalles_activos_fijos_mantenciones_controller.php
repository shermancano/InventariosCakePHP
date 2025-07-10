<?php
class DetallesActivosFijosMantencionesController extends AppController {
	var $name = 'DetallesActivosFijosMantenciones';
	var $uses = array('ActivoFijoMantencion', 'DetalleActivoFijoMantencion', 'Configuracion');
	
	function index() {
		$this->DetalleActivoFijoMantencion->ActivoFijoMantencion->recursive = 0;
		$ceco_id = $this->Session->read('userdata.CentroCosto.ceco_id');
		
		$this->paginate = array(
			'ActivoFijoMantencion' => array(
				'order' => array('ActivoFijoMantencion.afma_correlativo' => 'desc'),
				'conditions' => array('ActivoFijoMantencion.ceco_id' => $ceco_id)
			)
		);
		$mantenciones = $this->paginate();
		$this->set('mantenciones', $mantenciones);
	}
	
	function add() {
		if (!empty($this->data)) {
			$dataSource = $this->DetalleActivoFijoMantencion->ActivoFijoMantencion->getDataSource();
			$dataSource->begin($this->DetalleActivoFijoMantencion->ActivoFijoMantencion);
			$this->DetalleActivoFijoMantencion->ActivoFijoMantencion->create();
			$ceco_id = $this->data['ActivoFijoMantencion']['ceco_id'];
			
			$this->data['ActivoFijoMantencion']['afma_ano'] = $this->data['ActivoFijoMantencion']['afma_ano']['year'];			
			$this->data['ActivoFijoMantencion']['afma_correlativo'] = $this->DetalleActivoFijoMantencion->ActivoFijoMantencion->obtieneCorrelativo($ceco_id);
			
			if ($this->DetalleActivoFijoMantencion->ActivoFijoMantencion->save($this->data['ActivoFijoMantencion'])) {
				$maaf_id = $this->DetalleActivoFijoMantencion->ActivoFijoMantencion->id;
				$indice = 0;
				
				// Armamos estructura de mantención
				foreach ($this->data['DetalleMantencion'] as $key => $dema) {	
					$this->DetalleActivoFijoMantencion->create();
					$dema['afma_id'] = $maaf_id;
					$data['DetalleActivoFijo'] = $dema;					
					
					if (!$this->DetalleActivoFijoMantencion->save($data['DetalleActivoFijo'])) {							
						$dataSource->rollback($this->DetalleActivoFijoMantencion->ActivoFijoMantencion);							
						$this->Session->setFlash(__('No se pudo guardar parte del detalle de la mantención, por favor inténtelo nuevamente', true));
						$this->redirect(array('action' => 'index'));
					}				
				}
				
				$dataSource->commit($this->DetalleActivoFijoMantencion->ActivoFijoMantencion);	
				$this->Log->write($this->Session->read('userdata.Usuario.usua_id'), utf8_encode('Nueva Mantención de Activo Fijo'), $_REQUEST);
				$this->Session->setFlash(__('La mantención ha sido guardada de forma exitosa.', true));
				$this->redirect(array('action' => 'index'));
				
			} else {
				$dataSource->rollback($this->DetalleActivoFijoMantencion->ActivoFijoMantencion);
				$this->Session->setFlash(__('No se pudo guardar la mantención, por favor inténtelo nuevamente', true));
				$this->redirect(array('action' => 'add'));
			}
		}
		
		$ceco_id = $this->Session->read('userdata.CentroCosto.ceco_id');
		$proveedores = $this->DetalleActivoFijoMantencion->ActivoFijoMantencion->Proveedor->find('list');
		$this->set('ceco_id', $ceco_id);		
		$this->set('proveedores', $proveedores);
	}
	
	function edit($id = null) {
		if (!empty($this->data)) {
			$dataSource = $this->DetalleActivoFijoMantencion->ActivoFijoMantencion->getDataSource();
			$dataSource->begin($this->DetalleActivoFijoMantencion->ActivoFijoMantencion);
			$this->data['ActivoFijoMantencion']['afma_ano'] = $this->data['ActivoFijoMantencion']['afma_ano']['year'];
			
			if ($this->DetalleActivoFijoMantencion->ActivoFijoMantencion->save($this->data['ActivoFijoMantencion'])) {
				$afma_id = $this->DetalleActivoFijoMantencion->ActivoFijoMantencion->id;
				$indice = 0;
				
				// Eliminamos registros anteriores para crear los nuevos
				$this->DetalleActivoFijoMantencion->deleteAll(array('DetalleActivoFijoMantencion.afma_id' => $afma_id));
				
				// Armamos estructura de mantención
				foreach ($this->data['DetalleMantencion'] as $key => $dema) {	
					$this->DetalleActivoFijoMantencion->create();
					$dema['afma_id'] = $afma_id;
					$data['DetalleActivoFijo'] = $dema;					
					
					if (!$this->DetalleActivoFijoMantencion->save($data['DetalleActivoFijo'])) {							
						$dataSource->rollback($this->DetalleActivoFijoMantencion->ActivoFijoMantencion);							
						$this->Session->setFlash(__('No se pudo guardar parte del detalle de la mantención, por favor inténtelo nuevamente', true));
						$this->redirect(array('action' => 'index'));
					}				
				}
				
				$dataSource->commit($this->DetalleActivoFijoMantencion->ActivoFijoMantencion);	
				$this->Log->write($this->Session->read('userdata.Usuario.usua_id'), utf8_encode('Nueva Mantención de Activo Fijo'), $_REQUEST);
				$this->Session->setFlash(__('La mantención ha sido guardada de forma exitosa.', true));
				$this->redirect(array('action' => 'index'));
				
			} else {
				$dataSource->rollback($this->DetalleActivoFijoMantencion->ActivoFijoMantencion);
				$this->Session->setFlash(__('No se pudo guardar la mantención, por favor inténtelo nuevamente', true));
				$this->redirect(array('action' => 'add'));
			}
		} else {
			$this->DetalleActivoFijoMantencion->ActivoFijoMantencion->recursive = 2;
			$this->data = $this->DetalleActivoFijoMantencion->ActivoFijoMantencion->read(null, $id);
			$proveedores = $this->DetalleActivoFijoMantencion->ActivoFijoMantencion->Proveedor->find('list');
			$year = $this->data['ActivoFijoMantencion']['afma_ano'];
			$this->data['ActivoFijoMantencion']['afma_fecha_factura'] = date('d-m-Y', strtotime($this->data['ActivoFijoMantencion']['afma_fecha_factura']));
			$findDetalle = $this->DetalleActivoFijoMantencion->find('all', array('order' => 'DetalleActivoFijoMantencion.dema_id asc', 'conditions' => array('DetalleActivoFijoMantencion.afma_id' => $id)));			
			$this->set('proveedores', $proveedores);
			$this->set('year', $year);
			$this->set('findDetalle', $findDetalle);				
		}
	}
	
	function view($id = null) {
		$this->DetalleActivoFijoMantencion->ActivoFijoMantencion->recursive = 0;
		$infoMantencion = $this->DetalleActivoFijoMantencion->ActivoFijoMantencion->find('first', array('recursive' => 2, 'conditions' => array('ActivoFijoMantencion.afma_id' => $id)));
		$infoDetalleMantencion = $this->DetalleActivoFijoMantencion->find('all', array('conditions' => array('DetalleActivoFijoMantencion.afma_id' => $id)));		
		$this->set('infoMantencion', $infoMantencion);		
		$this->set('infoDetalleMantencion', $infoDetalleMantencion);
	}
	
	function comprobante_mantencion($id = null) {
		$this->layout = "ajax";
		if (!$id) {
			$this->Session->setFlash(__('ID invalido', true));
			$this->redirect(array('action'=>'index_entrada'));
		}
		
		try {
			$pdf = new HTML_ToPDF("http://".$_SERVER['HTTP_HOST']."/detalles_activos_fijos_mantenciones/comprobante_mantencion_pdf/".$id, "http://".$_SERVER['HTTP_HOST']);
			$pdf->setUnderlineLinks(true);
			$pdf->setScaleFactor('0.8');
			$pdf->setUseColor(true);
			$pdf->setFooter('center', 'Pagina $N');
			$pdf->setHeader('center', '&nbsp;');
			$fp = fopen($pdf->convert(), "r");
			
			ob_clean();
			header("Content-type: application/pdf; name=Comprobante_Mantención_".$id.".pdf");
			header("Content-disposition: attachment; filename=Comprobante_Mantención_".$id.".pdf");
			
			if (rewind($fp)) {
				fpassthru($fp);
			}
			fclose($fp);
			
		} catch (HTML_ToPDFException $e) {
			echo $e->getMessage();
		}
	}
	
	function comprobante_mantencion_pdf($id = null) {
		$this->layout = "ajax";
		$this->DetalleActivoFijoMantencion->ActivoFijoMantencion->recursive = 0;
		$infoMantencion = $this->DetalleActivoFijoMantencion->ActivoFijoMantencion->find('first', array('recursive' => 2, 'conditions' => array('ActivoFijoMantencion.afma_id' => $id)));
		$infoDetalleMantencion = $this->DetalleActivoFijoMantencion->find('all', array('conditions' => array('DetalleActivoFijoMantencion.afma_id' => $id)));		
		$this->set('infoMantencion', $infoMantencion);		
		$this->set('infoDetalleMantencion', $infoDetalleMantencion);		
		$logo = $this->Configuracion->obtieneLogo();
		$fp = fopen($_SERVER['DOCUMENT_ROOT']."/app/webroot/files/logo.png", "w");
		fputs($fp, base64_decode($logo));
		fclose($fp);
	}
	
	function delete($id = null) {
		if (!$id) {
			$this->Session->setFlash(__('ID invalido', true));
			$this->redirect(array('action'=>'index'));
		}
		if ($this->DetalleActivoFijoMantencion->ActivoFijoMantencion->delete($id)) {
			$this->Log->write($this->Session->read('userdata.Usuario.usua_id'), utf8_encode('Eliminación Mantención'), $_REQUEST);
			$this->Session->setFlash(__('La mantención ha sido eliminada.', true));
			$this->redirect(array('action'=>'index'));
		}
		$this->Session->setFlash(__('No se pudo eliminar la mantención.', true));
		$this->redirect(array('action' => 'index'));
	}
}
?>