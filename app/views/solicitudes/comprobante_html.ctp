
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
		<td align="center" width="100%"><font size="5"><strong>COMPROBANTE DE SOLICITUD</strong></font></td>
	</tr>
</table>
<br />
<table border="1" width="100%">
	<tr>
		<td width="30%"><strong><?php echo utf8_decode("Folio/Nº de Solicitud"); ?></strong></td>
		<td><font size="4"><strong><?php echo sprintf("%012d", $info['Solicitud']['soli_correlativo']); ?></strong></font>&nbsp;</td>
	</tr>
	<tr>
		<td><strong>Desde</strong></td>
		<td><?php echo utf8_decode($info['CentroCosto']['ceco_nombre']); ?>&nbsp;</td>
	</tr>
	<tr>
		<td><strong>Hacia</strong></td>
		<td><?php echo utf8_decode($info['CentroCosto2']['ceco_nombre']); ?>&nbsp;</td>
	</tr>
	<tr>
		<td><strong>Proveedor</strong></td>
		<td><?php echo utf8_decode($info['Proveedor']['prov_nombre']); ?>&nbsp;</td>
	</tr>
	<tr>
		<td><strong>Fecha</strong></td>
		<td>
			<?php
				if (trim($info['Solicitud']['soli_fecha']) != "") {
					echo date("d-m-Y H:i:s", strtotime($info['Solicitud']['soli_fecha']));
				}
			?>
			&nbsp;
		</td>
	</tr>
	<tr>
		<td><strong>Tipo de Solicitud</strong></td>
		<td><?php echo utf8_decode($info['TipoSolicitud']['tiso_nombre']); ?>&nbsp;</td>
	</tr>
	<tr>
		<td><strong>Observaciones</strong></td>
		<td><?php echo utf8_decode($info['Solicitud']['soli_comentario']); ?>&nbsp;</td>
	</tr>
</table>

<br />

<table width="100%" border="1">
	<tr>
		<th width="70%"><strong>Producto</strong></th>
		<th><strong>Tipo de Bien</strong></th>
		<th><strong>Cantidad</strong></th>
	</tr>
	<?php
		$sum_total_neto = 0;
		foreach ($deso_info as $deso) {
	?>
	<tr>
		<td><?php echo utf8_decode($deso['Producto']['prod_nombre']); ?>&nbsp;</td>
		<td><?php echo utf8_decode($deso['Producto']['TipoBien']['tibi_nombre']); ?>&nbsp;</td>
		<td><?php echo $deso['DetalleSolicitud']['deso_cantidad'] ?>&nbsp;</td>
	</tr>
	<?php
		}
	?>
</table>

</html>
</body>