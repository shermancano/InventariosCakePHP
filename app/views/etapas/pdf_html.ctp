<html>
<head>
	<title>&nbsp;</title>
</head>

<body>
<table border="1" width="100%">
   <tr>
      <th colspan="2">MONITOREO DE EJECUCION DE CONTRATOS</th>
   </tr>
   <tr>
      <td width="40%">Nombre del Proveedor</td>
      <td id="prov_nombre"><?php echo $info_res->info_cont->prov_nombre; ?></td>
   </tr>
   <tr>
      <td width="40%">Nombre de Adquisici&oacute;n/Contrato</td>
      <td id="cont_nombre"><?php echo utf8_decode($info_res->info_cont->cont_nombre); ?></td>
   </tr>
   </tr>
</table>
<br />

<table border="1" width="100%" id="resumen_table">
	<tr>
	   <th width="8%">&nbsp;</th>
	   <th width="25%">&nbsp;</th>
	   <th width="20%">Fecha Presupuestada</th>
	   <th width="20%">Fecha Real</th>
	   <th width="20%">Diferencia de D&iacute;as</th>
	   <th>Cumple</th>
	</tr>
	<?php
		$total_dias_final = 0;
		foreach ($info_res->info_res as $res) {
			$res = (array)$res;
			$count = 1;
			$total_dias = 0;
	?>
		<tr>
			<th colspan="6" align="left"><?php echo utf8_decode($res['etap_nombre']); ?></th>
		</tr>
		<?php
			foreach ($res['info_act'] as $act) {
				$act = (array)$act;
				$total_dias += $act['diferencia'];
		?>
			<tr>
				<td><?php echo $count; ?></td>
				<td><?php echo utf8_decode($act['acti_nombre']); ?></td>
				<td><?php echo $act['acti_fecha_presupuestada']; ?></td>
				<td><?php echo $act['acti_fecha_real']; ?></td>
				<td><?php echo $act['diferencia']; ?></td>
				<td><?php echo $act['cumple']; ?></td>
			</tr>	
		<?php
				$count++;
			}
		?>
		<tr>
			<td>&nbsp;</td>
			<td><strong>Total d&iacute;as</strong></td>
			<td>&nbsp;</td>
			<td>&nbsp;</td>
			<td><strong><?php echo $total_dias; ?></strong></td>
			<td>&nbsp;</td>
		</tr>
	<?php
			$total_dias_final += $total_dias;
		}
	?>
</table>
<br />
<table border="1" width="100%" id="resumen_table">
	<tr>
		<td width="8%">&nbsp;</td>
		<td width="25%"><strong>Total d&iacute;as final</strong></td>
		<td width="20%">&nbsp;</td>
		<td width="20%">&nbsp;</td>
		<td width="20%"><strong><?php echo $total_dias_final; ?></strong></td>
		<td>&nbsp;</td>
	</tr>
</table>

</body>
</html>
