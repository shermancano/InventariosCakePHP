<script language="Javascript" type="text/javascript" src="/js/contratos/edit.js"></script>

<div class="contratos form">
<?php echo $this->Form->create('Contrato', array('type' => 'file'));?>
	<fieldset>
		<legend>Editar Contrato - <?php echo $this->data['Contrato']['cont_nombre']; ?></legend>
	<?php
		echo $this->Form->input('cont_id', array('type' => 'hidden', 'value' => $cont_id));
		echo $this->Form->input('cont_nombre', array('label' => utf8_encode('Nombre de Adquisici�n/Contrato')));
		echo $this->Form->input('cont_nro_licitacion', array('label' => utf8_encode('N�mero de Licitaci�n/Contrato')));
		echo $this->Form->input('cont_descripcion', array('label' => utf8_encode('Descripci�n del Contrato')));
		echo $this->Form->input('tire_id', array('label' => utf8_encode('Tipo de Renovaci�n'), 'options' => $combo_tipo_renovacion));
		echo $this->Form->input('rubr_id', array('label' => 'Rubro interno de Compras', 'options' => $combo_rubros));
		echo $this->Form->input('tico_id', array('label' => 'Tipo de Contrato', 'options' => $combo_tipo_contrato));
		echo $this->Form->input('unco_id', array('label' => 'Unidad de Compra', 'options' => $combo_unid_compras));
		echo $this->Form->input('cont_admin_tecnico', array('label' => utf8_encode('Administrador T�cnico del Contrato')));
		echo $this->Form->input('moco_id', array('label' => 'Modalidad de Compra', 'options' => $combo_mod_compra));
		echo $this->Form->input('cont_resolucion', array('label' => utf8_encode('Resoluci�n Aprobaci�n Contrato')));
		
		echo "<div class=\"input textarea\">";
		echo "   <label for=\"ContratoContDetalleMultas\">Adjuntar Documentos</label>";
		
		if (is_array($this->data['Documento']) && sizeof($this->data['Documento']) > 0) :
			echo "   <br /><ul>";
			
			foreach ($this->data['Documento'] as $doc) {
				echo "<li><a href=\"/documentos/view/".$doc['docu_id']."\">".$doc['docu_nombre']."</a></li>";
			}
			
			echo "   </ul><br />";
		endif;
		
		echo "	 <input type=\"file\" name=\"data[Documento][]\" />&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<a id=\"mas_archivos\" href=\"javascript:;\">[Mas archivos]</a>";
		echo "	 <span id=\"mas_archivos_span\"></span>";
		echo "</div>";
		
		echo $this->Form->input('cont_orden_compra', array('label' => utf8_encode('N�mero Orden de Compra')));
		echo $this->Form->input('timo_id', array('label' => 'Moneda', 'options' => $combo_tipo_monto));
		echo $this->Form->input('cont_monto_compra', array('label' => 'Monto Total'));
		echo $this->Form->input('cont_monto_mensual', array('label' => 'Monto Mensual'));
		echo $this->Form->input('cont_fecha_suscripcion', array('label' => utf8_encode('Fecha de Suscripci�n'), 'dateFormat' => 'DMY', 'minYear' => date('Y') - 70, 'maxYear' => date('Y')+2));
		echo $this->Form->input('cont_fecha_inicio', array('label' => 'Fecha de Inicio', 'dateFormat' => 'DMY', 'minYear' => date('Y') - 70, 'maxYear' => date('Y')+2));
		echo $this->Form->input('cont_fecha_termino', array('label' => utf8_encode('Fecha de T�rmino'), 'dateFormat' => 'DMY', 'minYear' => date('Y') - 70, 'maxYear' => date('Y')+2));
		echo $this->Form->input('cont_fecha_aviso_termino', array('label' => utf8_encode('Fecha de aviso de t�rmino de contrato'), 'dateFormat' => 'DMY', 'minYear' => date('Y') - 70, 'maxYear' => date('Y')+2));
		echo $this->Form->input('cont_observaciones', array('label' => 'Observaciones Generales'));
		echo $this->Form->input('cont_multas', array('label' => 'Contempla Multas', 'options' => array("0" => "NO", "1" => "SI")));
		
		if ($this->data['Contrato']['cont_multas'] == 1) {
			echo $this->Form->input('cont_detalle_multas', array('label' => utf8_encode('Detalle de Multas')));
		} else {
			echo $this->Form->input('cont_detalle_multas', array('label' => utf8_encode('Detalle de Multas'), 'disabled' => true));
		}
				
		if (trim($this->data['Contrato']['timo_id_garantia']) != "") {
			echo $this->Form->input('aplica_boleta', array('checked' => 'checked', 'type' => 'checkbox', 'label' => utf8_encode('Aplica boleta de garant�a')));
		} else {
			echo $this->Form->input('aplica_boleta', array('type' => 'checkbox', 'label' => utf8_encode('Aplica boleta de garant�a')));
		}
		
		if ($this->data['Contrato']['timo_id_garantia'] == "") {
			echo $this->Form->input('timo_id_garantia', array('disabled' => 'disabled', 'label' => utf8_encode('Moneda de la Garant�a'), 'options' => $combo_tipo_monto_garantia));
		} else {
			echo $this->Form->input('timo_id_garantia', array('label' => utf8_encode('Moneda de la Garant�a'), 'options' => $combo_tipo_monto_garantia));
		}
		
		if ($this->data['Contrato']['cont_monto_garantia'] == "") {
			echo $this->Form->input('cont_monto_garantia', array('disabled' => 'disabled', 'label' => utf8_encode('Monto de Garant�a')));
		} else {
			echo $this->Form->input('cont_monto_garantia', array('label' => utf8_encode('Monto de Garant�a')));
		}
		
		if ($this->data['Contrato']['cont_nro_boleta'] == "") {
			echo $this->Form->input('cont_nro_boleta', array('disabled' => 'disabled', 'label' => utf8_encode('N�mero de Boleta de Garant�a')));
		} else {
			echo $this->Form->input('cont_nro_boleta', array('label' => utf8_encode('N�mero de Boleta de Garant�a')));
		}
		
		if ($this->data['Contrato']['cont_nro_boleta'] == "") {
			echo $this->Form->input('banc_id', array('disabled' => 'disabled', 'label' => 'Banco', 'options' => $combo_bancos));
		} else {
			echo $this->Form->input('banc_id', array('label' => 'Banco', 'options' => $combo_bancos));
		}
		
		if ($this->data['Contrato']['cont_vencimiento_garantia'] == "") {
			echo $this->Form->input('cont_vencimiento_garantia', array('value' => '', 'disabled' => 'disabled', 'empty' => '', 'label' => utf8_encode('Vencimiento de Garant�a'), 'dateFormat' => 'DMY', 'minYear' => date('Y') - 70, 'maxYear' => date('Y')+2));
		} else {
			echo $this->Form->input('cont_vencimiento_garantia', array('value' => $this->data['Contrato']['cont_vencimiento_garantia'], 'empty' => '', 'label' => utf8_encode('Vencimiento de Garant�a'), 'dateFormat' => 'DMY', 'minYear' => date('Y') - 70, 'maxYear' => date('Y')+2));
		}
		
		if ($this->data['Contrato']['cont_fecha_aviso'] == "") {
			echo $this->Form->input('cont_fecha_aviso', array('value' => '', 'disabled' => 'disabled', 'empty' => '', 'label' => utf8_encode('Fecha de aviso de vencimiento de garant�a'), 'dateFormat' => 'DMY', 'minYear' => date('Y') - 70, 'maxYear' => date('Y')+2));
		} else {
			echo $this->Form->input('cont_fecha_aviso', array('value' => $this->data['Contrato']['cont_fecha_aviso'] , 'empty' => '', 'label' => utf8_encode('Fecha de aviso de vencimiento de garant�a'), 'dateFormat' => 'DMY', 'minYear' => date('Y') - 70, 'maxYear' => date('Y')+2));
		}
		
		echo $this->Form->input('prov_id', array('label' => 'Proveedor Adjudicado', 'options' => $combo_proveedores));
		echo $this->Form->input('cont_vigente', array('label' => 'Contrato Vigente/Terminado', 'options' => $combo_vigente));
	?>
	</fieldset>
<?php echo $this->Form->end(__('Guardar', true));?>
</div>

<?php
	include("views/sidebars/menu.php");
?>
