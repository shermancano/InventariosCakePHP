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
		<td align="center" width="100%"><font size="5"><strong>EXCLUSI&Oacute;N DE INVENTARIO</strong></font></td>
	</tr>
</table>
<table border="1" width="100%">
	<tr>
		<td width="30%"><strong>Correlativo</strong></td>
		<td width="70%"><font size="4"><strong><?php echo sprintf("%012d", $info['ExclusionActivoFijo']['exaf_correlativo']); ?></strong></font>&nbsp;</td>
	</tr>
	<tr>
		<td><strong>Centro de Costo</strong></td>
		<td><?php echo utf8_decode($info['CentroCosto']['ceco_nombre']); ?>&nbsp;</td>
	</tr>
    <tr>
		<td><strong>Fecha</strong></td>
		<td><?php echo date('d-m-Y H:i:s', strtotime($info['ExclusionActivoFijo']['exaf_fecha'])); ?>&nbsp;</td>
	</tr>
    <tr>
		<td><strong>N&uacute;mero de Resoluci&oacute;n</strong></td>
		<td><?php echo $info['ExclusionActivoFijo']['exaf_numero_documento']; ?>&nbsp;</td>
	</tr>
    <tr>
		<td><strong>Observaci&oacute;n</strong></td>
		<td><?php echo $info['ExclusionActivoFijo']['exaf_observacion']; ?>&nbsp;</td>
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
        foreach ($infoDetalleExclusion as $deex) {
    ?>
        <tr>
            <td><?php echo $deex['DetalleExclusion']['dete_codigo']; ?></td>
            <td><?php echo utf8_decode($deex['Producto']['prod_nombre']); ?></td>                        
            <td align="right">$<?php echo number_format($deex['DetalleExclusion']['dete_valor_baja'], 0, ',', '.'); ?></td>
        </tr>
    <?php
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