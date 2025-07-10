
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
		<td align="center" width="100%"><font size="5"><strong>COMPROBANTE DE INGRESO EXISTENCIAS</strong></font></td>
	</tr>
</table>
<br />
<table border="1" width="100%">
	<tr>
		<td width="30%"><strong>Folio/Nº de Entrada</strong></td>
		<td><font size="4"><strong><?php echo sprintf("%012d", $info['Existencia']['exis_correlativo']); ?></strong></font>&nbsp;</td>
	</tr>
	<tr>
		<td><strong>Centro de Costo</strong></td>
		<td><?php echo utf8_decode($info['CentroCosto']['ceco_nombre']); ?>&nbsp;</td>
	</tr>
	<tr>
		<td><strong>Proveedor</strong></td>
		<td><?php echo utf8_decode($info['Proveedor']['prov_nombre']); ?>&nbsp;</td>
	</tr>
	<tr>
		<td><strong>Orden de Compra</strong></td>
		<td><?php echo utf8_decode($info['Existencia']['exis_orden_compra']); ?>&nbsp;</td>
	</tr>
	<tr>
		<td><strong>Tipo de Documento</strong></td>
		<td><?php echo utf8_decode($info['TipoDocumento']['tido_descripcion']); ?>&nbsp;</td>
	</tr>
	<tr>
		<td><strong>N&uacute;mero de Documento</strong></td>
		<td><?php echo utf8_decode($info['Existencia']['exis_nro_documento']); ?>&nbsp;</td>
	</tr>
	<tr>
		<td><strong>Fecha de Documento</strong></td>
		<td>
			<?php
				if (trim($info['Existencia']['exis_fecha_documento']) != "") {
					echo date("d-m-Y", strtotime($info['Existencia']['exis_fecha_documento']));
				}
			?>
			&nbsp;
		</td>
	</tr>
	<tr>
		<td><strong>Fecha de Recepci&oacute;n</strong></td>
		<td>
			<?php
				if (trim($info['Existencia']['exis_fecha_compra']) != "") {
					echo date("d-m-Y", strtotime($info['Existencia']['exis_fecha_compra']));
				}
			?>
			&nbsp;
		</td>
	</tr>
	<tr>
		<td><strong>Fecha de Ingreso</strong></td>
		<td>
			<?php
				if (trim($info['Existencia']['exis_fecha']) != "") {
					echo date("d-m-Y", strtotime($info['Existencia']['exis_fecha']));
				}
			?>
			&nbsp;
		</td>
	</tr>
	<tr>
		<td><strong>Descripci&oacute;n</strong></td>
		<td><?php echo utf8_decode($info['Existencia']['exis_descripcion']); ?>&nbsp;</td>
	</tr>
	<tr>
		<td><strong>Observaciones</strong></td>
		<td><?php echo utf8_decode($info['Existencia']['exis_observaciones']); ?>&nbsp;</td>
	</tr>
</table>

<br />

<table width="100%" border="1">
	<tr>
		<th width="20%"><strong>Producto</strong></th>
		<th><strong>Cantidad</strong></th>
		<th><strong>Precio Unitario</strong></th>
		<th><strong>Valor Neto</strong></th>
		<th><strong>Serie</strong></th>
		<th><strong>Vencimiento</strong></th>
	</tr>
	<?php
		$sum_total_neto = 0;
		foreach ($info_deex as $deex) {
	?>
	<tr>
		<td><?php echo utf8_decode($deex['Producto']['prod_nombre']); ?>&nbsp;</td>
		<td><?php echo number_format($deex['DetalleExistencia']['deex_cantidad'], 0, ",", "."); ?>&nbsp;</td>
		<td>$<?php
        		if (preg_match("/\./", $deex['DetalleExistencia']['deex_precio'])) {
					echo number_format($deex['DetalleExistencia']['deex_precio'], 2, ",", ".");
				} else {
					echo number_format($deex['DetalleExistencia']['deex_precio'], 0, "", ".");
				} 
		?>&nbsp;</td>	
		<td><?php echo number_format(round($deex['DetalleExistencia']['deex_precio']*$deex['DetalleExistencia']['deex_cantidad'], 0), 0, ",", "."); ?>&nbsp;</td>
		<td><?php echo $deex['DetalleExistencia']['deex_serie']; ?>&nbsp;</td>
		<td>
			<?php
				if (trim($deex['DetalleExistencia']['deex_fecha_vencimiento']) != "") {
					echo date("d-m-Y", strtotime($deex['DetalleExistencia']['deex_fecha_vencimiento']));
				}
			?>
			&nbsp;
		</td>
	</tr>
	<?php
			$sum_total_neto += ($deex['DetalleExistencia']['deex_precio']*$deex['DetalleExistencia']['deex_cantidad']);
		}
	?>
</table>
<br />

<table width="30%" align="right" border="1">
	<tr>
		<td><strong>Total Neto</strong></td>
		<td align="right">$<?php echo number_format($sum_total_neto, 0, ",", "."); ?></td>
	</tr>
	<tr>
		<td><strong>IVA (19%)</strong></td>
		<td align="right">$0</td>
	</tr>
	<tr>
		<td><strong>Total</strong></td>
		<td align="right">$<?php echo number_format(round($sum_total_neto, 0), 0, ",", "."); ?></td>
	</tr>
</table>

</html>
</body>
