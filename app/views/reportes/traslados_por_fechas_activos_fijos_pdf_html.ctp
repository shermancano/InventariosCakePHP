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
		<td align="center" width="100%"><font size="5"><strong>REPORTE TRASLADOS DE ACTIVOS FIJOS <?php echo utf8_decode($info_cc['CentroCosto']['ceco_nombre']);?></strong><font></td>
	</tr>
</table>
<br />
<table border="1" width="100%">
	<tr padding="4px">
    	<td align="center"><strong>C&oacute;digo</strong></td>
		<td align="center"><strong>Nombre</strong></td>
        <td align="center"><strong>Centro de Costo/Salud Destino</strong></td>
        <td align="center"><strong>Tipo Bien</strong></td>
        <td align="center"><strong>Fecha</strong></td>
        <td align="center"><strong>Marca</strong></td>
        <td align="center"><strong>Propiedad</strong></td>
        <td align="center"><strong>Color</strong></td>
        <td align="center"><strong>Situaci&oacute;n</strong></td>
        <td align="center"><strong>Modelo</strong></td>
        <td align="center"><strong>Serie</strong></td>
        <td align="center"><strong>Fecha Adquisici&oacute;n</strong></td>
        <td align="center"><strong>Fecha Garant&iacute;a</strong></td>
	</tr>
	<?php
		foreach ($info as $row) {
			$row = array_pop($row);
	?>
	<tr>
    	<td><?php echo $row['deaf_codigo']; ?>&nbsp;</td>
		<td><?php echo utf8_decode($row['prod_nombre']); ?>&nbsp;</td>
        <td><?php echo utf8_decode($row['ceco_nombre']); ?>&nbsp;</td>
        <td><?php echo $row['tibi_nombre'];?>&nbsp;</td>
        <td><?php echo date('d-m-Y', strtotime($row['acfi_fecha'])); ?>&nbsp;</td>
        <td><?php echo utf8_decode($row['marc_nombre']); ?>&nbsp;</td>
        <td><?php echo utf8_decode($row['prop_nombre']); ?>&nbsp;</td>
        <td><?php echo utf8_decode($row['colo_nombre']); ?>&nbsp;</td>
        <td><?php echo utf8_decode($row['situ_nombre']); ?>&nbsp;</td>
        <td><?php echo utf8_decode($row['mode_nombre']); ?>&nbsp;</td>
        <td><?php 
				if (isset($row['deaf_serie'])) {
					echo utf8_decode($row['deaf_serie']);
				}
				
		 	?>&nbsp;
        </td>
        <td><?php 
				if (isset($row['deaf_fecha_adquisicion'])) {
					echo date('d-m-Y', strtotime($row['deaf_fecha_adquisicion']));
				}
		 ?>&nbsp;</td>
        <td><?php
				if (isset($row['deaf_fecha_garantia'])) {
					echo date('d-m-y', strtotime($row['deaf_fecha_garantia']));				
				}
		 ?>&nbsp;</td>
	</tr>
	<?php
		}
	?>
</table>
</body>
</html>