
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
		<td align="center" width="100%"><font size="5"><strong>COMPROBANTE DE TRASLADO EXISTENCIAS</strong></font></td>
	</tr>
</table>
<br />
<table border="1" width="100%">
	<tr>
		<td><strong>Correlativo</strong></td>
		<td><strong><font size="4"><?php echo sprintf("%012d", $info['Existencia']['exis_correlativo']); ?></font></strong>&nbsp;</td>
	</tr>
	<tr>
		<td><strong>Centro de Costo "Desde"</strong></td>
		<td><?php echo utf8_decode($info['CentroCosto']['ceco_nombre']); ?>&nbsp;</td>
	</tr>
	<tr>
		<td><strong>Centro de Costo "Hasta"</strong></td>
		<td><?php echo utf8_decode($info['CentroCostoHijo']['ceco_nombre']); ?>&nbsp;</td>
	</tr>
	<tr>
		<td><strong>Fecha</strong></td>
		<td><?php echo date("d-m-Y H:i:s", strtotime($info['Existencia']['exis_fecha'])); ?>&nbsp;</td>
	</tr>
	<tr>
		<td><strong>Descripción</strong></td>
		<td><?php echo utf8_decode($info['Existencia']['exis_descripcion']); ?>&nbsp;</td>
	</tr>
	<tr>
		<td><strong>Observaciones</strong></td>
		<td><?php echo utf8_decode($info['Existencia']['exis_observaciones']); ?>&nbsp;</td>
	</tr>
</table>

<br />

<table width="100%" border="1">
	<tr padding="4px">
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
		<td align="right"><?php echo number_format($deex['DetalleExistencia']['deex_cantidad'], 0, ",", "."); ?>&nbsp;</td>
		<td align="right">$<?php if (preg_match("/\./", $deex['DetalleExistencia']['deex_precio'])) {
					echo number_format($deex['DetalleExistencia']['deex_precio'], 2, ",", ".");
				} else {
					echo number_format($deex['DetalleExistencia']['deex_precio'], 0, "", ".");
				} 
		?>&nbsp;</td>	
		<td align="right"><?php echo number_format(round($deex['DetalleExistencia']['deex_precio']*$deex['DetalleExistencia']['deex_cantidad'], 0), 0, ",", "."); ?>&nbsp;</td>
		<td align="right"><?php echo $deex['DetalleExistencia']['deex_serie']; ?>&nbsp;</td>
		<td align="center">
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

<table width="103%" align="center">
	<tr>
		<td>
			<table width="330px" align="left" border="1" valign="top">
				<tr>
					<td align="center" colspan="2"><strong>Recibo Conforme</strong></td>
				</tr>
				<tr>
					<td colspan="2"><strong>Nombre:</strong></td>
				</tr>
				<tr>
					<td colspan="2"><strong>RUT:</strong></td>
				</tr>
				<tr>
					<td colspan="2"><strong>Firma:</strong></td>
				</tr>
			</table>
		</td>
		<td>
			<table width="150px" align="right" border="1" valign="top">
				<tr>
					<td colspan="2" align="center"><strong>Cálculos Totales</strong></td>
				</tr>
				<tr>
					<td><strong>Total Neto:</strong></td>
					<td align="right">$<?php echo number_format($sum_total_neto, 0, ",", "."); ?>&nbsp;</td>
				</tr>
				<tr>
					<td><strong>IVA (19%):</strong></td>
					<td align="right">$0</td>
				</tr>
				<tr>
					<td><strong>Total:</strong></td>
					<td align="right">$<?php echo number_format(round($sum_total_neto, 0), 0, ",", "."); ?>&nbsp;</td>
				</tr>
			</table>
		</td>
	
	</tr>
</table>

</html>
</body>
