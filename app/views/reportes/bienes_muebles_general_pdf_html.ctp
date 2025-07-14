<style type="text/css">
    
</style>
<html>
	<title>&nbsp;</title>
    <meta charset="UTF-8">
<body>
<br />
<table width="100%">
    <tr>
        <td align="center" width="100%" colspan="3"><font size="2"><strong>Inventario de Bienes Muebles</strong><font></td>
    </tr>
    <tr>
        <td align="left" width="20%"><font size="2">Comuna: <?php echo utf8_decode($infoCentroCosto['Comuna']['comu_nombre']);?><font></td>
        <td align="center" width="60%"><font size="2">Establecimiento Educacional: <?php echo utf8_decode($infoCentroCosto['CentroCosto']['ceco_nombre']);?><font></td>
        <td align="right" width="20%"><font size="2">RBD-DV/C&oacute;digo JUNJI: <?php echo $infoCentroCosto['CentroCosto']['ceco_rut'];?><font></td>
    </tr>
</table>
<br />
<table border="1" width="100%" cellpadding="2" cellspacing="2" style="border: 1px solid black; border-collapse: collapse;">
    <tr style="background-color: #E5E4E2;">
        <td align="center" width="9%"><font size="1"><strong>Nivel Educativo</strong></font></td>
        <td align="center" width="33%"><font size="1"><strong>Individualizaci&oacute;n del bien</strong></font></td>
        <td align="center" width="5%"><font size="1"><strong>Cantidad</strong></font></td>
        <td align="center" width="6%"><font size="1"><strong>Estado de conservaci&oacute;n</strong></font></td>
        <td align="center" width="17%"><font size="1"><strong>Lugar f&iacute;sico</strong></font></td>
        <td align="center" width="10%"><font size="1"><strong>Procedencia: Inversi&oacute;n o donaci&oacute;n</strong></font></td>
        <td align="center" width="10%"><font size="1"><strong>Procedencia: Donador o fondo de adquisici&oacute;n</strong></font></td>
        <td align="center" width="10%"><font size="1"><strong>Procedencia: Fecha de adquisici&oacute;n</strong></font></td>
    </tr>
<?php
    foreach ($info as $row) {
        $row = array_pop($row);
?>
    <?php
        $fechaAdquisición = (!empty($row['ubaf_fecha_adquisicion'])) ? date("d-m-Y", strtotime($row['ubaf_fecha_adquisicion'])) : utf8_decode("Sin información");
        $observaciones = (!empty($row['acfi_observaciones'])) ? utf8_decode($row['acfi_observaciones']) : utf8_decode("Sin información");
        $financiamiento = (!empty($row['fina_nombre'])) ? utf8_decode($row['fina_nombre']) : utf8_decode("Sin información");
    ?>
    <tr>
        <td align="center"><font size="1"><?php echo utf8_decode($row['nied_nombre']); ?></font></td>
        <td><font size="1"><?php echo utf8_decode($row['prod_nombre']); ?></font></td>
        <td align="center"><font size="1"><?php echo $row['total']; ?></font></td>
        <td align="center"><font size="1"><?php echo utf8_decode($row['situ_nombre']); ?></font></td>
        <td><font size="1"><?php echo utf8_decode($row['ceco_nombre']); ?></font></td>
        <td><font size="1"><?php echo $observaciones; ?></font></td>
        <td align="center"><font size="1"><?php echo $financiamiento; ?></font></td>
        <td align="center"><font size="1"><?php echo $fechaAdquisición; ?></font></td>
    </tr>
<?php
	}
?>
</table>
<br><br>
<table style="page-break-before: always; break-before: always; border: 1px solid black;" width="100%" cellpadding="2" cellspacing="2">
    <tbody>
        <tr>
            <td><font size="2"><br>
                Yo Director(a) Sr./ Sra./Srta. _____________________________, del Establecimiento Educacional ___________________________________, RBD/C&oacute;digo JUNJI _______________,<br>
                con fecha _____ de ____________ de 20____, doy fe que la informaci&oacute;n individualizada corresponde a los Bienes Muebles afectos a la prestaci&oacute;n del servicio educacional del<br>
                Establecimiento que dirijo con su actual estado de conservaci&oacute;n.<br><br>
            </td>
        </tr>
        <tr>
            <td align="right">_____________________________________________________________</td>
        </tr>
        <tr>
            <td align="right"><font size="2"><strong>FIRMA Y TIMBRE DIRECTOR DEL ESTABLECIMIENTO</strong>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</font><br><br></td>
        </tr>
    </tbody>
</table>
</body>
</html>
