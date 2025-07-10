<html>
	<title>&nbsp;</title>
<body>
<table border="0" width="165%">
	<tr>
		<td width="88%"><img src="http://<?php echo $_SERVER['HTTP_HOST']?>/files/logo.png" /></td>
        <td valign="bottom" width="87%"><?php echo date("d-m-Y H:i:s");?></td>
	</tr>
</table>
<br />
<table border="0" width="100%">
	<tr>
		<td align="center" width="100%"><font size="5"><strong>COMPROBANTE DE MANTENCI&Oacute;N</strong></font></td>
	</tr>
</table>
<br />
<table border="1" width="100%">
	<tr>
		<td width="30%"><strong>Correlativo</strong></td>
		<td width="70%"><font size="4"><strong><?php echo sprintf("%012d", $infoMantencion['ActivoFijoMantencion']['afma_correlativo']); ?></strong></font>&nbsp;</td>
	</tr>
	<tr>
		<td><strong>Centro de Costo</strong></td>
		<td><?php echo utf8_decode($infoMantencion['CentroCosto']['ceco_nombre']); ?>&nbsp;</td>
	</tr>
    <tr>
		<td><strong>Producto</strong></td>
		<td><?php echo utf8_decode($infoMantencion['UbicacionActivoFijo']['Producto']['prod_nombre']); ?>&nbsp;</td>
	</tr>
    <tr>
		<td><strong>C&oacute;digo Producto</strong></td>
		<td><?php echo $infoMantencion['UbicacionActivoFijo']['ubaf_codigo']; ?>&nbsp;</td>
	</tr>
    <tr>
		<td><strong>N&uacute;mero de Factura</strong></td>
		<td><?php echo $infoMantencion['ActivoFijoMantencion']['afma_numero_factura']; ?>&nbsp;</td>
	</tr>
    <tr>
		<td><strong>Fecha de Factura</strong></td>
		<td><?php echo date('d-m-Y', strtotime($infoMantencion['ActivoFijoMantencion']['afma_fecha_factura'])); ?>&nbsp;</td>
	</tr>
	<tr>
		<td><strong>Proveedor</strong></td>
		<td><?php echo utf8_decode($infoMantencion['Proveedor']['prov_nombre']); ?>&nbsp;</td>
	</tr>
    <tr>
		<td><strong>Modelo</strong></td>
		<td><?php echo utf8_decode($infoMantencion['ActivoFijoMantencion']['afma_modelo']); ?>&nbsp;</td>
	</tr>
    <tr>
		<td><strong>Marca</strong></td>
		<td><?php echo utf8_decode($infoMantencion['ActivoFijoMantencion']['afma_marca']); ?>&nbsp;</td>
	</tr>
    <tr>
		<td><strong>A&ntilde;o</strong></td>
		<td><?php echo $infoMantencion['ActivoFijoMantencion']['afma_ano']; ?>&nbsp;</td>
	</tr>
    <tr>
		<td><strong>Patente</strong></td>
		<td><?php echo utf8_decode($infoMantencion['ActivoFijoMantencion']['afma_patente']); ?>&nbsp;</td>
	</tr>
    <tr>
		<td><strong>Motor</strong></td>
		<td><?php echo utf8_decode($infoMantencion['ActivoFijoMantencion']['afma_motor']); ?>&nbsp;</td>
	</tr>
</table>
<table width="100%" border="1">
    <tr>
        <th width="13%">Fecha Servicio</th>
        <th>Kilometraje</th>
        <th>Trabajo y/o Servicio</th>
        <th>Operador</th>
        <th>Costo</th>
        <th>Observaci&oacute;n</th>
    </tr>
    <?php
        foreach ($infoDetalleMantencion as $dema) {
    ?>
        <tr>
            <td><?php echo date('d-m-Y', strtotime($dema['DetalleActivoFijoMantencion']['dema_fecha_servicio']))?></td>
            <td align="right"><?php echo number_format($dema['DetalleActivoFijoMantencion']['dema_kilometraje'], 0, ',', '.');?></td>
            <td><?php echo utf8_decode($dema['DetalleActivoFijoMantencion']['dema_descripcion']);?></td>
            <td><?php echo utf8_decode($dema['DetalleActivoFijoMantencion']['dema_nombre_operador']);?></td>
            <td align="right"><?php echo number_format($dema['DetalleActivoFijoMantencion']['dema_valor'], 0, ',', '.');?></td>
            <td><?php echo utf8_decode($dema['DetalleActivoFijoMantencion']['dema_observacion']);?></td>
        </tr>
    <?php
        }
    ?>
</table>
<table width="30%" border="1" id="table_resumen" align="right">
	<tr>
        <td width="50%" style="text-align:right; border-bottom:#FFF;"><strong>Valor Total:</strong></td>
        <td width="50%" style="border-bottom:#FFF;" id="valor_total" align="right">$ <?php echo number_format($infoMantencion['ActivoFijoMantencion']['afma_valor_total'], 0, ',', '.');?></td>
    </tr>
</table>

