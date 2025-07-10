<script language="Javascript" type="text/javascript" src="/js/existencias/add_traslado.js"></script>

<div class="traslado form">
<?php echo $this->Form->create('TrasladoExistencia', array('id' => 'FormTraslado', 'url' => '/existencias/add_traslado'));?>
	<fieldset>
		<legend><?php __(utf8_encode('Traslado de Existencia')); ?></legend>
	<?php
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
						<input id="nuevo_detalle" type="button" value="Agregar" />&nbsp;&nbsp;<img style="display:none;" id="ajax_loader" src="/img/ajax-loader.gif" alt="0"/>
					</div>
				</td>
			</tr>
		</table>
		
		<table width="100%" id="table_detalle" style="display:none;">
			<tr>
				<th width="25%">Producto</th>
				<th width="14%">Serie</th>
				<th width="10%">Vencimiento</th>
				<th width="10%">Precio</th>
				<th width="10%">Total</th>
				<th width="10%">Transferir</th>
				<th width="5%">Acciones</th>
			</tr>
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