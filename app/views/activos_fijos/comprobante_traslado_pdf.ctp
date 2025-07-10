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
		<td align="center" width="100%"><font size="5"><strong>COMPROBANTE DE TRASLADO ACTIVO FIJO</strong></font></td>
	</tr>
</table>
<table border="1" width="100%">
	<tr>
		<td width="30%"><strong>Correlativo</strong></td>
		<td><font size="4"><strong><?php echo sprintf("%012d", $info['ActivoFijo']['acfi_correlativo']); ?></strong></font>&nbsp;</td>
	</tr>
    	<tr>
		<td><strong>Centro de Costo Desde</strong></td>
		<td><?php echo utf8_decode($info['CentroCosto']['ceco_nombre']); ?>&nbsp;</td>
	</tr>
    <tr>
		<td><strong><?php echo utf8_decode('Ubicacion Desde');?></strong></td>
		<td><?php echo utf8_decode($ubicacion); ?>&nbsp;</td>
	</tr>
    <tr>
		<td><strong>Centro de Costo Hacia</strong></td>
		<td><?php echo utf8_decode($info['CentroCostoHijo']['ceco_nombre']); ?>&nbsp;</td>
	</tr>
    <tr>
		<td><strong><?php echo utf8_decode('Ubicacion Hacia');?></strong></td>
		<td><?php echo utf8_decode($ubicacionPadre); ?>&nbsp;</td>
	</tr>
    <tr>
		<td><strong>Fecha</strong></td>
		<td>
			<?php
				if (trim($info['ActivoFijo']['acfi_fecha']) != "") {
					echo date("d-m-Y H:i:s", strtotime($info['ActivoFijo']['acfi_fecha']));
				}
			?>
			&nbsp;
		</td>
	</tr>
    <tr>
		<td><strong>Descripciones</strong></td>
		<td><?php echo utf8_decode($info['ActivoFijo']['acfi_descripcion']); ?>&nbsp;</td>
	</tr>
    <tr>
		<td><strong>Observaciones</strong></td>
		<td><?php echo utf8_decode($info['ActivoFijo']['acfi_observaciones']); ?>&nbsp;</td>
	</tr>
</table>
<table width="100%" border="1">
	<tr>
		<th>C&oacute;digo</th>
		<th>Producto</th>
        <th>Situaci&oacute;n</th>
        <th>Color</th>
        <th>Modelo</th>
        <th>Serie</th>
		<th>Precio Unitario</th>
		<th>&iquest;Es Depreciable?</th>
        <th>Vida &Uacute;til</th>
	</tr>
		<?php
			$total = 0;
			foreach ($info['DetalleActivoFijo'] as $deaf) {
		?>
	<tr>
		<td><?php echo $deaf['deaf_codigo']; ?></td>
		<td><?php echo utf8_decode($deaf['Producto']['prod_nombre']); ?></td>
        <td><?php echo utf8_decode($deaf['Situacion']['situ_nombre']); ?></td>
        <td><?php
				if (isset($deaf['Color']['colo_nombre'])){
					echo utf8_decode($deaf['Color']['colo_nombre']);
				}
					
			?>
        </td>
        <td><?php
        		if (isset($deaf['Modelo']['mode_nombre'])) {
					echo $deaf['Modelo']['mode_nombre'];
				}
					
		?>
        </td>
        <td><?php
        		if (isset($deaf['deaf_serie'])) {
					echo $deaf['deaf_serie'];
				}
		?>
        </td>
		<td align="right">$<?php echo $deaf['deaf_precio']; ?></td>
		<td align="center">
			<?php
				if ($deaf['deaf_depreciable'] == 1) {
					echo "Si";
				} else {
					echo "No";
				}
			?>
		</td>
        <td><?php if (isset($deaf['deaf_vida_util'])){
				   	  echo $deaf['deaf_vida_util'];
				  }
				      echo $deaf['deaf_vida_util'] = null;
			?>
        </td>
	</tr>
		<?php
			$total += $deaf['deaf_precio'];
		}
		?>
</table>			
<br />
<table width="103%" align="center">
	<tr>
		<td>
			<table width="310px" align="left" border="1" valign="top">
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
			<table width="170px" align="right" border="1" valign="top" id="table_resumen">
				<tr>
					<td colspan="2" align="center"><strong>C&aacute;lculos Totales</strong></td>
				</tr>
				<tr>
					<td><strong>Total Neto:</strong></td>
					<td align="right">$<?php echo number_format($total, 0, ",", "."); ?>&nbsp;</td>
				</tr>
				<tr>
					<td><strong>IVA (<?php echo $valor_iva;?>%):</strong></td>
					<td align="right" id="td_iva">$<?php echo number_format(round(($total * $valor_iva) / 100), 0, ",", ".");?>&nbsp;</td>
				</tr>
				<tr>
					<td><strong>Total:</strong></td>
					<?php
                      				$total_monto = round($total + ($total * $valor_iva / 100));
                    			?>
					<td align="right" id="td_total">$<?php echo number_format(round($total_monto, 0), 0, ",", "."); ?>&nbsp;</td>
				</tr>
			</table>
		</td>	
	</tr>
</table>
</body>
</html>
