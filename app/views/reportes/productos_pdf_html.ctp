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
		<td align="center" width="100%"><font size="5"><strong>CATALOGO DE PRODUCTOS</strong><font></td>
	</tr>
</table>
<br />

<table border="1" width="100%">
	<tr padding="4px">
		<td align="center"><strong>ID Producto</strong></td>
		<td align="center"><strong>Código Interno</strong></td>
		<td align="center"><strong>Nombre</strong></td>
		<td align="center"><strong>Familia</strong></td>
		<td align="center"><strong>Grupo</strong></td>
		<td align="center"><strong>Tipo de Bien</strong></td>
		<td align="center"><strong>Tipo de Unidad</strong></td>
	</tr>
	<?php
		foreach ($info as $row) {
	?>
	<tr>
		<td><?php echo $row['Producto']['prod_id']; ?>&nbsp;</td>
		<td><?php echo $row['Producto']['prod_codigo']; ?>&nbsp;</td>
		<td><?php echo utf8_decode($row['Producto']['prod_nombre']); ?>&nbsp;</td>
		<td><?php echo utf8_decode($row['Grupo']['Familia']['fami_nombre']); ?>&nbsp;</td>
		<td><?php echo utf8_decode($row['Grupo']['grup_nombre']); ?>&nbsp;</td>
		<td><?php echo utf8_decode($row['TipoBien']['tibi_nombre']); ?>&nbsp;</td>
		<td><?php echo utf8_decode($row['Unidad']['unid_nombre']); ?>&nbsp;</td>
	</tr>
	<?php
		}
	?>
</table>

</body>
</html>