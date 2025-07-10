<html> 
 <head>
   <title></title>
 </head>
<body>

<?php
	$meses = array("Enero", "Febrero", "Marzo", "Abril", "Mayo", "Junio", "Julio", "Agosto", "Septiembre", "Octubre", "Noviembre", "Diciembre");
?>

<table border="1" width="100%">
  <tr>
    <th colspan="2">ANTECEDENTES DEL CONTRATO</th>
  </tr>
  <tr>
    <td>Nombre de Adquisición/Contrato</td>
    <td><?php echo utf8_decode($info['Contrato']['cont_nombre']); ?>&nbsp;</td>
  </tr>
  <tr>
    <td>Número del Contrato</td>
    <td><?php echo $info['Contrato']['cont_nro_licitacion']; ?>&nbsp;</td>
  </tr>
  <tr>
    <td>Descripción del Contrato</td>
    <td><?php echo utf8_decode($info['Contrato']['cont_descripcion']); ?>&nbsp;</td>
  </tr>
  <tr>
    <td>Tipo de Renovación</td>
    <td><?php echo utf8_decode($info['TipoRenovacion']['tire_descripcion']); ?>&nbsp;</td>
  </tr>
  <tr>
    <td>Rubro Interno de Compras</td>
    <td><?php echo utf8_decode($info['Rubro']['rubr_descripcion']); ?>&nbsp;</td>
  </tr>
  <tr>
    <td>Tipo de Contrato</td>
    <td><?php echo $info['TipoContrato']['tico_descripcion']; ?>&nbsp;</td>
  </tr>
  <tr>
    <td>Unidad de Compra</td>
    <td><?php echo utf8_decode($info['UnidadCompra']['unco_descripcion']); ?>&nbsp;</td>
  </tr>
  <tr>
    <td>Administrador Técnico</td>
    <td><?php echo utf8_decode($info['Contrato']['cont_admin_tecnico']); ?>&nbsp;</td>
  </tr>
  <tr>
    <td>Modalidad de Compra</td>
    <td><?php echo utf8_decode($info['ModalidadCompra']['moco_descripcion']); ?>&nbsp;</td>
  </tr>
  <tr>
    <td>Resolución</td>
    <td><?php echo utf8_decode($info['Contrato']['cont_resolucion']); ?>&nbsp;</td>
  </tr>
  <tr>
    <td>Documentos</td>
    <td>
    	<?php
			if (sizeof($info['Documento']) > 0) {
				echo "</ul>";
				foreach ($info['Documento'] as $doc) {
					echo "<li>".utf8_decode($doc['docu_nombre'])."</li>";
				}
				echo "<ul>";
			}
    	?>&nbsp;
    </td>
  </tr>
  <tr>
    <td>Orden de Compra</td>
    <td><?php echo utf8_decode($info['Contrato']['cont_orden_compra']); ?>&nbsp;</td>
  </tr>
  <tr>
    <td>Moneda</td>
    <td><?php echo utf8_decode($info['TipoMonto']['timo_descripcion']); ?>&nbsp;</td>
  </tr>
  <tr>
    <td>Monto Compra</td>
    <td>
		<?php
			if (preg_match("/\./", $info['Contrato']['cont_monto_compra'])) {
				echo number_format($info['Contrato']['cont_monto_compra'], 3, ",", ".");
			} else {
				echo number_format($info['Contrato']['cont_monto_compra'], 0, "", ".");
			}
		?>
		&nbsp;
	</td>
  </tr>
  <tr>
    <td>Monto Mensual</td>
    <td>
		<?php
			if (trim($info['Contrato']['cont_monto_mensual']) != "") {
				if (preg_match("/\./", $info['Contrato']['cont_monto_mensual'])) {
					echo $info['Contrato']['cont_monto_mensual'];
				} else {
					echo number_format($info['Contrato']['cont_monto_mensual'], 0, "", ".");
				}
			}
		?>
		&nbsp;
	</td>
  </tr>
  <tr>
    <td>Fecha de Suscripción del Contrato</td>
    <td>
    <?php
    	$suscrip = $info['Contrato']['cont_fecha_suscripcion'];
    	echo date("d", strtotime($suscrip))." de ".$meses[date("n", strtotime($suscrip))-1]." del ".date("Y", strtotime($suscrip));
    ?>
    &nbsp;
    </td>
  </tr>
  <tr>
    <td>Fecha de Inicio del Contrato</td>
    <td>
    <?php
    	$inicio = $info['Contrato']['cont_fecha_inicio'];
    	echo date("d", strtotime($inicio))." de ".$meses[date("n", strtotime($inicio))-1]." del ".date("Y", strtotime($inicio));
    ?>
    &nbsp;
    </td>
  </tr>
  <tr>
    <td>Fecha término del Contrato</td>
    <td>
    <?php
    	$termino = $info['Contrato']['cont_fecha_termino'];
    	echo date("d", strtotime($termino))." de ".$meses[date("n", strtotime($termino))-1]." del ".date("Y", strtotime($termino));
    ?>
    &nbsp;
    </td>
  </tr>
  <tr>
    <td>Observaciones Generales</td>
    <td>
    <?php
    	echo utf8_decode($info['Contrato']['cont_observaciones']);
    ?>
    &nbsp;
    </td>
  </tr>
  <tr>
    <td>Contempla Multas</td>
    <td>
    	<?php
			if ($info['Contrato']['cont_multas'] == 1) {
				echo "SI";
			} else {
				echo "NO";
			}
		?>
	&nbsp;
	</td>	
  </tr>
  <tr>
    <td>Detalle de Multas</td>
    <td><?php echo $info['Contrato']['cont_detalle_multas']; ?>&nbsp;</td>
  </tr>
  <tr>
    <td>Moneda de la Garantía</td>
    <td><?php echo $info['TipoMontoGarantia']['timo_descripcion']; ?>&nbsp;</td>
  </tr>
  <tr>
    <td>Monto de la Garantía</td>
    <td>
		<?php
			if (trim($info['Contrato']['cont_monto_garantia']) != "") {
				if (preg_match("/\./", $info['Contrato']['cont_monto_garantia'])) {
					echo $info['Contrato']['cont_monto_garantia'];
				} else {
					echo number_format($info['Contrato']['cont_monto_garantia'], 0, "", ".");
				}
			}
		?>
		&nbsp;
	</td>
  </tr>
  <tr>
    <td>Número Boleta Garantía</td>
    <td><?php echo $info['Contrato']['cont_nro_boleta']; ?>&nbsp;</td>
  </tr>
  <tr>
    <td>Banco</td>
    <td><?php echo $info['Banco']['banc_nombre']; ?>&nbsp;</td>
  </tr>
  <tr>
    <td>Fecha de Vencimiento de la Garantía</td>
    <td>
		<?php
			if (trim($info['Contrato']['cont_vencimiento_garantia']) != "") {
				$venc = $info['Contrato']['cont_vencimiento_garantia'];
				echo date("d", strtotime($venc))." de ".$meses[date("n", strtotime($venc))-1]." del ".date("Y", strtotime($venc));
			}
		?>
		&nbsp;
  </td>
  </tr>
  <tr>
    <td>Evaluación</td>
    <td><?php echo $nota_final; ?>&nbsp;</td>
  </tr>
  <tr>
    <td>Vigente</td>
    <td>
		<?php
			if ($info['Contrato']['cont_vigente'] == "" || $info['Contrato']['cont_vigente'] == 1) {
				echo "SI";
			} else {
				echo "NO";
			}
		?>
		&nbsp;
	</td>
  </tr>
</table>

<br />

<table border="1" width="100%">
  <tr>
    <th colspan="2">ANTECEDENTES DEL PROVEEDOR</th>
  </tr>
  <tr>
    <td>Proveedor Adjudicado</td>
    <td><?php echo utf8_decode($info['Proveedor']['prov_nombre']); ?></td>
  </tr>
  <tr>
    <td>Dirección</td>
    <td><?php echo utf8_decode($info['Proveedor']['prov_direccion']); ?></td>
  </tr>
  <tr>
    <td>Rol único Tributario (RUT)</td>
    <td><?php echo $info['Proveedor']['prov_rut']; ?></td>
  </tr>
  <tr>
    <td>Contacto</td>
    <td><?php echo utf8_decode($info['Proveedor']['prov_contacto']); ?></td>
  </tr>
  <tr>
    <td>Correo electrónico</td>
    <td><?php echo $info['Proveedor']['prov_email']; ?></td>
  </tr>
  <tr>
    <td>Página Web</td>
    <td><?php echo $info['Proveedor']['prov_web']; ?></td>
  </tr>
  <tr>
    <td>Teléfono</td>
    <td><?php echo $info['Proveedor']['prov_telefono']; ?></td>
  </tr>
</table>

</body>
</html>
