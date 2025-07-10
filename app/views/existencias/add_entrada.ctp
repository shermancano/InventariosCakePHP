<script language="Javascript" type="text/javascript" src="/js/existencias/add_entrada.js"></script>

<div class="entradas form">
<?php echo $this->Form->create('Existencia', array('id' => 'FormExistencia', 'url' => '/existencias/add_entrada'));?>
	<fieldset>
		<legend><?php __(utf8_encode('Entrada de Existencia')); ?></legend>
	<?php
		echo $this->Form->input('ceco_id', array('label' => 'Centro de Costo', 'options' => $centros_costos));
		echo $this->Form->input('prov_id', array('label' => 'Proveedor', 'options' => $proveedores, 'empty' => utf8_encode('--- Seleccione Opción ---')));
		echo $this->Form->input('exis_orden_compra', array('label' => 'Orden de Compra'));
		echo $this->Form->input('tido_id', array('label' => 'Tipo de Documento', 'options' => $tipos_documentos, 'empty' => utf8_encode('--- Seleccione Opción ---')));
		echo $this->Form->input('exis_nro_documento', array('label' => utf8_encode('Número de Documento')));
		echo $this->Form->input('exis_fecha_documento', array('type' => 'date', 'label' => 'Fecha de Documento', 'value' => '', 'empty' => '', 'dateFormat' => 'DMY', 'minYear' => date('Y') - 70, 'maxYear' => date('Y')+2));		
		echo $this->Form->input('exis_fecha_compra', array('type' => 'date', 'label' => utf8_encode('Fecha de Recepción'), 'value' => '', 'empty' => '', 'dateFormat' => 'DMY', 'minYear' => date('Y') - 70, 'maxYear' => date('Y')+2));
		echo $this->Form->input('exis_descripcion', array('type' => 'textarea', 'label' => utf8_encode('Descripción')));
		echo $this->Form->input('exis_observaciones', array('type' => 'textarea', 'label' => 'Observaciones'));
	?>
	</fieldset>
	<fieldset>
		<legend><?php __('Detalle de Entrada'); ?></legend>
		<table width="100%" class="detalle_form" border="0">
			<tr>
				<td width="58%">
					<span class="input select required">
						<label>Producto/C&oacute;digo</label>
						<input type="text" id="prod_nombre" style="width:456px;" />
						<input type="hidden" id="prod_id" />
					</span>
				</td>
				<td>
					<span class="input select required">
						<label>Serie</label>
					</span>
					<input type="text" id="deex_serie" class="checkMayuscula"/>
				</td>
			</tr>
		</table>
		<table width="100%" class="detalle_form" border="0">
			<tr>
				<td>
					<span class="input select required">
						<label>Fecha de Vencimiento</label>
						<input type="text" id="deex_fecha_vencimiento" />
					</span>
				</td>
				<td>
					<span class="input select required">
						<label>Cantidad</label>
						<input type="text" id="deex_cantidad" onkeypress="return(validchars(event,nums))" />
					</span>
					
				</td>
				<td>
					<span class="input select required">
						<label>Precio Unitario</label>
						<input type="text" id="deex_precio" onkeypress="return(validchars(event,nums))" />
					</span> 
				</td>
				
				<td>
					<div class="submit">
						<input id="nuevo_detalle" type="button" value="Agregar" />
					</div>
				</td>
			</tr>
		</table>
		
		<br />
		<table width="100%" id="table_detalle" style="display:none;">
			<tr>
				<th width="40%">Producto</th>
				<th width="15%">Cantidad</th>
				<th width="15%">Precio Unitario</th>
				<th width="15%">Valor Neto</th>
				<th width="15%">Vencimiento</th>
				<th>Acciones</th>
			</tr>
		</table>
		<br />
		<table width="30%" border="0" id="table_resumen" style="display:none;">
			<tr>
				<th>Total Neto</th>
				<th id="td_valor_neto"></th>
			</tr>
			<tr>
				<th>IVA (19%)</th>
				<th id="td_iva"></th>
			</tr>
			<tr>
				<th>Total</th>
				<th id="td_total"></th>
			</tr>
		</table>
		
	</fieldset>
	<div class="submit">
		<input type="button" value="Guardar" id="form_submit" />
	</div>
</form>

<div id="prod_details" style="display:none;">
	<table width="100%">
		<tr>
			<td width="30%"><strong>Serie:</strong></td>
			<td id="prod_serie"></td>
		</tr>
	</table>
</div>

</div>

<?php
	include("views/sidebars/menu.php");
?>
