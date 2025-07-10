<html>
	<title>&nbsp;</title>
<body marginwidth="5%" marginheight="5%">

<?php
	foreach ($info as $ceco) {
		$ceco_nombre = $ceco[0]['ceco_nombre'];
?>
<table border="0" width="165%">
	<tr>
		<td width="88%"><img src="http://<?php echo $_SERVER['HTTP_HOST']?>/files/logo.png" /></td>
        <td valign="bottom" width="87%"><?php echo date("d-m-Y H:i:s");?></td>
	</tr>
</table>
<br />

<table border="0" width="100%">
	<tr>
		<td align="center" width="100%"><font size="5"><strong>REPORTE GENERAL DE ACTIVOS FIJOS <?php echo utf8_decode($ceco_nombre);?></strong><font></td>
	</tr>
</table>
<br />
<table border="1" width="100%">
	<tr padding="4px">
		<td align="center"><strong>Centro de Costo/Salud</strong></td>
		<td align="center"><strong>Producto</strong></td>
		<td align="center"><strong>Tipo Bien</strong></td>
		<td align="center"><strong>Familia</strong></td>
        <td align="center"><strong>Grupo</strong></td>
        <td align="center"><strong>Propiedad</strong></td>
        <td align="center"><strong>Situaci&oacute;n</strong></td>
        <td align="center"><strong>Marca</strong></td>
        <td align="center"><strong>Color</strong></td>
        <td align="center"><strong>Modelo</strong></td>
        <td align="center"><strong>Precio</strong></td>
        <td align="center"><strong>Stock Total</strong></td>
	</tr>
	<?php
		$precio_total = 0;
		foreach ($ceco as $row) {
			$sum_precio_total = round($row['ubaf_precio']*$row['total'], 0);
	?>
	<tr>
		<td><?php echo utf8_decode($row['ceco_nombre']); ?>&nbsp;</td>
		<td><?php echo utf8_decode($row['prod_nombre']); ?>&nbsp;</td>
		<td><?php echo utf8_decode($row['tibi_nombre']); ?>&nbsp;</td>
		<td><?php echo utf8_decode($row['fami_nombre']); ?>&nbsp;</td>
        <td><?php echo utf8_decode($row['grup_nombre']); ?>&nbsp;</td>
        <td><?php echo utf8_decode($row['prop_nombre']); ?>&nbsp;</td>
        <td><?php echo utf8_decode($row['situ_nombre']); ?>&nbsp;</td>
        <td><?php echo utf8_decode($row['marc_nombre']); ?>&nbsp;</td>
        <td><?php echo utf8_decode($row['colo_nombre']); ?>&nbsp;</td>
        <td><?php echo utf8_decode($row['mode_nombre']); ?>&nbsp;</td>
        <td><?php echo utf8_decode($row['ubaf_precio']); ?>&nbsp;</td>
        <td><?php echo utf8_decode($row['total']); ?>&nbsp;</td>
	</tr>
	<?php
			$precio_total += round($row['ubaf_precio']*$row['total'], 0);
		}
	?>
</table>
<br />

<table border="1" align="right" width="30%">
	<tr>
    	<td><strong>Total Neto</strong></td>
        <td align="right">$<?php echo number_format($precio_total, 0, ",", "."); ?></td>
    </tr>
    <tr>
    	<td><strong>IVA (<?php echo $valor_iva;?>%)</strong></td>
        <td align="right">$<?php echo number_format(round(($precio_total * $valor_iva) / 100), 0, ",", ".");?></td>
    </tr>
    <tr>
    	<td><strong>Total</strong></td>
        <td align="right">$<?php echo number_format(round($precio_total, 0), 0, ",", "."); ?></td>
    </tr>
</table>

<?php
	}
?>

</body>
</html>