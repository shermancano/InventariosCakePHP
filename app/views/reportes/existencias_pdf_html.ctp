<html>
	<title>&nbsp;</title>
<body marginwidth="5%" marginheight="5%">
<table border="0" width="165%">
	<tr>
		<td width="88%"><img src="http://<?php echo $_SERVER['HTTP_HOST']?>/files/logo.png" /></td>
        <td valign="bottom" width="87%"><?php echo date("d-m-Y H:i:s");?></td>
	</tr>
</table>
<br />

<table border="0" width="100%">
	<tr>
		<td align="center" width="100%"><font size="5"><strong>REPORTE DE EXISTENCIA <?php echo utf8_decode($info_cc['CentroCosto']['ceco_nombre']);?></strong><font></td>
	</tr>
</table>

<br />

<table border="1" width="100%">
	<tr padding="4px">
		<td align="center"><strong>Nombre</strong></td>
		<td align="center"><strong>Código Interno</strong></td>
		<td align="center"><strong>Tipo de Bien</strong></td>
		<td align="center"><strong>Familia</strong></td>
		<td align="center"><strong>Grupo</strong></td>
        <td align="center"><strong>Stock Crítico (por CS/CC)</strong></td>
        <td align="center"><strong>Serie</strong></td>
        <td align="center"><strong>Fecha Vencimiento</strong></td>
        <td align="center"><strong>Precio</strong></td>
        <td align="center"><strong>Stock Total</strong></td>
        <td align="center"><strong>Precio Total</strong></td>
	</tr>
	<?php
		$precio_total = 0;
		foreach ($info as $row) {
			$row = array_pop($row);
			$sum_precio_total = round($row['deex_precio']*$row['total_stock'], 0);
	?>
	<tr>
		<td><?php echo utf8_decode($row['prod_nombre']); ?>&nbsp;</td>
		<td><?php echo utf8_decode($row['prod_codigo']); ?>&nbsp;</td>
		<td><?php echo utf8_decode($row['tibi_nombre']); ?>&nbsp;</td>
		<td><?php echo utf8_decode($row['fami_nombre']); ?>&nbsp;</td>
		<td><?php echo utf8_decode($row['grup_nombre']); ?>&nbsp;</td>
        <td><?php echo utf8_decode($row['stcc_stock_critico']); ?>&nbsp;</td>
        <td><?php echo utf8_decode($row['deex_serie']); ?>&nbsp;</td>
        <td><?php echo date ("d-m-Y", strtotime($row['deex_fecha_vencimiento'])); ?>&nbsp;</td>
        <td><?php echo utf8_decode($row['deex_precio']); ?>&nbsp;</td>
        <td><?php echo utf8_decode($row['total_stock']); ?>&nbsp;</td>
        <td><?php echo $sum_precio_total; ?>&nbsp;</td>
	</tr>
	<?php
			$precio_total += round($row['deex_precio']*$row['total_stock'], 0);
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

</body>
</html>

