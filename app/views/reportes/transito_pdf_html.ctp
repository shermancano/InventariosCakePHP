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

<?php
	if ($tibi_id == 1) :
?>

<table border="0" width="100%">
	<tr>
		<td align="center" width="100%"><font size="5"><strong>REPORTE DE TRANSITO DE ACTIVOS FIJOS (ITEMS NO RECEPCIONADOS)</strong><font></td>
	</tr>
</table>

<br />

<table border="1" width="100%">
	<tr padding="4px">
		<td align="center"><strong>ID Producto</strong></td>
		<td align="center"><strong>Código</strong></td>
		<td align="center"><strong>Nombre</strong></td>
		<td align="center"><strong>Tipo de Bien</strong></td>
		<td align="center"><strong>Propiedad</strong></td>
		<td align="center"><strong>Situaci&oacute;n</strong></td>
		<td align="center"><strong>Marca</strong></td>
		<td align="center"><strong>Color</strong></td>
		<td align="center"><strong>Fecha de Garant&iacute;a</strong></td>
		<td align="center"><strong>Precio</strong></td>
		<td align="center"><strong>¿Es Depreciable?</strong></td>
		<td align="center"><strong>Vida &Uacute;til</strong></td>
		<td align="center"><strong>Tipo de Movimiento</strong></td>
		<td align="center"><strong>Centro de Costo Padre</strong></td>
		<td align="center"><strong>Centro de Costo</strong></td>
		<td align="center"><strong>Centro de Costo Hijo</strong></td>
	</tr>
	<?php
		foreach ($info as $row) {
			$row = array_pop($row);
	?>
	<tr>
		<td><?php echo $row['prod_id']; ?>&nbsp;</td>
		<td><?php echo $row['deaf_codigo']; ?>&nbsp;</td>
		<td><?php echo utf8_decode($row['prod_nombre']); ?>&nbsp;</td>
		<td><?php echo utf8_decode($row['tibi_nombre']); ?>&nbsp;</td>
		<td><?php echo utf8_decode($row['prop_nombre']); ?>&nbsp;</td>
		<td><?php echo utf8_decode($row['situ_nombre']); ?>&nbsp;</td>
		<td><?php echo utf8_decode($row['marc_nombre']); ?>&nbsp;</td>
		<td><?php echo utf8_decode($row['colo_nombre']); ?>&nbsp;</td>
		<td>
		<?php
			if (trim($row['deaf_fecha_garantia']) != "") {
				echo date("d-m-Y H:i:s", strtotime($row['deaf_fecha_garantia']));
			}
		?>&nbsp;
		</td>
		<td><?php echo $row['deaf_precio']; ?>&nbsp;</td>
		<td>
		<?php
			if ($row['deaf_depreciable'] == 1) {
				echo "Si";
			} else {
				echo "No";
			}
		?>&nbsp;
		</td>
		<td><?php echo $row['deaf_vida_util']; ?>&nbsp;</td>
		<td><?php echo utf8_decode($row['tmov_descripcion']); ?>&nbsp;</td>
		<td><?php echo utf8_decode($row['ceco_padre']); ?>&nbsp;</td>
		<td><?php echo utf8_decode($row['ceco_nombre']); ?>&nbsp;</td>
		<td><?php echo utf8_decode($row['ceco_hijo']); ?>&nbsp;</td>
	</tr>
	<?php
		}
	?>
</table>

<?php
	else:
?>
<table border="0" width="100%">
	<tr>
		<td align="center" width="100%"><font size="5"><strong>REPORTE DE TRANSITO DE EXISTENCIAS (ITEMS NO RECEPCIONADOS)</strong><font></td>
	</tr>
</table>

<br />

<table border="1" width="100%">
	<tr padding="4px">
		<td align="center"><strong>ID Producto</strong></td>
		<td align="center"><strong>Código Interno</strong></td>
		<td align="center"><strong>Nombre</strong></td>
		<td align="center"><strong>Tipo de Bien</strong></td>
		<td align="center"><strong>Familia</strong></td>
		<td align="center"><strong>Grupo</strong></td>
		<td align="center"><strong>Cantidad</strong></td>
		<td align="center"><strong>Precio</strong></td>
		<td align="center"><strong>Serie</strong></td>
		<td align="center"><strong>Fecha de Vencimiento</strong></td>
		<td align="center"><strong>Tipo de Movimiento</strong></td>
		<td align="center"><strong>Centro de Costo Padre</strong></td>
		<td align="center"><strong>Centro de Costo</strong></td>
		<td align="center"><strong>Centro de Costo Hijo</strong></td>
	</tr>
	<?php
		foreach ($info as $row) {
			$row = array_pop($row);
	?>
	<tr>
		<td><?php echo $row['prod_id']; ?>&nbsp;</td>
		<td><?php echo $row['prod_codigo']; ?>&nbsp;</td>
		<td><?php echo utf8_decode($row['prod_nombre']); ?>&nbsp;</td>
		<td><?php echo utf8_decode($row['tibi_nombre']); ?>&nbsp;</td>
		<td><?php echo utf8_decode($row['fami_nombre']); ?>&nbsp;</td>
		<td><?php echo utf8_decode($row['grup_nombre']); ?>&nbsp;</td>
		<td><?php echo $row['deex_cantidad']; ?>&nbsp;</td>
		<td><?php echo $row['deex_precio']; ?>&nbsp;</td>
		<td><?php echo $row['deex_serie']; ?>&nbsp;</td>
		<td>
		<?php
			if (trim($row['deex_fecha_vencimiento']) != "") {
				echo date("d-m-Y", strtotime($row['deex_fecha_vencimiento']));
			}
		?>&nbsp;
		</td>
		<td><?php echo $row['tmov_descripcion']; ?>&nbsp;</td>
		<td><?php echo utf8_decode($row['ceco_padre']); ?>&nbsp;</td>
		<td><?php echo utf8_decode($row['ceco_nombre']); ?>&nbsp;</td>
		<td><?php echo utf8_decode($row['ceco_hijo']); ?>&nbsp;</td>
	</tr>
	<?php
		}
	?>
</table>
<?php
	endif;
?>
	
</body>
</html>