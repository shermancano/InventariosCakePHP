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
		<td align="center" width="100%"><font size="5"><strong>REPORTE DE STOCK ACTIVOS FIJOS <?php echo utf8_decode($info_cc['CentroCosto']['ceco_nombre']);?></strong></font></td>
	</tr>
</table>
<br />
<table border="1" width="100%">
	<tr padding="4px">
		<td align="center"><strong>C&oacute;digo</strong></td>
		<td align="center"><strong>Nombre</strong></td>
		<td align="center"><strong>Propiedad</strong></td>
		<td align="center"><strong>Situaci&oacute;n</strong></td>
		<td align="center"><strong>Marca</strong></td>
		<td align="center"><strong>Color</strong></td>
		<td align="center"><strong>Fecha Garant&iacute;a</strong></td>
        <td align="center"><strong>Precio</strong></td>
        <td align="center"><strong>Serie</strong></td>
        <td align="center"><strong>&iquest;Es Depreciable?</strong></td>
        <td align="center"><strong>Vida &Uacute;til</strong></td>
        <td align="center"><strong>Centro de Costo (Unidad)</strong></td>
		<td align="center"><strong>Dependencia</strong></td>
		<td align="center"><strong>Establecimiento</strong></td>
		
	</tr>
	<?php
		foreach ($info as $deaf) {
			$deaf = array_pop($deaf);
		
	?>
	<tr>
		<td><?php echo utf8_decode($deaf['ubaf_codigo']);?>&nbsp;</td>
        <td><?php echo utf8_decode($deaf['prod_nombre']);?>&nbsp;</td>
        <td><?php echo utf8_decode($deaf['prop_nombre']);?>&nbsp;</td>
        <td><?php echo utf8_decode($deaf['situ_nombre']);?>&nbsp;</td>
        <td><?php echo utf8_decode($deaf['marc_nombre']);?>&nbsp;</td>
        <td><?php echo utf8_decode($deaf['colo_nombre']);?>&nbsp;</td>
		<td><?php if (isset($deaf['ubaf_fecha_garantia'])){
					  echo date("d-m-Y", strtotime ($deaf['ubaf_fecha_garantia']));
		          }
				      echo $deaf['ubaf_fecha_garantia'] = null;
			?>&nbsp;</td>
		<td><?php echo $deaf['ubaf_precio']; ?>&nbsp;</td>
        <td><?php echo $deaf['ubaf_serie'];?>&nbsp;</td>
		<td align="center"><?php if ($deaf['ubaf_depreciable'] == 1) {
						echo "Si";
				  } else {
						echo "No";
				  }
			?>&nbsp;</td>
		<td>
			<?php
				if (isset($deaf['ubaf_vida_util'])){
					echo $deaf['ubaf_vida_util'];
				}
				echo $deaf['ubaf_vida_util'] = null;
			?>&nbsp;</td>
		<td><?php echo utf8_decode($deaf['hijo']);?>&nbsp;</td>
		<td><?php echo utf8_decode($deaf['padre']);?>&nbsp;</td>
		<td><?php echo utf8_decode($deaf['abuelo']);?>&nbsp;</td>  		
	</tr>
	<?php
		}
	?>
	
</table>
</body>
</html>
