<style type="text/css">
.tabla_contenido {
	font-size:10px;
	border-collapse: collapse;
	border-width: 1px;
	border-color:#000;
}
</style>
<html>
	<title>&nbsp;</title>
    	<meta charset="UTF-8">
<body>
<table border="0" width="100%" height="30%">
	<tr>
		<td width="20%" valign="top" align="left" rowspan="4"><img src="http://<?php echo $_SERVER['HTTP_HOST']?>/files/logo.png" /></td>
        <td>&nbsp;</td>
        <td>&nbsp;</td>
	</tr>
    <tr>
    	<td>&nbsp;</td>
        <td>&nbsp;</td>
    </tr>
    <tr>
    	<td width="60%" valign="bottom" align="center"><font size="5"><strong>BIENES POR UBICACION</strong></font></td>
        <td width="20%" valign="bottom" align="right"><?php echo date("d-m-Y H:i:s");?></td>
    </tr>
    <tr>
    	<td>&nbsp;</td>
        <td>&nbsp;</td>
    </tr>
</table>
<br />
<?php 
	$nombres = array();
	
	foreach ($responsables as $row) {
		$nombres[] = utf8_decode($row['Usuario']['usua_nombre']);
	}
	
?>
<table border="1" width="100%" cellpadding="2" cellspacing="2" bordercolor="#000000" style="border-collapse:collapse; font-size:12px;">
    <tr>
        <td width="20%"><strong>Centro de Costo/Educaci&oacute;n</strong></td>
    	<td width="80%"><?php echo utf8_decode($info_cc['CentroCosto']['ceco_nombre']); ?></td>
    </tr>
    <tr>
    	<td width="20%"><strong>Ubicaci&oacute;n</strong></td>
    	<td width="80%"><?php echo utf8_decode($ubicacion);?></td>
    </tr>
</table>
<br />
<table border="1" width="100%" cellpadding="2" cellspacing="2" class="tabla_contenido">
	<tr valign="top">
    	<td align="center"><strong>C&oacute;digo</strong></td>
        <td align="center"><strong>Descripci&oacute;n</strong></td>
        <td align="center"><strong>Propiedad</strong></td>
        <td align="center"><strong>Situaci&oacute;n</strong></td>
		<!--<td align="center"><strong>Marca</strong></td>-->
        <td align="center"><strong>Color</strong></td>
        <td align="center"><strong>Vida &Uacute;til</strong></td>
        <td align="center"><strong>Fecha Adquisici&oacute;n</strong></td>
        <td align="center"><strong>Financiamiento</strong></td>
    </tr>
    <?php
    	foreach ($activos_fijos as $ubac) {
		$ubac = array_pop($ubac);
	?> 
    <tr valign="top"> 
    	<td><?php echo $ubac['ubaf_codigo']; ?>&nbsp;</td>   
        <td><?php echo utf8_decode($ubac['prod_nombre']); ?>&nbsp;</td>   
        <td><?php echo utf8_decode($ubac['prop_nombre']); ?>&nbsp;</td>   
        <td><?php echo utf8_decode($ubac['situ_nombre']); ?>&nbsp;</td>   
        <!--<td><?php echo utf8_decode($ubac['marc_nombre']); ?>&nbsp;</td>-->
        <td><?php echo utf8_decode($ubac['colo_nombre']); ?>&nbsp;</td>
        <td><?php echo $ubac['ubaf_vida_util'];	?>&nbsp;</td> 
        <td><?php echo date("d-m-Y", strtotime($ubac['ubaf_fecha_adquisicion'])); ?>&nbsp;</td>              	
        <td><?php echo utf8_decode($ubac['fina_nombre']);?>&nbsp;</td>
    </tr>
    <?php
		}
	?>
</table>
<table border="0" width="100%">
	<tr>
   		<td>&nbsp;</td>
    </tr>
    <tr>
   		<td>&nbsp;</td>
    </tr>
</table>
<table border="0" width="100%">
    <tr>
    	<td align="center"><?php echo utf8_decode(substr($buscaEncargadoDependencia, 0, strlen($buscaEncargadoDependencia) - 1)); ?></td>
        <td align="center"><?php echo utf8_decode(substr($buscaEncargadoInventario, 0, strlen($buscaEncargadoInventario) - 1)); ?></td>
    </tr>
    <tr>
    	<td align="center">______________________________________</td>
        <td align="center">______________________________________</td>
    </tr>
    <tr>
    	<td align="center"><strong>Encargado de Dependencia</strong></td>
        <td align="center"><strong>Encargado de Inventarios</strong></td>
    </tr>
</table>
<br><br><br>
<table border="0" width="100%">
    <tr>
    	<td width="25%"></td>
        <td align="center"><?php echo utf8_decode(substr($buscaEncargadoEstablecimiento, 0, strlen($buscaEncargadoEstablecimiento) - 1)); ?></td>
        <td width="25%"></td>
    </tr>
    <tr>
    	<td width="25%"></td>
        <td align="center">______________________________________</td>
        <td width="25%"></td>
    </tr>
    <tr>
    	<td width="25%"></td>
        <td align="center"><strong>Director de Establecimiento</strong></td>
        <td width="25%"></td>
    </tr>
</table>     
</body>
</html>
