<script language="Javascript" type="text/javascript" src="/js/activos_fijos/edit_entrada.js"></script>
<div class="entradas form">
<?php echo $this->Form->create('ActivoFijo', array('id' => 'FormActivoFijo', 'url' => '/activos_fijos/edit_entrada', 'type' => 'file'));?>
	<fieldset>
		<legend><?php __(utf8_encode('Editar Entrada de Activo Fijo')); ?></legend>
	<?php
		echo $this->Form->input('deaf_size', array('type' => 'hidden', 'value' => $deaf_size));
		echo $this->Form->input('acfi_id', array('type' => 'hidden'));
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
		if (!empty($this->data['ActivoFijoDocumento']['acfd_id'])) {
			echo $this->Form->input('ActivoFijoDocumento.acfd_id', array('type' => 'hidden', 'value' => $this->data['ActivoFijoDocumento']['acfd_id']));
		}
		echo $this->Form->input('ActivoFijoDocumento.acfd_contenido', array('label' => 'Adjuntar Fotografia', 'type' => 'file'));
		echo $this->Form->input('acfi_descripcion', array('type' => 'textarea', 'label' => utf8_encode('Descripción')));
		echo $this->Form->input('acfi_observaciones', array('type' => 'textarea', 'label' => 'Observaciones'));
	?>
	</fieldset>
	<fieldset>
		<legend><?php __('Detalle de Entrada'); ?></legend>
		<table width="100%" class="detalle_form" border="0">
			<tr>
				<td colspan="2" width="58%">
					<span class="input select required">
						<label>Producto/C&oacute;digo</label>
						<input type="text" class="prod_nombre" style="width:495px;" />
						<input type="hidden" class="prod_id" />
					</span>
				</td>
				<td>
					<span class="input select required">
						<label>Propiedad</label>
						<select class="prop_id">
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
				</td>
			</tr>
			<tr>
				<td width="33%">
					<span class="input select required">
						<label>Situaci&oacute;n</label>
						<select class="situ_id">
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
					<span class="input select">
						<label>Marca</label>
						<select class="marc_id">
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
						<select class="colo_id">
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
                        <select class="mode_id">
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
                        <input type="text" class="deaf_serie" />
                    </span>
                </td>
                <td>
                	<span class="input select required">
						<label><?php echo utf8_encode("Fecha de adquisición"); ?></label>
						<input type="text" class="deaf_fecha_adquisicion" />
					</span>
                </td>
            </tr>
			<tr>
				<td>
					<span class="input select">
						<label>Fecha de Garant&iacute;a</label>
						<input type="text" class="deaf_fecha_garantia" />
					</span> 
				</td>
				<td>
					<span class="input select required">
						<label>Precio</label>
						<input type="text" class="deaf_precio" onkeypress="javascript:return(validchars(event,nums));" />
					</span> 
				</td>
				<td>
					<span class="input select required">
						<label>Cantidad</label>
						<input maxlength="7" type="text" class="deaf_cantidad" onkeypress="javascript:return(validchars(event,num));" />
					</span> 
				</td>
            </tr>
			<tr>
				<td>
					<span class="input select required">
						<label><?php echo utf8_encode("¿Es Depreciable?"); ?></label>
						<input type="checkbox" checked="checked" class="deaf_depreciable" style="width:0px;"/>
					</span> 
				</td>
				<td>
					<span class="input select required">
						<label><?php echo utf8_encode("Vida Útil"); ?></label>
						<input maxlength="4" type="text" class="deaf_vida_util" onkeypress="javascript:return(validchars(event,num));" />
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
		<table width="100%" id="table_detalle">
			<tr>
				<th width="25%">C&oacute;digo</th>
				<th width="40%">Producto</th>
				<th width="15%">Precio Unitario</th>
				<th width="15%">Depreciable</th>
				<th>Acciones</th>
			</tr>
			
			<?php
				$sum_total = 0;
				$count = 0;
				foreach ($this->data['DetalleActivoFijo'] as $deaf) {
			?>
			<tr>
				<td>
					<?php echo $deaf['deaf_codigo']; ?>
				</td>
				<td>
					<?php echo $deaf['Producto']['prod_nombre']; ?>
				</td>
				<td>
					<?php echo $deaf['deaf_precio']; ?>
				</td>
				<td>
					<?php			
						if ($deaf['deaf_depreciable'] == 1)	{
							echo "Si";
						} else {
							echo "No";
						}
					?>
				</td>
				<td>
					<a href="javascript:;" rel="<?php echo $deaf['deaf_id']; ?>"><img class="del_row" src="/img/delete.png" border="0" title="Eliminar" alt="0" /></a>
					<?php
						$prop_nombre = $deaf['Propiedad']['prop_nombre'];
						$situ_nombre = $deaf['Situacion']['situ_nombre'];
						
						if (isset($deaf['Marca']['marc_nombre'])) {
							$marc_nombre = $deaf['Marca']['marc_nombre'];
						} else {
							$marc_nombre = null;
						}
						
						if (isset($deaf['Color']['colo_nombre'])) {
							$colo_nombre = $deaf['Color']['colo_nombre'];
						} else {
							$colo_nombre = null;
						}
						
						if ($deaf['deaf_fecha_garantia'] != "") {
							$deaf_fecha_garantia = date("d-m-Y", strtotime($deaf['deaf_fecha_garantia']));
						} else {
							$deaf_fecha_garantia = null;
						}
						
						if (isset($deaf['Modelo']['mode_nombre'])) {
							$mode_nombre = $deaf['Modelo']['mode_nombre'];
						} else {
							$mode_nombre = null;
						}
						
						$deaf_depreciable = $deaf['deaf_depreciable'];
						$deaf_vida_util = $deaf['deaf_vida_util'];
						$deaf_fecha_adquisicion = date("d-m-Y", strtotime($deaf['deaf_fecha_adquisicion']));
						$valor_neto = $deaf['deaf_precio'];
						$deaf_id = $deaf['deaf_id'];
						$deaf_serie = $deaf['deaf_serie'];
					?>
					
					<a href="javascript:;"><img class="ver_detalles" src="/img/magnifier.png" border="0" title="Ver Detalle" alt="0" /></a>
					<input class="valor_neto" type="hidden" value="<?php echo $valor_neto; ?>" />
					<input class="prop_nombre" type="hidden" value="<?php echo $prop_nombre; ?>" />
					<input class="situ_nombre" type="hidden" value="<?php echo $situ_nombre; ?>" />
					<input class="marc_nombre" type="hidden" value="<?php echo $marc_nombre; ?>" />
					<input class="colo_nombre" type="hidden" value="<?php echo $colo_nombre; ?>" />
                    <input class="mode_nombre" type="hidden" value="<?php echo $mode_nombre; ?>" />
                    <input class="deaf_serie" type="hidden" value="<?php echo $deaf_serie; ?>"/>
					<input class="deaf_fecha_garantia" type="hidden" value="<?php echo $deaf_fecha_garantia; ?>" />
					<input class="deaf_depreciable" type="hidden" value="<?php echo $deaf_depreciable; ?>" />
					<input class="deaf_vida_util" type="hidden" value="<?php echo $deaf_vida_util; ?>" />
					<input class="deaf_fecha_adquisicion" type="hidden" value="<?php echo $deaf_fecha_adquisicion; ?>" />
					<input name="data[DetalleActivoFijo][<?php echo $count; ?>][deaf_id]" class="deaf_id" type="hidden" value="<?php echo $deaf_id; ?>" />
				</td>
			</tr>
			
			<?php
					$count++;
					$sum_total += $deaf['deaf_precio'];
				}
			?>
			
		</table>
		<br />
		<table width="30%" border="0" id="table_resumen">
			<tr>
				<th>Total Neto</th>
				<th id="td_valor_neto">$<?php echo number_format($sum_total, 0, ",", "."); ?></th>
			</tr>
			<tr>
				<th>IVA (<?php echo $valor_iva;?>%)</th>
				<th id="td_iva">$<?php echo number_format(round(($sum_total * $valor_iva) / 100), 0, ",", ".");?></th>
			</tr>
			<tr>
				<th>Total</th>
				<th id="td_total">$<?php echo number_format(round($sum_total, 0), 0, ",", "."); ?></th>
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
			<td><strong>Fecha de adquisici&oacute;n:</strong></td>
			<td id="dialog_deaf_fecha_adquisicion"></td>
		</tr>
	</table>
</div>

</div>

<?php
	include("views/sidebars/menu.php");
?>
