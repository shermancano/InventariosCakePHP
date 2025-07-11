<script language="Javascript" type="text/javascript" src="/js/activos_fijos/add_entrada.js"></script>

<div class="entradas form">
<?php echo $this->Form->create('ActivoFijo', array('id' => 'FormActivoFijo', 'url' => '/activos_fijos/add_entrada', 'type' => 'file'));?>
	<fieldset>
		<legend><?php __(utf8_encode('Entrada de Activo Fijo')); ?></legend>
	<?php
		echo $this->Form->input('ceco_id', array('label' => 'Centro de Costo', 'options' => $centros_costos));
		echo $this->Form->input('prov_id', array('label' => 'Proveedor', 'options' => $proveedores, 'empty' => utf8_encode('--- Seleccione Opción ---')));
		echo $this->Form->input('fina_id', array('label' => 'Financiamiento', 'options' => $financiamientos, 'empty' => utf8_encode('--- Seleccione Opción ---')));
		echo $this->Form->input('acfi_orden_compra', array('label' => 'Orden de Compra'));
		echo $this->Form->input('tido_id', array('label' => 'Tipo de Documento', 'options' => $tipos_documentos, 'empty' => utf8_encode('--- Seleccione Opción ---')));
		echo $this->Form->input('acfi_nro_documento', array('label' => utf8_encode('Número de Documento')));
		echo $this->Form->input('acfi_fecha_documento', array('type' => 'date', 'label' => 'Fecha de Documento', 'value' => '', 'empty' => '', 'dateFormat' => 'DMY', 'minYear' => date('Y') - 70, 'maxYear' => date('Y')+15));
		echo $this->Form->input('tire_id', array('label' => utf8_encode('Tipo Resolución'), 'options' => $tipos_resoluciones, 'empty' => utf8_encode('-- Seleccione Opción --')));
		echo $this->Form->input('acfi_numero_resolucion', array('label' => utf8_encode('Número Resolución')));
		echo $this->Form->input('acfi_fecha_resolucion', array('label' => utf8_encode('Fecha Resolución'), 'value' => '', 'empty' => '', 'dateFormat' => 'DMY', 'minYear' => date('Y') -70, 'maxYear' => date('Y')+15));
		echo $this->Form->input('ActivoFijoDocumento.acfd_contenido', array('label' => 'Adjuntar Fotografia', 'type' => 'file'));
		echo $this->Form->input('acfi_descripcion', array('type' => 'textarea', 'label' => utf8_encode('Descripción')));
		echo $this->Form->input('acfi_observaciones', array('type' => 'textarea', 'label' => 'Observaciones'));
	?>
	</fieldset>
	<fieldset>
		<legend><?php __('Detalle de Entrada'); ?></legend>
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
						<?php
							foreach ($propiedades as $key => $val) {
						?>
							<option value="<?php echo $key; ?>"><?php echo $val; ?></option>
						<?php
							}
						?>
                        	<option value="1"></option>
						</select>
					</span>
				</th>
			</tr>
			<tr>
				<td width="33%">
					<span class="input select required">
						<label>Situaci&oacute;n</label>
						<select id="situ_id">
                        	
						<?php
							foreach ($situaciones as $key => $val) {
						?>
							<option value="<?php echo $key; ?>"><?php echo $val; ?></option>
						<?php
							}
						?>
                        	<option value="1"></option>
						</select>
					</span> 
				</td>
				<td width="33%">
					<span class="input select">
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
					<span class="input select">
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
                    	<label>Modelo</label>
                        <select id="mode_id">
                        <option value=""></option>
                        <?php
							foreach ($modelos as $key => $val) {
						?>
							<option value="<?php echo $key; ?>"><?php echo $val; ?></option>
						<?php
							}
						?>
                        </select>
                    </span>
                </td>
                <td>
                	<span class="input select">
                    	<label>Serie</label>
                        <input type="text" id="deaf_serie" />
                    </span>
                </td>
                <td>
                	<span class="input select required">
						<label><?php echo utf8_encode("Fecha de adquisición"); ?></label>
						<input type="text" id="deaf_fecha_adquisicion" onkeypress="javascript:return(validchars(event,fecha));"/>
					</span>
                </td>
            </tr>
			<tr>
				<td>
					<span class="input select">
						<label>Fecha de Garant&iacute;a</label>
						<input type="text" id="deaf_fecha_garantia" onkeypress="javascript:return(validchars(event,fecha));"/>
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
						<label><?php echo utf8_encode("¿Es Depreciable?"); ?></label>
						<input type="checkbox" checked="checked" id="deaf_depreciable" style="width:0px;"/>
					</span> 
				</td>
				<td>
					<span class="input select required">
						<label><?php echo utf8_encode("Vida Útil"); ?></label>
						<input maxlength="4" type="text" id="deaf_vida_util" onkeypress="javascript:return(validchars(event,num));" />
					</span> 
				</td>
				<td>
					
				</td>
			</tr>
			<tr>
				<td>&nbsp;</td>
				<td>&nbsp;</td>
				<td>
					<div class="submit" style="text-align:right;">
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
				<th id="iva">IVA (19%)</th>
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
			<td width="45%"><strong>Propiedad:</strong></td>
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
			<td><strong>Modelo:</strong></td>
			<td id="dialog_mode_nombre"></td>
		</tr>
        <tr>
			<td><strong>Serie:</strong></td>
			<td id="dialog_deaf_serie"></td>
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
		<tr>
			<td><strong>Fecha de Adquisici&oacute;n:</strong></td>
			<td id="dialog_deaf_fecha_adquisicion"></td>
		</tr>
	</table>
</div>

</div>

<?php
	include("views/sidebars/menu.php");
?>
