<div class="ordenes_compra view">
<h2><?php  __('Orden de Compra');?></h2>
	<dl><?php $i = 0; $class = ' class="altrow"';?>
		<dt<?php if ($i % 2 == 0) echo $class;?>><?php __('Número'); ?></dt>
		<dd<?php if ($i++ % 2 == 0) echo $class;?>>
			<?php echo $orden['OrdenCompra']['orco_numero']; ?>
			&nbsp;
		</dd>
		<dt<?php if ($i % 2 == 0) echo $class;?>><?php __('Proveedor'); ?></dt>
		<dd<?php if ($i++ % 2 == 0) echo $class;?>>
			<?php echo $orden['Proveedor']['prov_nombre']; ?>
			&nbsp;
		</dd>
		<dt<?php if ($i % 2 == 0) echo $class;?>><?php __('Nombre'); ?></dt>
		<dd<?php if ($i++ % 2 == 0) echo $class;?>>
			<?php echo $orden['OrdenCompra']['orco_nombre']; ?>
			&nbsp;
		</dd>
		<dt<?php if ($i % 2 == 0) echo $class;?>><?php __('Fecha'); ?></dt>
		<dd<?php if ($i++ % 2 == 0) echo $class;?>>
			<?php echo date("d-m-Y H:i:s", strtotime($orden['OrdenCompra']['orco_fecha'])); ?>
			&nbsp;
		</dd>
		<dt<?php if ($i % 2 == 0) echo $class;?>><?php __('Método de Despacho'); ?></dt>
		<dd<?php if ($i++ % 2 == 0) echo $class;?>>
			<?php echo $orden['MetodoDespacho']['mede_descripcion']; ?>
			&nbsp;
		</dd>
		<dt<?php if ($i % 2 == 0) echo $class;?>><?php __('Forma de Pago'); ?></dt>
		<dd<?php if ($i++ % 2 == 0) echo $class;?>>
			<?php echo $orden['FormaPago']['fopa_descripcion']; ?>
			&nbsp;
		</dd>
		<dt<?php if ($i % 2 == 0) echo $class;?>><?php __('Fecha de Entrega'); ?></dt>
		<dd<?php if ($i++ % 2 == 0) echo $class;?>>
			<?php echo date("d-m-Y", strtotime($orden['OrdenCompra']['orco_fecha_entrega'])); ?>
			&nbsp;
		</dd>
		<dt<?php if ($i % 2 == 0) echo $class;?>><?php __('Dirección de Envío'); ?></dt>
		<dd<?php if ($i++ % 2 == 0) echo $class;?>>
			<?php echo $orden['OrdenCompra']['orco_direccion_factura']; ?>
			&nbsp;
		</dd>
		<dt<?php if ($i % 2 == 0) echo $class;?>><?php __('Responsable'); ?></dt>
		<dd<?php if ($i++ % 2 == 0) echo $class;?>>
			<?php echo $orden['OrdenCompra']['orco_responsable']; ?>
			&nbsp;
		</dd>
		<dt<?php if ($i % 2 == 0) echo $class;?>><?php __('Financiamiento'); ?></dt>
		<dd<?php if ($i++ % 2 == 0) echo $class;?>>
			<?php echo $orden['Financiamiento']['fina_nombre']; ?>
			&nbsp;
		</dd>
		<dt<?php if ($i % 2 == 0) echo $class;?>><?php __('Observaciones'); ?></dt>
		<dd<?php if ($i++ % 2 == 0) echo $class;?>>
			<?php echo $orden['OrdenCompra']['orco_observaciones']; ?>
			&nbsp;
		</dd>
	</dl>
	
	<br />
	<br />
	<h2>Detalle</h2>
	<table width="100%">
		<tr>
			<th>Código</th>
			<th width="40%">Producto</th>
			<th width="8%">Cantidad</th>
			<th width="12%">Unidad</th>
			<th width="10%">Especificaciones Comprador</th>
			<th width="10%">Especificaciones Proveedor</th>
			<th width="10%">Precio Unitario</th>
			<th width="10%">Descuento (%)</th>
			<th width="10%">Cargos</th>
			<th width="10%">Valor Total</th>
		</tr>
		<?php
			$sum_total_neto = 0;
			$sum_descuentos = 0;
			$sum_cargos = 0;
			foreach ($orden['DetalleOrdenCompra'] as $row) {
		?>
		<tr>
			<td>
			<?php
				if (isset($row['Producto']['prod_codigo'])) {
					echo $row['Producto']['prod_codigo'];
				}
			?>
			</td>
			<td>
			<?php
				if (isset($row['Producto']['prod_nombre'])) {
					echo $row['Producto']['prod_nombre'];
				} else {
					echo $row['deor_nombre'];
				}
			?>
			</td>
			<td><?php echo $row['deor_cantidad']; ?></td>
			<td><?php echo $row['Unidad']['unid_nombre']; ?></td>
			<td><?php echo $row['deor_especifi_comprador']; ?></td>
			<td><?php echo $row['deor_especifi_proveedor']; ?></td>
			<td><?php echo $row['deor_precio']; ?></td>
			<td>
			<?php
				if ($row['deor_descuento'] != "") {
					echo $row['deor_descuento'];
				} else {
					echo 0;
				}
			?>
			</td>
			<td>
			<?php
				if ($row['deor_descuento'] != "") {
					echo $row['deor_cargos'];
				} else {
					echo 0;
				}
			?>
			</td>
			<td>
			<?php
				$valor_total = $row['deor_cantidad']*$row['deor_precio'];
				
				if ($row['deor_descuento'] != "") {
					$descuento = ($row['deor_cantidad']*$row['deor_precio']*$row['deor_descuento'])/100;
					$valor_total -= $descuento;
					$sum_descuentos += $descuento;
				}
				
				if ($row['deor_cargos'] != "") {
					$cargo = $row['deor_cargos'];
					$valor_total += $cargo;
					$sum_cargos += $cargo;
				}
				
				$sum_total_neto += $valor_total;
				echo $valor_total;
			?>
			</td>
		</tr>
		<?php
			}
		?>
	</table>
	<br />
	<table width="30%" border="0" id="table_resumen_cal">
		<tr>
			<th>Total Neto</th>
			<th align="right" class="resumen_right"><p>$<?php echo number_format($sum_total_neto, 0, ",", "."); ?></p></th>
		</tr>
		<tr>
			<th>Descuentos</th>
			<th align="right" class="resumen_right"><p>$<?php echo number_format(round($sum_descuentos, 0), 0, ",", "."); ?></p></th>
		</tr>
		<tr>
			<th>Cargos</th>
			<th align="right" class="resumen_right"><p>$<?php echo number_format(round($sum_cargos, 0), 0, ",", "."); ?></p></th>
		</tr>
		<tr>
			<th>Subtotal</th>
			<th align="right" class="resumen_right">
				<p>$
				<?php
					$subtotal = $sum_total_neto + $sum_descuentos + $sum_cargos;
					echo number_format(round($subtotal, 0), 0, ",", ".");
				?>
				</p>
			</th>
		</tr>
		<tr>
			<th>IVA (19%)</th>
			<th align="right" class="resumen_right"><p>$<?php echo number_format(round($subtotal*0.19, 0), 0, ",", "."); ?></p></th>
		</tr>
		<tr>
			<th>Total</th>
			<th align="right" class="resumen_right"><p>$<?php echo number_format(round($subtotal*1.19, 0), 0, ",", "."); ?></p></th>
		</tr>
	</table>
</div>

<?php
	include("views/sidebars/menu.php");
?>
