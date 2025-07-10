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
		<td align="center" width="100%"><font size="5"><strong>STOCK GENERAL ACTIVOS FIJOS <?php echo $ceco_nombre_; ?></strong></font></td>
	</tr>
</table>
<table border="1" width="100%">
	<tr>
        <td align="center"><strong>Descripci&oacute;n</strong></td>
        <td align="center"><strong>Stock Total</strong></td>
    </tr>
    <?php
    	foreach ($info as $row) {
		$row = array_pop($row);
	?> 
    <tr> 
        <td><?php echo utf8_decode($row['prod_nombre']); ?>&nbsp;</td>   
        <td align="center"><?php echo $row['total']; ?>&nbsp;</td>   
    </tr>
    <?php
		}
	?>
</table>
<table border="0" width="100%">
    <tr>
    	<td>&nbsp;</td>
        <td>&nbsp;</td>
    </tr>
    <tr>
    	<td>&nbsp;</td>
        <td>&nbsp;</td>
    </tr>
	<tr>
    	<td align="center" width="50%"></td>
        <td align="center" width="50%">______________________________________</td>
    </tr>
    <tr>
    	<td align="center" width="50%"></td>
        <td align="center" width="50%"><strong>Encargado de Inventarios</strong></td>
    </tr>
</table>     
</body>
</html>