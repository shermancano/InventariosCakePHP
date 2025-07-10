<?php
class OrdenesComprasController extends AppController {
	var $name = 'OrdenesCompras';
	var $uses = array('OrdenCompra', 'DetalleOrdenCompra');
	var $paginate = array('OrdenCompra' => array('order' => array('OrdenCompra.orco_fecha' => 'desc')));

	function index() {
		// no hay filtro por centro de costo
		$this->OrdenCompra->recursive = 0;
		$ordenes = $this->paginate();
		$this->set('ordenes', $ordenes);
	}

	function view($id = null) {
		if (!$id) {
			$this->Session->setFlash(__('Id invalido', true));
			$this->redirect(array('action' => 'index'));
		}
		
		$this->OrdenCompra->recursive = 2;
		$orden = $this->OrdenCompra->read(null, $id);
		$this->set('orden', $orden);
	}

	function add() {
		if (!empty($this->data)) {
			$this->data['OrdenCompra']['orco_fecha'] = date("Y-m-d H:i:s");
			$this->OrdenCompra->create();
			$this->OrdenCompra->set($this->data);
			
			if (!isset($this->data['DetalleOrdenCompra']) || sizeof($this->data['DetalleOrdenCompra']) == 0) {
				$this->Session->setFlash(__('Debe ingresar el detalle', true));
			} else {
			
				if ($this->OrdenCompra->validates()) {
					$dataSource = $this->OrdenCompra->getDataSource();
					$dataSource->begin($this->OrdenCompra);
				
					if ($this->OrdenCompra->save($this->data)) {
						$orco_id = $this->OrdenCompra->id;
						
						if ($this->OrdenCompra->DetalleOrdenCompra->save($orco_id, $this->data)) {
							$dataSource->commit($this->OrdenCompra);
							$this->Log->write($this->Session->read('userdata.Usuario.usua_id'), 'Nueva orden de compra', $_REQUEST);
							$this->Session->setFlash(__('La orden de compra ha sido guardada', true));
							$this->redirect(array('action' => 'index'));
							
						} else {
							$dataSource->rollback($this->OrdenCompra);
							$this->Session->setFlash(__('No se pudo guardar la orden de compra, por favor inténtelo nuevamente', true));
						}
					} else {
						$dataSource->rollback($this->OrdenCompra);
						$this->Session->setFlash(__('No se pudo guardar la orden de compra, por favor inténtelo nuevamente', true));
					}
				}
			}
		}
		
		$centros_costos = $this->Session->read('userdata.selectCC');
		$proveedores = $this->OrdenCompra->Proveedor->find('list', array('order' => array('Proveedor.prov_nombre' => 'asc')));
		$metodos_despachos = $this->OrdenCompra->MetodoDespacho->find('list', array('order' => array('MetodoDespacho.mede_descripcion' => 'asc')));
		$formas_pagos = $this->OrdenCompra->FormaPago->find('list', array('order' => array('FormaPago.fopa_descripcion' => 'asc')));
		$financiamientos = $this->OrdenCompra->Financiamiento->find('list', array('order' => array('Financiamiento.fina_nombre' => 'asc')));
		$unidades = $this->OrdenCompra->DetalleOrdenCompra->Unidad->find('list', array('order' => array('Unidad.unid_id' => 'asc')));
		
		$this->set(compact('centros_costos', 'proveedores', 'metodos_despachos', 'formas_pagos', 'financiamientos', 'unidades'));
		
	}
	
	/*
	function edit($id = null) {
		if (!$id && empty($this->data)) {
			$this->Session->setFlash(__('ID invalido', true));
			$this->redirect(array('action' => 'index'));
		}
		if (!empty($this->data)) {
			$this->Banco->set($this->data);
			
			if ($this->Banco->validates()) {
				if ($this->Banco->save($this->data)) {
					$this->Log->write($this->Session->read('userdata.Usuario.usua_id'), 'Modificación Banco', $_REQUEST);
					$this->Session->setFlash(__('El banco ha sido guardado', true));
					$this->redirect(array('action' => 'index'));
				} else {
					$this->Session->setFlash(__('No se pudo guardar el banco, por favor inténtelo nuevamente', true));
				}
			}
		}
		if (empty($this->data)) {
			$this->data = $this->Banco->read(null, $id);
		}
	}
	*/

	function delete($id = null) {
		if (!$id) {
			$this->Session->setFlash(__('ID invalido', true));
			$this->redirect(array('action'=>'index'));
		}
		if ($this->OrdenCompra->delete($id)) {
			$this->Log->write($this->Session->read('userdata.Usuario.usua_id'), 'Eliminación de Orden de Compra', $_REQUEST);
			$this->Session->setFlash(__('Orden de Compra eliminada', true));
			$this->redirect(array('action'=>'index'));
		}
		$this->Session->setFlash(__('No se pudo eliminar la orden de compra', true));
		$this->redirect(array('action' => 'index'));
	}
	
	function comprobante($orco_id = null) {
		if (!$orco_id) {
			$this->Session->setFlash(__('Id invalido', true));
			$this->redirect(array('action' => 'index'));
		}
		
		try {
			$pdf = new HTML_ToPDF("http://".$_SERVER['HTTP_HOST']."/ordenes_compras/comprobante_pdf/".$orco_id, "http://".$_SERVER['HTTP_HOST']);
			$pdf->setUnderlineLinks(true);
			$pdf->setScaleFactor('0.8');
			$pdf->setUseColor(true);
			$pdf->setFooter('center', 'P&aacute;gina $N');
			$pdf->setHeader('center', '&nbsp;');
			$fp = fopen($pdf->convert(), "r");
			
			header("Content-type: application/pdf; name=Comprobante_Orden_Compra_".$orco_id.".pdf");
			header("Content-disposition: attachment; filename=Comprobante_Orden_Compra_".$orco_id.".pdf");
			
			if (rewind($fp)) {
				fpassthru($fp);
			}
			fclose($fp);
			
		} catch (HTML_ToPDFException $e) {
			echo $e->getMessage();
		}
	}
	
	function comprobante_pdf($orco_id) {
		$this->layout = "ajax";
		$this->OrdenCompra->recursive = 2;
		$info = $this->OrdenCompra->find('first', array('conditions' => array('OrdenCompra.orco_id' => $orco_id)));
		$info_deoc = $this->OrdenCompra->DetalleOrdenCompra->find('all', array('conditions' => array('DetalleOrdenCompra.orco_id' => $orco_id)));
		$this->set("info" ,$info);
		$this->set("info_deoc" ,$info_deoc);
		
		$logo = $this->Configuracion->obtieneLogo();
		$fp = fopen($_SERVER['DOCUMENT_ROOT']."/app/webroot/files/logo.png", "w");
		fputs($fp, base64_decode($logo));
		fclose($fp);
	}
}
?>