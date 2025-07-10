<div class="entradas view" id="entradas_view">
<h2><?php  __('Entrada de Existencia');?></h2>
	<dl><?php $i = 0; $class = ' class="altrow"';?>
		<dt<?php if ($i % 2 == 0) echo $class;?>><?php __('ID'); ?></dt>
		<dd<?php if ($i++ % 2 == 0) echo $class;?>>
			<?php echo $entrada['Existencia']['exis_id']; ?>
			&nbsp;
		</dd>
		<dt<?php if ($i % 2 == 0) echo $class;?>><?php __('Correlativo'); ?></dt>
		<dd<?php if ($i++ % 2 == 0) echo $class;?>>
			<?php echo sprintf("%012d", $entrada['Existencia']['exis_correlativo']); ?>
			&nbsp;
		</dd>
		<dt<?php if ($i % 2 == 0) echo $class;?>><?php __('Centro de Costo'); ?></dt>
		<dd<?php if ($i++ % 2 == 0) echo $class;?>>
			<?php echo $entrada['CentroCosto']['ceco_nombre']; ?>
			&nbsp;
		</dd>
		<dt<?php if ($i % 2 == 0) echo $class;?>><?php __('Proveedor'); ?></dt>
		<dd<?php if ($i++ % 2 == 0) echo $class;?>>
			<?php echo $entrada['Proveedor']['prov_nombre']; ?>
			&nbsp;
		</dd>
		<dt<?php if ($i % 2 == 0) echo $class;?>><?php __('Fecha'); ?></dt>
		<dd<?php if ($i++ % 2 == 0) echo $class;?>>
			<?php echo date("d-m-Y H:i:s", strtotime($entrada['Existencia']['exis_fecha'])); ?>
			&nbsp;
		</dd>
		<dt<?php if ($i % 2 == 0) echo $class;?>><?php __('Orden de Compra'); ?></dt>
		<dd<?php if ($i++ % 2 == 0) echo $class;?>>
			<?php echo $entrada['Existencia']['exis_orden_compra']; ?>
			&nbsp;
		</dd>
		<dt<?php if ($i % 2 == 0) echo $class;?>><?php __('Tipo de Documento'); ?></dt>
		<dd<?php if ($i++ % 2 == 0) echo $class;?>>
			<?php echo $entrada['TipoDocumento']['tido_descripcion']; ?>
			&nbsp;
		</dd>
		<dt<?php if ($i % 2 == 0) echo $class;?>><?php __(utf8_encode('Número de Documento')); ?></dt>
		<dd<?php if ($i++ % 2 == 0) echo $class;?>>
			<?php echo $entrada['Existencia']['exis_nro_documento']; ?>
			&nbsp;
		</dd>
		<dt<?php if ($i % 2 == 0) echo $class;?>><?php __('Fecha de Documento'); ?></dt>
		<dd<?php if ($i++ % 2 == 0) echo $class;?>>
			<?php
				if (trim($entrada['Existencia']['exis_fecha_documento']) != "") {
					echo date("d-m-Y", strtotime($entrada['Existencia']['exis_fecha_documento']));
				}
			?>
			&nbsp;
		</dd>
		<dt<?php if ($i % 2 == 0) echo $class;?>><?php __(utf8_encode('Fecha de Recepción')); ?></dt>
		<dd<?php if ($i++ % 2 == 0) echo $class;?>>
			<?php
				if (trim($entrada['Existencia']['exis_fecha_compra']) != "") {
					echo date("d-m-Y", strtotime($entrada['Existencia']['exis_fecha_compra']));
				}
			?>
			&nbsp;
		</dd>
		<dt<?php if ($i % 2 == 0) echo $class;?>><?php __(utf8_encode('Descripción')); ?></dt>
		<dd<?php if ($i++ % 2 == 0) echo $class;?>>
			<?php echo $entrada['Existencia']['exis_descripcion']; ?>
			&nbsp;
		</dd>
		<dt<?php if ($i % 2 == 0) echo $class;?>><?php __('Observaciones'); ?></dt>
		<dd<?php if ($i++ % 2 == 0) echo $class;?>>
			<?php echo $entrada['Existencia']['exis_observaciones']; ?>
			&nbsp;
		</dd>
		
	</dl>
	
	<br />
	<br />
	<h2><?php  __('Detalle');?></h2>
	
			<table width="100%">
				<tr>
					<th width="40%">Producto</th>
					<th width="8%">Cantidad</th>
					<th width="12%">Precio Unitario</th>
					<th width="10%">Valor Neto</th>
					<th width="10%">Serie</th>
					<th width="10%">Vencimiento</th>
				</tr>
				<?php
					$sum_total_neto = 0;
					foreach ($entrada['DetalleExistencia'] as $deex) {
				?>
					<tr>
						<td><?php echo $deex['Producto']['prod_nombre']; ?></td>
						<td><?php echo $deex['deex_cantidad']; ?></td>
						<td><?php echo $deex['deex_precio']; ?></td>
						<td><?php echo round($deex['deex_cantidad']*$deex['deex_precio'], 0); ?></td>
						<td><?php echo $deex['deex_serie']; ?></td>
						<td><?php echo date("d-m-Y", strtotime($deex['deex_fecha_vencimiento'])); ?></td>
					</tr>
				<?php
						$sum_total_neto += ($deex['deex_precio']*$deex['deex_cantidad']);
					}
				?>
			</table>
			<br />
			
			<table width="30%" border="0" id="table_resumen_cal">
				<tr>
					<th>Total Neto</th>
					<th align="right" id="td_valor_neto"><p>$<?php echo number_format($sum_total_neto, 0, ",", "."); ?></p></th>
				</tr>
				<tr>
					<th>IVA (19%)</th>
					<th id="td_iva" align="right"><p>$0</p></th>
				</tr>
				<tr>
					<th>Total</th>
					<th id="td_total" align="right"><p>$<?php echo number_format(round($sum_total_neto, 0), 0, ",", "."); ?></p></th>
				</tr>
			</table>
	
	
</div>
<?php
	include("views/sidebars/menu.php");
?>
