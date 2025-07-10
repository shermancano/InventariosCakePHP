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
		<td align="center" width="100%"><font size="5"><strong>ALTA DE INVENTARIO</strong></font></td>
	</tr>
</table>
<br />
<table border="1" width="100%">
	<tr>
		<td width="30%"><strong>Correlativo</strong></td>
		<td width="70%"><font size="4"><strong><?php echo sprintf("%012d", $info['ActivoFijo']['acfi_correlativo']); ?></strong></font>&nbsp;</td>
	</tr>
	<tr>
		<td><strong>Centro de Costo</strong></td>
		<td><?php echo utf8_decode($info['CentroCosto']['ceco_nombre']); ?>&nbsp;</td>
	</tr>
    	<tr>
		<td><strong><?php echo utf8_decode('Ubicacion');?></strong></td>
		<td><?php echo utf8_encode($ubicacion); ?>&nbsp;</td>
	</tr>
    	<?php
    		if (!empty($info['ActivoFijo']['ceco_id_padre'])) {
	?>
    	<tr>
            <td><strong>Centro de Costo Padre</strong></td>
            <td><?php echo utf8_decode($info['CentroCostoPadre']['ceco_nombre']); ?>&nbsp;</td>
        </tr>
        <tr>
            <td><strong><?php echo utf8_decode('Ubicacion Centro Costo Padre');?></strong></td>
            <td><?php echo utf8_encode($ubicacionPadre); ?>&nbsp;</td>
        </tr>
	<?php
		}
	?>
	<tr>
		<td><strong>Proveedor</strong></td>
		<td><?php echo utf8_decode($info['Proveedor']['prov_nombre']); ?>&nbsp;</td>
	</tr>
	<tr>
		<td><strong>Financiamiento</strong></td>
		<td><?php echo utf8_decode($info['Financiamiento']['fina_nombre']); ?>&nbsp;</td>
	</tr>
	<tr>
		<td><strong>Fecha</strong></td>
		<td>
			<?php
				if (trim($info['ActivoFijo']['acfi_fecha']) != "") {
					echo date("d-m-Y", strtotime($info['ActivoFijo']['acfi_fecha']));
				}
			?>
			&nbsp;
		</td>
	</tr>
	<tr>
		<td><strong>Orden de Compra</strong></td>
		<td><?php echo utf8_decode($info['ActivoFijo']['acfi_orden_compra']); ?>&nbsp;</td>
	</tr>
	<tr>
		<td><strong>Tipo de Documento</strong></td>
		<td><?php echo utf8_decode($info['TipoDocumento']['tido_descripcion']); ?>&nbsp;</td>
	</tr>
    <tr>
		<td><strong>N&uacute;mero de Documento</strong></td>
		<td><?php echo utf8_decode($info['ActivoFijo']['acfi_nro_documento']); ?>&nbsp;</td>
	</tr>
    <tr>
		<td><strong>Fecha de Documento</strong></td>
		<td>
			<?php
				if (trim($info['ActivoFijo']['acfi_fecha_documento']) != "") {
					echo date("d-m-Y", strtotime($info['ActivoFijo']['acfi_fecha_documento']));
				}
			?>
			&nbsp;
		</td>
	</tr>
    <tr>
    	<td><strong>Tipo Resoluci&oacute;n</strong></td>
        <td><?php echo utf8_decode($info['TipoResolucion']['tire_nombre']);?></td>
    </tr>
    <tr>
    	<td><strong>N&uacute;mero Resoluci&oacute;n</strong></td>
        <td><?php echo utf8_decode($info['ActivoFijo']['acfi_numero_resolucion']);?></td>
    </tr>
    <tr>
    	<td><strong>Fecha Resoluci&oacute;n</strong></td>
        <td><?php echo utf8_decode($info['ActivoFijo']['acfi_fecha_resolucion']);?></td>
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
<br />
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
                        <td><?php if (isset($deaf['Color']['colo_nombre'])){
								   	 echo utf8_decode($deaf['Color']['colo_nombre']);
								  }
								     echo $deaf['Color']['colo_nombre'] = null;
							?>
                        </td>
                       	<td><?php
                        		if(isset($deaf['Modelo']['mode_nombre'])) {
									echo utf8_decode($deaf['Modelo']['mode_nombre']);
								}   
									echo $deaf['Modelo']['mode_nombre'] = null;                  
							?>                        
                        </td>
                        <td>
                        	<?php
                            	if(isset($deaf['deaf_serie'])) {
									echo $deaf['deaf_serie'];
								}
									echo $deaf['deaf_serie'] = null;
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
							?></td>
					</tr>
				<?php
						$total += $deaf['deaf_precio'];
					}
				?>
</table>
			
<br />

<table width="30%" border="1" id="table_resumen" align="right">
				<tr>
					<th>Total Neto</th>
					<th align="right"><p>$<?php echo number_format($total, 0, ",", "."); ?></p></td>
				</tr>
				<tr>
					<th>IVA (<?php echo $valor_iva;?>%)</th>
					<th id="td_iva" align="right"><p>$<?php echo number_format(round(($total * $valor_iva) / 100), 0, ",", ".");?></p></td>
				</tr>
				<tr>
					<th>Total</th>
					<th id="td_total" align="right">
						<?php
                        				$total_monto = round($total + ($total * $valor_iva / 100));
						?>
						<p>$<?php echo number_format(round($total_monto, 0), 0, ",", "."); ?></p>
					</td>
				</tr>
</table>
<br />
<table width="100%" border="0">
	<tr>
    	<td colspan="2">&nbsp;</td>
    </tr>
    <tr>
    	<td colspan="2">&nbsp;</td>
    </tr>
	<tr>
    	<td width="33%">________________________________</td>
        <td width="33%">________________________________</td>
        <td>________________________________</td>
    </tr>
    <tr>
    	<td width="33%" align="center"><strong>Unidad de Inventario</strong></td>
        <td width="33%" align="center"><strong>Jefe Depto/Unidad</strong></td>
        <td align="center"><strong>Firma de Quien Recibe</strong></td>
    </tr>
</table>
</html>
</body>
