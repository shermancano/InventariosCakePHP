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
		<td align="center" width="100%"><font size="5"><strong>REPORTE TRASLADO DE EXISTENCIA <?php echo utf8_decode($info_cc['CentroCosto']['ceco_nombre']);?></strong><font></td>
	</tr>
</table>

<br />
<table border="1" width="100%">
	<tr>
    	<td>Fecha Desde</td>
        <td><?php echo $fecha_desde;?></td>
    </tr>
    <tr>
    	<td>Fecha Hasta</td>
        <td><?php echo $fecha_hasta;?></td>
    </tr>
</table>
<br />
<table border="1" width="100%">
	<tr padding="4px">
		<td align="center"><strong>Producto</strong></td>
		<td align="center"><strong>Tipo Bien</strong></td>
		<td align="center"><strong>Centro de Costo/Salud Destino</strong></td>
		<td align="center"><strong>Fecha</strong></td>
        <td align="center"><strong>Serie</strong></td>
        <td align="center"><strong>Precio</strong></td>
        <td align="center"><strong>Cantidad</strong></td>
        <td align="center"><strong>Fecha Vencimiento</strong></td>
	</tr>
	<?php
		foreach ($info as $row) {
			$row = array_pop($row);
	?>
	<tr>
		<td><?php echo utf8_decode($row['prod_nombre']); ?>&nbsp;</td>
		<td><?php echo utf8_decode($row['tibi_nombre']); ?>&nbsp;</td>
		<td><?php echo utf8_decode($row['ceco_nombre']); ?>&nbsp;</td>
		<td><?php echo date('d-m-Y', strtotime($row['exis_fecha'])); ?>&nbsp;</td>
		<td><?php echo utf8_decode($row['deex_serie']); ?>&nbsp;</td>
        <td><?php echo $row['deex_precio']; ?>&nbsp;</td>
        <td><?php echo $row['deex_cantidad']; ?>&nbsp;</td>
        <td><?php echo date ("d-m-Y", strtotime($row['deex_fecha_vencimiento'])); ?>&nbsp;</td>
	</tr>
	<?php
		}
	?>
</table>
<br />
</body>
</html>
