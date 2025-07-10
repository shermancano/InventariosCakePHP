<?php

set_time_limit(0);

class ContratosController extends AppController {
	var $name = 'Contratos';
	var $paginate = array(
		'Contrato' => array('order' => array('Contrato.cont_id' => 'desc'))
	); 

	function index() {
		$this->Contrato->recursive = 0;
		$this->set('contratos', $this->paginate());
	}

	function view($id = null) {
		if (!$id) {
			$this->Session->setFlash(__('Contrato invalido', true));
			$this->redirect(array('action' => 'index'));
		}
		$nota_final = $this->Contrato->Evaluacion->getNotaFinal($id);
		$this->set('nota_final', $nota_final);
		$this->set('contrato', $this->Contrato->read(null, $id));
	}

	function add() {
		if (!empty($this->data)) {
			$this->Contrato->create();
			$this->Contrato->set($this->data);
			
			if ($this->Contrato->validates()) {
				if ($this->Contrato->save($this->data)) {
					$cont_id = $this->Contrato->id;
					if ($this->Contrato->Documento->saveDocumento($cont_id, $this->data)) {
						$this->Log->write($this->Session->read('userdata.Usuario.usua_id'), 'Nuevo Contrato', $_REQUEST);
						$this->Session->setFlash(__('El contrato ha sido guardado', true));
						$this->redirect(array('action' => 'index'));
					} else {
						$this->Session->setFlash(__(utf8_encode('No se pudo guardar el contrato, por favor inténtelo nuevamente'), true));
					}
				} else {
					$this->Session->setFlash(__(utf8_encode('No se pudo guardar el contrato, por favor inténtelo nuevamente'), true));
				}	
				
			} 
		}
		$mod_compras = $this->Contrato->ModalidadCompra->find('all');
		$combo_mod_compra = array();
		
		foreach ($mod_compras as $mod_compra) {
			$combo_mod_compra[$mod_compra['ModalidadCompra']['moco_id']] = $mod_compra['ModalidadCompra']['moco_descripcion'];
		}
		$this->set('combo_mod_compra', $combo_mod_compra);
		
		$tipo_contratos = $this->Contrato->TipoContrato->find('all');
		$combo_tipo_contrato = array();
		
		foreach ($tipo_contratos as $tipo_contrato) {
			$combo_tipo_contrato[$tipo_contrato['TipoContrato']['tico_id']] = $tipo_contrato['TipoContrato']['tico_descripcion'];
		}
		$this->set('combo_tipo_contrato', $combo_tipo_contrato);
		
		$proveedores = $this->Contrato->Proveedor->find('all');
		$combo_proveedores = array();
		
		foreach ($proveedores as $proveedor) {
			$combo_proveedores[$proveedor['Proveedor']['prov_id']] = $proveedor['Proveedor']['prov_nombre'];
		}
		$this->set('combo_proveedores', $combo_proveedores);
		
		$rubros = $this->Contrato->Rubro->find('all');
		$combo_rubros = array();
		
		foreach ($rubros as $rubro) {
			$combo_rubros[$rubro['Rubro']['rubr_id']] = $rubro['Rubro']['rubr_descripcion'];
		}
		$this->set('combo_rubros', $combo_rubros);
		
		$tipo_renovaciones = $this->Contrato->TipoRenovacion->find('all');
		$combo_tipo_renovacion = array();
		
		foreach ($tipo_renovaciones as $tipo_renovacion) {
			$combo_tipo_renovacion[$tipo_renovacion['TipoRenovacion']['tire_id']] = $tipo_renovacion['TipoRenovacion']['tire_descripcion'];
		}
		$this->set('combo_tipo_renovacion', $combo_tipo_renovacion);
		
		$tipo_montos = $this->Contrato->TipoMonto->find('all');
		$combo_tipo_monto = array();
		
		foreach ($tipo_montos as $tipo_monto) {
			$combo_tipo_monto[$tipo_monto['TipoMonto']['timo_id']] = $tipo_monto['TipoMonto']['timo_descripcion'];
		}
		$this->set('combo_tipo_monto', $combo_tipo_monto);
		
		$unid_compras = $this->Contrato->UnidadCompra->find('all');
		$combo_unid_compras = array();
		
		foreach ($unid_compras as $unid_compra) {
			$combo_unid_compras[$unid_compra['UnidadCompra']['unco_id']] = $unid_compra['UnidadCompra']['unco_descripcion'];
		}
		$this->set('combo_unid_compras', $combo_unid_compras);
		
		$bancos = $this->Contrato->Banco->find('all');
		$combo_bancos = array("" => "");
		
		foreach ($bancos as $banco) {
			$combo_bancos[$banco['Banco']['banc_id']] = $banco['Banco']['banc_nombre'];
		}
		$this->set('combo_bancos', $combo_bancos);
		
		$combo_tipo_monto_garantia = array("" => "");
		foreach ($tipo_montos as $tipo_monto) {
			$combo_tipo_monto_garantia[$tipo_monto['TipoMonto']['timo_id']] = $tipo_monto['TipoMonto']['timo_descripcion'];
		}
		$this->set('combo_tipo_monto_garantia', $combo_tipo_monto_garantia);
	}

	function edit($id = null) {
		if (!$id && empty($this->data)) {
			$this->Session->setFlash(__('ID invalido', true));
			$this->redirect(array('action' => 'index'));
		}
		if (!empty($this->data)) {
			$this->Contrato->set($this->data);
			
			if ($this->data['Contrato']['cont_multas'] == 0) {
				$this->data['Contrato']['cont_detalle_multas'] = null;	
			}
			
			if ($this->Contrato->validates()) {
				if ($this->Contrato->save($this->data)) {
					$cont_id = $this->Contrato->id;
					
					if ($this->Contrato->Documento->saveDocumento($cont_id, $this->data)) {
						$this->Log->write($this->Session->read('userdata.Usuario.usua_id'), utf8_encode('Modificación Contrato'), $_REQUEST);
						$this->Session->setFlash(__('El contrato ha sido guardado', true));
						$this->redirect(array('action' => 'index'));
					} else {
						$this->Session->setFlash(__(utf8_encode('No se pudo guardar el contrato, por favor inténtelo nuevamente'), true));
					}
				} else {
					$this->Session->setFlash(__(utf8_encode('No se pudo guardar el contrato, por favor inténtelo nuevamente'), true));
				}
			}
		}
		
		if (empty($this->data)) {
			$this->data = $this->Contrato->read(null, $id);
		}
		
		$this->set('cont_id', $id);
			
		$mod_compras = $this->Contrato->ModalidadCompra->find('all');
		$combo_mod_compra = array();
			
		foreach ($mod_compras as $mod_compra) {
			$combo_mod_compra[$mod_compra['ModalidadCompra']['moco_id']] = $mod_compra['ModalidadCompra']['moco_descripcion'];
		}
		$this->set('combo_mod_compra', $combo_mod_compra);
			
		$tipo_contratos = $this->Contrato->TipoContrato->find('all');
		$combo_tipo_contrato = array();
			
		foreach ($tipo_contratos as $tipo_contrato) {
			$combo_tipo_contrato[$tipo_contrato['TipoContrato']['tico_id']] = $tipo_contrato['TipoContrato']['tico_descripcion'];
		}
		$this->set('combo_tipo_contrato', $combo_tipo_contrato);
			
		$proveedores = $this->Contrato->Proveedor->find('all');
		$combo_proveedores = array();
			
		foreach ($proveedores as $proveedor) {
			$combo_proveedores[$proveedor['Proveedor']['prov_id']] = $proveedor['Proveedor']['prov_nombre'];
		}
		$this->set('combo_proveedores', $combo_proveedores);
			
		$rubros = $this->Contrato->Rubro->find('all');
		$combo_rubros = array();
		
		foreach ($rubros as $rubro) {
			$combo_rubros[$rubro['Rubro']['rubr_id']] = $rubro['Rubro']['rubr_descripcion'];
		}
		$this->set('combo_rubros', $combo_rubros);
		
		$tipo_renovaciones = $this->Contrato->TipoRenovacion->find('all');
		$combo_tipo_renovacion = array();
		
		foreach ($tipo_renovaciones as $tipo_renovacion) {
			$combo_tipo_renovacion[$tipo_renovacion['TipoRenovacion']['tire_id']] = $tipo_renovacion['TipoRenovacion']['tire_descripcion'];
		}
		$this->set('combo_tipo_renovacion', $combo_tipo_renovacion);
		
		$tipo_montos = $this->Contrato->TipoMonto->find('all');
		$combo_tipo_monto = array();
		
		foreach ($tipo_montos as $tipo_monto) {
			$combo_tipo_monto[$tipo_monto['TipoMonto']['timo_id']] = $tipo_monto['TipoMonto']['timo_descripcion'];
		}
		$this->set('combo_tipo_monto', $combo_tipo_monto);
		
		$unid_compras = $this->Contrato->UnidadCompra->find('all');
		$combo_unid_compras = array();
		
		foreach ($unid_compras as $unid_compra) {
			$combo_unid_compras[$unid_compra['UnidadCompra']['unco_id']] = $unid_compra['UnidadCompra']['unco_descripcion'];
		}
		$this->set('combo_unid_compras', $combo_unid_compras);
		
		$bancos = $this->Contrato->Banco->find('all');
		$combo_bancos = array("" => "");
		foreach ($bancos as $banco) {
			$combo_bancos[$banco['Banco']['banc_id']] = $banco['Banco']['banc_nombre'];
		}
		$this->set('combo_bancos', $combo_bancos);
		
		$combo_tipo_monto_garantia = array("" => "");
		foreach ($tipo_montos as $tipo_monto) {
			$combo_tipo_monto_garantia[$tipo_monto['TipoMonto']['timo_id']] = $tipo_monto['TipoMonto']['timo_descripcion'];
		}
		$this->set('combo_tipo_monto_garantia', $combo_tipo_monto_garantia);
		
		$combo_vigente = array("1" => "Vigente", "0" => "Terminado");
		$this->set('combo_vigente', $combo_vigente);
	}

	function delete($id = null) {
		if (!$id) {
			$this->Session->setFlash(__('ID invalido', true));
			$this->redirect(array('action'=>'index'));
		}
		if ($this->Contrato->delete($id)) {
			$this->Log->write($this->Session->read('userdata.Usuario.usua_id'), utf8_encode('Eliminación Contrato'), $_REQUEST);
			$this->Session->setFlash(__('El contrato ha sido eliminado', true));
			$this->redirect(array('action'=>'index'));
		}
		$this->Session->setFlash(__('El contrato no se pudo eliminar', true));
		$this->redirect(array('action' => 'index'));
	}
	
	function excel($cont_id = null) {
		$this->layout = "ajax";
		$info = $this->Contrato->find('first', array('conditions' => array('Contrato.cont_id' => $cont_id)));
		$nota_final = $this->Contrato->Evaluacion->getNotaFinal($cont_id);
		$this->set('nota_final', $nota_final);
		$this->set("info", $info);
		header("Content-type: application/vnd.ms-excel; name=contrato_".$cont_id.".xls");
		header("Content-disposition: attachment; filename=contrato_".$cont_id.".xls");
	}
	
	function searchContratos() {
		$this->layout = "ajax";
		$strings = explode(" ", stripslashes($_GET['term']));
		$info_con = $this->Contrato->searchContrato($strings);
		$contratos = array();
	
		if (sizeof($info_con) > 0) {
			foreach ($info_con as $contrato) {
				$contrato = array_pop($contrato);
				$contratos[] = array("value" => $contrato['cont_nombre'], "label" => $contrato['cont_nombre'], 'cont_id' => $contrato['cont_id']);
			}
		}
		echo json_encode($contratos);
	}
	
	function excelAll() {
		$this->layout = "ajax";	
		$fields = array('Contrato.*', 'TipoRenovacion.*'
					   ,'Rubro.*', 'TipoContrato.*'
					   ,'UnidadCompra.*', 'ModalidadCompra.*'
					   ,'TipoMonto.*', 'TipoMontoGarantia.*'
					   ,'Banco.*', 'Proveedor.*'
					   ,'promedio_evaluacion(Contrato.cont_id) as nota_final');
		$info_cont = $this->Contrato->find('all', array('order' => 'Contrato.cont_id desc', 'fields' => $fields));
		$this->set('contratos', $info_cont);
		header("Content-type: application/vnd.ms-excel; name=contratos.xls");
		header("Content-disposition: attachment; filename=contratos.xls");
	}
}
?>