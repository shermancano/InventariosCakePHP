<script language="Javascript" type="text/javascript" src="/js/activos_fijos/edit_traslado.js"></script>

<div class="traslado form">
<?php echo $this->Form->create('ActivoFijo', array('id' => 'FormActivoFijo', 'url' => '/activos_fijos/edit_traslado'));?>
	<fieldset>
		<legend><?php __(utf8_encode('Editar Traslado Activo Fijo')); ?></legend>
	<?php
		echo $this->Form->input('ceco_id', array('label' => 'Centro de Costo', 'options' => $centros_costos));
		echo $this->Form->input('prov_id', array('label' => 'Proveedor', 'options' => $proveedores, 'empty' => utf8_encode('--- Seleccione Opción ---')));
		echo $this->Form->input('fina_id', array('label' => 'Financiamiento', 'options' => $financiamientos, 'empty' => utf8_encode('--- Seleccione Opción ---')));
		echo $this->Form->input('cuco_id', array('label' => 'Cuenta Contable', 'options' => $cuentas_contables, 'empty' => utf8_encode('--- Seleccione Opción ---')));
		echo $this->Form->input('acfi_orden_compra', array('label' => 'Orden de Compra'));
		echo $this->Form->input('tido_id', array('label' => 'Tipo de Documento', 'options' => $tipos_documentos, 'empty' => utf8_encode('--- Seleccione Opción ---')));
		echo $this->Form->input('acfi_nro_documento', array('label' => utf8_encode('Número de Documento')));
		echo $this->Form->input('acfi_fecha_documento', array('type' => 'date', 'label' => 'Fecha de Documento', 'value' => '', 'empty' => '', 'dateFormat' => 'DMY', 'minYear' => date('Y') - 70, 'maxYear' => date('Y')+15));		
		echo $this->Form->input('acfi_fecha_adquisicion', array('type' => 'date', 'label' => utf8_encode('Fecha de Adquisici&oacute;n'), 'value' => '', 'empty' => '', 'dateFormat' => 'DMY', 'minYear' => date('Y') - 70, 'maxYear' => date('Y')+15));
		echo $this->Form->input('acfi_descripcion', array('type' => 'textarea', 'label' => utf8_encode('Descripci&oacute;n')));
		echo $this->Form->input('acfi_observaciones', array('type' => 'textarea', 'label' => 'Observaciones'));
	?>
	</fieldset>
    <fieldset>
		<legend><?php __('Detalle de Traslado'); ?></legend>
		<table width="100%" class="detalle_form" border="0">
			<tr>
				<th colspan="2" width="58%">
					<span class="input select required">
						<label>Producto/C&oacute;digo</label>
						<input type="text" id="prod_nombre" style="width:495px;" />
						<input type="hidden" id="prod_id" />
					</span>
				</th>
				<th>
					<span class="input select required">
						<label>Propiedad</label>
						<select id="prop_id">
							<option value=""></option>
						<?php
							foreach ($propiedades as $key => $val) {
						?>
							<option value="<?php echo $key; ?>"><?php echo $val; ?></option>
						<?php
							}
						?>
						</select>
					</span>
				</th>
			</tr>
			<tr>
				<td width="33%">
					<span class="input select required">
						<label>Situaci&oacute;n</label>
						<select id="situ_id">
							<option value=""></option>
						<?php
							foreach ($situaciones as $key => $val) {
						?>
							<option value="<?php echo $key; ?>"><?php echo $val; ?></option>
						<?php
							}
						?>
						</select>
					</span> 
				</td>
				<td width="33%">
					<span class="input select required">
						<label>Marca</label>
						<select id="marc_id">
							<option value=""></option>
							<?php
								foreach ($marcas as $key => $val) {
							?>
								<option value="<?php echo $key; ?>"><?php echo $val; ?></option>
							<?php
								}
							?>
						</select>
					</span>
				</td>
				<td width="33%">
					<span class="input select required">
						<label>Color</label>
						<select id="colo_id">
							<option value=""></option>
						<?php
							foreach ($colores as $key => $val) {
						?>
							<option value="<?php echo $key; ?>"><?php echo $val; ?></option>
						<?php
							}
						?>
						</select>
					</span>
				</td>
				
			</tr>
			<tr>
				<td>
					<span class="input select">
						<label>Fecha de Garant&iacute;a</label>
						<input type="text" id="deaf_fecha_garantia" />
					</span> 
				</td>
				<td>
					<span class="input select required">
						<label>Precio</label>
						<input type="text" id="deaf_precio" onkeypress="javascript:return(validchars(event,nums));" />
					</span> 
				</td>
				<td>
					<span class="input select required">
						<label>Cantidad</label>
						<input maxlength="7" type="text" id="deaf_cantidad" onkeypress="javascript:return(validchars(event,num));" />
					</span> 
				</td>
			</tr>
			<tr>
				<td>
					<span class="input select required">
						<label><?php echo utf8_encode("&iquest;Es Depreciable?"); ?></label>
						<input type="checkbox" checked="checked" id="deaf_depreciable" style="width:0px;"/>
					</span> 
				</td>
				<td>
					<span class="input select required">
						<label><?php echo utf8_encode("Vida &Uacute;til"); ?></label>
						<input maxlength="4" type="text" id="deaf_vida_util" onkeypress="javascript:return(validchars(event,num));" />
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
				<th width="15%">Depreciable</th>
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
			<td width="40%"><strong>Propiedad:</strong></td>
			<td id="dialog_prop_nombre"></td>
		</tr>
		<tr>
			<td><strong>Situaci&oacute;n:</strong></td>
			<td id="dialog_situ_nombre"></td>
		</tr>
		<tr>
			<td><strong>Marca:</strong></td>
			<td id="dialog_marc_nombre"></td>
		</tr>
		<tr>
			<td><strong>Color:</strong></td>
			<td id="dialog_colo_nombre"></td>
		</tr>
		<tr>
			<td><strong>Fecha de Garant&iacute;a:</strong></td>
			<td id="dialog_deaf_fecha_garantia"></td>
		</tr>
		<tr>
			<td><strong>Depreciable:</strong></td>
			<td id="dialog_deaf_depreciable"></td>
		</tr>
		<tr>
			<td><strong>Vida &Uacute;til:</strong></td>
			<td id="dialog_deaf_vida_util"></td>
		</tr>
	</table>
</div>
</div>
<?php
	include("views/sidebars/menu.php");
?>
