<html>
	<title>&nbsp;</title>
<body topmargin="5px">
<div>
<table border="0" width="165%">
	<tr>
		<td width="88%"><img src="http://<?php echo $_SERVER['HTTP_HOST']?>/files/logo.png" /></td>
        <td valign="bottom" width="87%"><?php echo date("d-m-Y H:i:s");?></td>
	</tr>
</table>
<br />

<table border="0" width="100%">
	<tr>
		<td align="center" width="100%"><font size="5"><strong>REPORTE DE STOCK EXISTENCIAS <?php echo utf8_decode($info_cc['CentroCosto']['ceco_nombre']);?></strong></font></td>
	</tr>
</table>

<br />

<table border="1" width="100%">
	<tr padding="4px">
		<td align="center"><strong>ID Producto</strong></td>
		<td align="center"><strong>Nombre</strong></td>
		<td align="center"><strong>Código Interno</strong></td>
		<td align="center"><strong>Tipo de Bien</strong></td>
		<td align="center"><strong>Familia</strong></td>
		<td align="center"><strong>Grupo</strong></td>
		<td align="center"><strong>Stock Crítico (por CS/CC)</strong></td>
		<td align="center"><strong>Stock Total</strong></td>
		
	</tr>
	<?php
		foreach ($info as $row) {
			$row = array_pop($row);
			$total = $row['total_entradas']-$row['total_traslados'];
		
	?>
	<tr>
		<td><?php echo utf8_decode($row['prod_id']); ?>&nbsp;</td>
		<td><?php echo utf8_decode($row['prod_nombre']); ?>&nbsp;</td>
		<td><?php echo utf8_decode($row['prod_codigo']); ?>&nbsp;</td>
		<td><?php echo utf8_decode($row['tibi_nombre']); ?>&nbsp;</td>
		<td><?php echo utf8_decode($row['fami_nombre']); ?>&nbsp;</td>
		<td><?php echo utf8_decode($row['grup_nombre']); ?>&nbsp;</td>
		<td><?php echo $row['stcc_stock_critico']; ?>&nbsp;</td>
		<td><?php echo $total; ?></td>		
	</tr>
	<?php
		}
	?>
	
</table>
</body>
</html>

