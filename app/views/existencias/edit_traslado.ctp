<script language="Javascript" type="text/javascript" src="/js/existencias/edit_traslado.js"></script>

<div class="traslado form">
<?php echo $this->Form->create('TrasladoExistencia', array('id' => 'FormTraslado', 'url' => '/existencias/edit_traslado/'.$this->data['TrasladoExistencia']['exis_id']));?>
	<fieldset>
		<legend><?php __(utf8_encode('Editar Traslado de Existencia')); ?></legend>
	<?php
		echo $this->Form->input('size_detalle_existencia', array('type' => 'hidden', 'value' => $size_detalle_existencia));
		echo $this->Form->input('exis_id', array('type' => 'hidden'));
		echo $this->Form->input('ceco_id', array('type' => 'hidden', 'value' => $ceco_id));
		echo $this->Form->input('ceco_nombre', array('label' => 'Desde', 'readonly' => 'readonly', 'value' => $ceco_nombre));
		echo $this->Form->input('ceco_id_hijo', array('label' => 'Hacia', 'options' => $centros_costos));
		echo $this->Form->input('exis_descripcion', array('type' => 'textarea', 'label' => utf8_encode('Descripción')));
		echo $this->Form->input('exis_observaciones', array('type' => 'textarea', 'label' => 'Observaciones'));
	?>
	</fieldset>
	<fieldset>
		<legend><?php __('Detalle de Traslado'); ?></legend>
		<p><strong>* Solo se trasladar&aacute;n productos que mantienen stock vigente en el Centro de Costo.</strong></p>
		<p><strong>* Si el monto a transferir es 0, &eacute;ste se omitir&aacute;.</strong></p>
		<br />
		<table width="100%" id="detalle_form" border="0">
			<tr>
				<td width="50%">
					<span class="input select required">
						<label>Producto/C&oacute;digo</label>
						<input type="text" id="prod_nombre" />
						<input type="hidden" id="prod_id" />
					</span>
				</td>
				<td>
					<div class="submit">
						<input id="nuevo_detalle" type="button" value="Agregar" />&nbsp;&nbsp;<img style="display:none;" id="ajax_loader" src="/img/ajax-loader.gif" alt="0" />
					</div>
				</td>
			</tr>
		</table>
		
		<table width="100%" id="table_detalle">
			<tr>
				<th width="35%">Producto</th>
				<th width="10%">Serie</th>
				<th width="10%">Vencimiento</th>
				<th width="10%">Precio</th>
				<th width="10%">Total</th>
                <th width="10%">Transferir</th>
				<th width="5%">Acciones</th>
			</tr>
			<?php
				$row_count = 0;
				foreach ($deex_info as $deex) {
				$class = $deex['Producto']['prod_id'].$deex['DetalleExistencia']['deex_serie'].$deex['DetalleExistencia']['deex_fecha_vencimiento'].$deex['DetalleExistencia']['deex_precio'].$deex['DetalleExistencia']['total'];
			?>
				<tr class="<?php echo $class; ?>">
					<td>
						<input name="data[DetalleExistencia][<?php echo $row_count; ?>][prod_id]" type="hidden" value="<?php echo $deex['Producto']['prod_id']; ?>" />
						<?php echo $deex['Producto']['prod_nombre']; ?>
					</td>
                    <td>
						<input name="data[DetalleExistencia][<?php echo $row_count; ?>][deex_serie]" type="hidden" value="<?php echo $deex['DetalleExistencia']['deex_serie']; ?>" />
						<?php echo $deex['DetalleExistencia']['deex_serie']; ?>
					</td>
                    <td>
						<input name="data[DetalleExistencia][<?php echo $row_count; ?>][deex_fecha_vencimiento]" type="hidden" value="<?php echo date("d-m-Y", strtotime($deex['DetalleExistencia']['deex_fecha_vencimiento'])); ?>" />
						<?php echo date("d-m-Y", strtotime($deex['DetalleExistencia']['deex_fecha_vencimiento'])); ?>
					</td>
                    <td>
						<input name="data[DetalleExistencia][<?php echo $row_count; ?>][deex_precio]" type="hidden" value="<?php echo $deex['DetalleExistencia']['deex_precio']; ?>" />
						<?php echo $deex['DetalleExistencia']['deex_precio']; ?>
					</td>
                    <td class="deex_total">
						<?php echo $deex['DetalleExistencia']['total']; ?>
					</td>
                    <td>
                    	<input name="data[DetalleExistencia][<?php echo $row_count; ?>][deex_cantidad]" class="deex_cantidad" value="<?php echo $deex['DetalleExistencia']['deex_cantidad']; ?>" onkeypress="return(validchars(event, num))" />
                    </td>
					<td>
						<a href="javascript:;" class="del_row" rel="<?php echo $deex['DetalleExistencia']['deex_id']; ?>"><img src="/img/delete.png" border="0" alt="0" /></a>
						<a href="javascript:;"></a>
						<input class="deex_serie" name="data[DetalleExistencia][<?php echo $row_count; ?>][deex_serie]" type="hidden" value="<?php echo $deex['DetalleExistencia']['deex_serie']; ?>" />
						<input class="deex_id" name="data[DetalleExistencia][<?php echo $row_count; ?>][deex_id]" type="hidden" value="<?php echo $deex['DetalleExistencia']['deex_id']; ?>" />
						<input class="exis_id" name="data[DetalleExistencia][<?php echo $row_count; ?>][exis_id]" type="hidden" value="<?php echo $deex['DetalleExistencia']['exis_id']; ?>" />
					</td>
				</tr>
			<?php
					$row_count++;
				}
			?>
		</table>
	</fieldset>
	<div class="submit">
		<input type="button" value="Guardar" id="form_submit" />
	</div>
</form>


</div>

<?php
	include("views/sidebars/menu.php");
?>