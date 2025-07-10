<?php
	if (trim($logo) != "") {
?>
		<img src="data:image/png;base64,<?php echo $logo; ?>">
		<br />
		<br />
<?php
	}
?>
Estimado encargado centro de costo/salud <strong><?php echo $ceco_nombre_padre; ?></strong>:
<br />
<br />
La solicitud n&uacute;mero <strong><?php echo sprintf("%012d", $correlativo); ?></strong>, ha sido <strong>rechazada</strong> por el <?php if ($prov_nombre != "") { echo "proveedor <strong>".$prov_nombre."</strong>"; } else { echo "Centro de Costo <strong>".$ceco_nombre_hijo."</strong>"; }?>.
<br />
<br />
El motivo del rechazo fue el siguiente:
<br />
<br />
<strong>"<?php echo $motivo; ?>"</strong>
<br />
<br />
Para ver el detalle de la operaci&oacute;n, puede seguir el siguiente enlace:
<br />
<br />
<a href="http://<?php echo $http_host; ?>/solicitudes/view/<?php echo $soli_id; ?>">http://<?php echo $http_host; ?>/solicitudes/view/<?php echo $soli_id; ?></a>
<br />
<br />
Para editar el traslado, puede seguir el siguiente enlace:
<br />
<br />
Atte.
<br />
Sistema de Inventarios