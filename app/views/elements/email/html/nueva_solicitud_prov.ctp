<?php
	if (trim($logo) != "") {
?>
		<img src="data:image/png;base64,<?php echo $logo; ?>">
		<br />
		<br />
<?php
	}
?>
Estimado(a) Sr(a). <strong><?php echo $prov_contacto; ?></strong> (contacto proveedor <strong><?php echo $prov_nombre; ?></strong>):
<br />
<br />
Tiene pendiente una nueva solicitud (n&uacute;mero <strong><?php echo sprintf("%012d", $correlativo); ?></strong>) desde <strong><?php echo $site_title; ?>/<?php echo $ceco_nombre_padre; ?></strong>.
<br />
<br />
Adjuntamos el comprobante de solicitud.
<br />
<br />
Atte.
<br />
Sistema de Inventarios