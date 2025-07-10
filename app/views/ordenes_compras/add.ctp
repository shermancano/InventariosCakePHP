<script language="Javascript" type="text/javascript" src="/js/ordenes_compras/add.js"></script>

<div class="ordenes_compras form">
<?php echo $this->Form->create('OrdenCompra', array('url' => '/ordenes_compras/add'));?>
	<fieldset>
		<legend><?php __('Nueva Orden de Compra'); ?></legend>
	<?php
		echo $this->Form->input('prov_id', array('label' => 'Proveedor', 'options' => $proveedores, 'empty' => '-- Seleccione Opción --'));
		echo $this->Form->input('ceco_id', array('label' => 'Centro de Costo (Salud)/Unidad de Compra', 'options' => $centros_costos));
		echo $this->Form->input('orco_numero', array('label' => 'Número'));
		echo $this->Form->input('orco_nombre', array('label' => 'Nombre de Orden de Compra'));
		echo $this->Form->input('orco_fecha_entrega', array('dateFormat' => 'DMY', 'label' => 'Fecha de Entrega'));
		echo $this->Form->input('orco_direccion_factura', array('type' => 'text', 'label' => 'Dirección de Envío de Factura'));
		echo $this->Form->input('mede_id', array('label' => 'Método de Despacho', 'options' => $metodos_despachos, 'empty' => '-- Seleccione Opción --'));
		echo $this->Form->input('fopa_id', array('label' => 'Forma de Pago', 'options' => $formas_pagos, 'empty' => '-- Seleccione Opción --'));
		echo $this->Form->input('fina_id', array('label' => 'Financiamiento', 'options' => $financiamientos, 'empty' => '-- Seleccione Opción --'));
		echo $this->Form->input('orco_responsable', array('label' => 'Emitida Por'));
		echo $this->Form->input('orco_observaciones', array('type' => 'textarea', 'label' => 'Observaciones'));
	?>
	</fieldset>
	<fieldset>
    	<legend><?php __('Detalle de Orden de Compra')?></legend>
		<div class="input checkbox">
			<label for=""><input type="checkbox" id="check_busqueda" />Buscar en catálogo de productos</label>
		</div>
		
    	<table width="100%" class="detalle_form" border="0">
			<tr>
				<td width="33%">
					<span class="input select required">
						<span class="prod_change">
						<label>Nombre del Producto</label>
						<input type="text" id="deor_nombre" style="width:250px;" />
						</span>
					</span>
				</td>
				<td width="33%">
					<span class="input select required">
						<label>Cantidad</label>
					</span>
					<input type="text" maxlength="10" id="deor_cantidad" onkeypress="return validchars(event, num)" style="width:250px;" />
				</td>
				<td width="33%">
					<span class="input select required">
						<label>Unidad</label>
						<select id="unid_id" style="width:250px;">
						<?php
							foreach ($unidades as $key => $val) {
						?>
							<option value="<?php echo $key; ?>"><?php echo $val; ?></option>
						<?php
							}
						?>
						</select>
					</span>
				</td>
			<tr>
				<td>
					<span class="input textarea">
						<label>Especificaciones Comprador</label>
					</span>
					<textarea style="width:250px;" id="deor_especifi_comprador"></textarea>
					
				</td>
				<td>
					<span class="input textarea">
						<label>Especificaciones Proveedor</label>
					</span>
					<textarea style="width:250px;" id="deor_especifi_proveedor"></textarea>
				</td>
                <td>
					<span class="input required">
						<label>Precio</label>
					</span>
					<input type="text" maxlength="10" id="deor_precio" onkeypress="return validchars(event, num)" style="width:245px;"/>
				</td>
			</tr>
			<tr>
				<td>
					<span class="input">
						<label>Descuento (%)</label>
					</span>
					<input type="text" maxlength="3" id="deor_descuento" onkeypress="return validchars(event, num)" style="width:250px;" />
				</td>
				<td>
					<span class="input">
						<label>Cargos</label>
					</span>
					<input type="text" maxlength="10" id="deor_cargos" onkeypress="return validchars(event, num)" style="width:250px;" />
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
				<th width="50%">Producto</th>
				<th>Cantidad</th>
				<th>Precio</th>
				<th>Descuento</th>
				<th>Cargos</th>
				<th>Total</th>
				<th>Acciones</th>
			</tr>
		</table>
    </fieldset>
<?php echo $this->Form->end(__('Guardar', true));?>
</div>

<?php
	include("views/sidebars/menu.php");
?>