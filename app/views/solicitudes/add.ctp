<script language="Javascript" type="text/javascript" src="/js/solicitudes/add.js"></script>
<div class="solicitudes form">
<?php echo $this->Form->create('Solicitud', array('url' => '/solicitudes/add', 'id' => 'SolicitudAdd'));?>
	<fieldset>
		<legend><?php __(utf8_encode('Nueva Solicitud')); ?></legend>
	<?php
		echo $this->Form->input('ceco_id', array('type' => 'hidden', 'value' => $ceco_id));
		echo $this->Form->input('tiso_id', array('label' => 'Tipo de Solicitud', 'options' => $tipo_solicitud, 'empty' => '-- Seleccione Opción --'));
		echo $this->Form->input('ceco_id_hacia', array('label' => 'Centro de Costo', 'options' => $centros_costos, 'empty' => '-- Seleccione Opción --'));
		echo $this->Form->input('prov_id', array('label' => 'Proveedor', 'options' => $proveedores, 'empty' => '-- Seleccione Opción --'));
		echo $this->Form->input('soli_comentario', array('label' => 'Observaciones'));
	?>
	</fieldset>
    <fieldset>
    	<legend><?php __('Detalle Solicitud')?></legend>
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
						<label>Cantidad</label>
					</span>
					<input type="text" maxlength="10" id="deso_cantidad" onkeypress="return validchars(event, num)" style="width:150px;"/>
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
				<th width="70%">Producto</th>
				<th>Cantidad</th>
				<th>Acciones</th>
			</tr>
		</table>
    </fieldset>
<?php echo $this->Form->end(__('Guardar', true));?>
</div>
<?php
	include("views/sidebars/menu.php");
?>