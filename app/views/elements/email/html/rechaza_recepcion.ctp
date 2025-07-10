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
El traslado de existencias n&uacute;mero <strong><?php echo sprintf("%012d", $correlativo); ?></strong>, ha sido <strong>rechazado</strong> por el Centro de Costo <strong><?php echo $ceco_nombre_hijo; ?></strong>.
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
<a href="http://<?php echo $http_host; ?>/existencias/view_traslado/<?php echo $exis_id; ?>">http://<?php echo $http_host; ?>/existencias/view_traslado/<?php echo $exis_id; ?></a>
<br />
<br />
Para editar el traslado, puede seguir el siguiente enlace:
<br />
<br />
<a href="http://<?php echo $http_host; ?>/existencias/edit_traslado/<?php echo $exis_id; ?>">http://<?php echo $http_host; ?>/existencias/edit_traslado/<?php echo $exis_id; ?></a>
<br />
<br />
Atte.
<br />
Sistema de Inventarios