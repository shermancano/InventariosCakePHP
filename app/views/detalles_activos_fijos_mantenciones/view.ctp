<div class="mantenciones index">
	<h2><?php  __('Mantención');?></h2>
    <dl><?php $i = 0; $class = ' class="altrow"';?>
		<dt<?php if ($i % 2 == 0) echo $class;?>><?php __('ID'); ?></dt>
		<dd<?php if ($i++ % 2 == 0) echo $class;?>>
			<?php echo $infoMantencion['ActivoFijoMantencion']['afma_id']; ?>
			&nbsp;
		</dd>
        <dt<?php if ($i % 2 == 0) echo $class;?>><?php __('Producto'); ?></dt>
		<dd<?php if ($i++ % 2 == 0) echo $class;?>>
			<?php echo $infoMantencion['UbicacionActivoFijo']['Producto']['prod_nombre']; ?>
			&nbsp;
		</dd>
        <dt<?php if ($i % 2 == 0) echo $class;?>><?php __('Código Producto'); ?></dt>
		<dd<?php if ($i++ % 2 == 0) echo $class;?>>
			<?php echo $infoMantencion['UbicacionActivoFijo']['ubaf_codigo']; ?>
			&nbsp;
		</dd>
        <dt<?php if ($i % 2 == 0) echo $class;?>><?php __('Número de Factura'); ?></dt>
		<dd<?php if ($i++ % 2 == 0) echo $class;?>>
			<?php echo $infoMantencion['ActivoFijoMantencion']['afma_numero_factura']; ?>
			&nbsp;
		</dd>
        <dt<?php if ($i % 2 == 0) echo $class;?>><?php __('Fecha de Factura'); ?></dt>
		<dd<?php if ($i++ % 2 == 0) echo $class;?>>
			<?php echo date('d-m-Y', strtotime($infoMantencion['ActivoFijoMantencion']['afma_fecha_factura'])); ?>
			&nbsp;
		</dd>
        <dt<?php if ($i % 2 == 0) echo $class;?>><?php __('Proveedor'); ?></dt>
		<dd<?php if ($i++ % 2 == 0) echo $class;?>>
			<?php echo $infoMantencion['Proveedor']['prov_nombre']; ?>
			&nbsp;
		</dd>
        <dt<?php if ($i % 2 == 0) echo $class;?>><?php __('Modelo'); ?></dt>
		<dd<?php if ($i++ % 2 == 0) echo $class;?>>
			<?php echo $infoMantencion['ActivoFijoMantencion']['afma_modelo']; ?>
			&nbsp;
		</dd>
        <dt<?php if ($i % 2 == 0) echo $class;?>><?php __('Marca'); ?></dt>
		<dd<?php if ($i++ % 2 == 0) echo $class;?>>
			<?php echo $infoMantencion['ActivoFijoMantencion']['afma_marca']; ?>
			&nbsp;
		</dd>
        <dt<?php if ($i % 2 == 0) echo $class;?>><?php __('Año'); ?></dt>
		<dd<?php if ($i++ % 2 == 0) echo $class;?>>
			<?php echo $infoMantencion['ActivoFijoMantencion']['afma_ano']; ?>
			&nbsp;
		</dd>
        <dt<?php if ($i % 2 == 0) echo $class;?>><?php __('Patente'); ?></dt>
		<dd<?php if ($i++ % 2 == 0) echo $class;?>>
			<?php echo $infoMantencion['ActivoFijoMantencion']['afma_patente']; ?>
			&nbsp;
		</dd>
        <dt<?php if ($i % 2 == 0) echo $class;?>><?php __('Motor'); ?></dt>
		<dd<?php if ($i++ % 2 == 0) echo $class;?>>
			<?php echo $infoMantencion['ActivoFijoMantencion']['afma_motor']; ?>
			&nbsp;
		</dd>
    </dl>
    <br />
	<br />
	<h2><?php  __('Detalle Mantención');?></h2>
    <table width="100%" border="0">
    	<tr>
        	<th width="12%">Fecha Servicio</th>
            <th>Kilometraje</th>
            <th>Trabajo y/o Servicio</th>
            <th>Operador</th>
            <th>Costo</th>
            <th>Observación</th>
        </tr>
        <?php
        	foreach ($infoDetalleMantencion as $dema) {
		?>
        	<tr>
            	<td><?php echo date('d-m-Y', strtotime($dema['DetalleActivoFijoMantencion']['dema_fecha_servicio']))?></td>
                <td><?php echo number_format($dema['DetalleActivoFijoMantencion']['dema_kilometraje'], 0, ',', '.');?></td>
                <td><?php echo $dema['DetalleActivoFijoMantencion']['dema_descripcion'];?></td>
                <td><?php echo $dema['DetalleActivoFijoMantencion']['dema_nombre_operador'];?></td>
                <td><?php echo number_format($dema['DetalleActivoFijoMantencion']['dema_valor'], 0, ',', '.');?></td>
                <td><?php echo $dema['DetalleActivoFijoMantencion']['dema_observacion'];?></td>
            </tr>
        <?php
			}
		?>
    </table>
    <table width="100%">
    	<tr>
        	<td width="90%" style="text-align:right; border-bottom:#FFF;"><strong>Valor Total:</strong></td>
            <td style="border-bottom:#FFF;" id="valor_total">$ <?php echo number_format($infoMantencion['ActivoFijoMantencion']['afma_valor_total'], 0, ',', '.');?></td>
        </tr>
    </table>
</div>
<?php
	include("views/sidebars/menu.php");
?>