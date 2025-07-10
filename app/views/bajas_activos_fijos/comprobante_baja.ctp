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
		<td align="center" width="100%"><font size="5"><strong>BAJA DE INVENTARIO</strong></font></td>
	</tr>
</table>
<table border="1" width="100%">
	<tr>
		<td width="30%"><strong>Correlativo</strong></td>
		<td width="70%"><font size="4"><strong><?php echo sprintf("%012d", $info['BajaActivoFijo']['baaf_correlativo']); ?></strong></font>&nbsp;</td>
	</tr>
	<tr>
		<td><strong>Centro de Costo</strong></td>
		<td><?php echo utf8_decode($info['CentroCosto']['ceco_nombre']); ?>&nbsp;</td>
	</tr>
    <tr>
		<td><strong>Fecha</strong></td>
		<td><?php echo date('d-m-Y H:i:s', strtotime($info['BajaActivoFijo']['baaf_fecha'])); ?>&nbsp;</td>
	</tr>
    <tr>
		<td><strong>N&uacute;mero de Resoluci&oacute;n</strong></td>
		<td><?php echo $info['BajaActivoFijo']['baaf_numero_documento']; ?>&nbsp;</td>
	</tr>
    <tr>
		<td><strong>Observaci&oacute;n</strong></td>
		<td><?php echo $info['BajaActivoFijo']['baaf_observacion']; ?>&nbsp;</td>
	</tr>
    <tr>
		<td><strong>Tipo de Baja</strong></td>
		<td><?php echo utf8_decode($info['DependenciaVirtual']['devi_nombre']); ?>&nbsp;</td>
	</tr>
    <tr>
		<td><strong>Motivo de Baja</strong></td>
		<td><?php echo utf8_decode($info['MotivoBaja']['moba_nombre']); ?>&nbsp;</td>
	</tr>
</table>
<br />
<table width="100%" border="1">
    <tr>
        <th>C&oacute;digo</th>
        <th>Producto</th>                    
        <th>Precio Unitario</th>
    </tr>
    <?php
        $total = 0;
        foreach ($info_deba as $deba) {
    ?>
        <tr>
            <td><?php echo $deba['DetalleBaja']['deba_codigo']; ?></td>
            <td><?php echo utf8_decode($deba['Producto']['prod_nombre']); ?></td>                        
            <td align="right">$<?php echo number_format($deba['DetalleBaja']['deba_valor_baja'], 0, ',', '.'); ?></td>
        </tr>
    <?php
            $total += $deba['DetalleBaja']['deba_valor_baja'];
        }
    ?>
</table>
			
<br />
<br />
<table width="100%" border="0">
	<tr>
    	<td colspan="2">&nbsp;</td>
    </tr>
    <tr>
    	<td colspan="2">&nbsp;</td>
    </tr>
	<tr>
    	<td width="33%" align="center">________________________________</td>
        <td align="center">________________________________</td>        
    </tr>
    <tr>
    	<td width="33%" align="center"><strong>Unidad de Inventario</strong></td>
        <td align="center"><strong>Jefe Depto/Unidad</strong></td>        
    </tr>
</table>
</html>
</body>