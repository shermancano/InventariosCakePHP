
<table width="500%" border="1">
  <tr>
     <th>ID</th>
     <th>Nombre Adquisici&oacute;n/Contrato</th>
     <th>Nº del Contrato</th>
     <th>Descripci&oacute;n</th>
     <th>Tipo de Renovaci&oacute;n</th>
     <th>Rubro Interno</th>
     <th>Tipo de Contrato</th>
     <th>Unidad de Compra</th>
     <th>Administrador T&eacute;cnico</th>
     <th>Modalidad de Compra</th>
     <th>Resoluci&oacute;n</th>
     <th>Documentos</th>
     <th>Orden de Compra</th>
     <th>Moneda</th>
     <th>Monto de Compra</th>
	 <th>Monto Mensual</th>
     <th>Fecha de Suscripci&oacute;n</th>
     <th>Fecha de Inicio</th>
     <th>Fecha de T&eacute;rmino</th>
	 <th>Observaciones Generales</th>
     <th>Contempla Multas</th>
     <th>Detalle de Multas</th>
     <th>Moneda de la Garantía</th>
     <th>Monto de Garantía</th>
     <th>Número Boleta de Garantía</th>
     <th>Banco</th>
     <th>Fecha de Vencimiento de Garantía</th>
     <th>Evaluación</th>
     <th>Proveedor Adjudicado</th>
     <th>Dirección</th>
     <th>RUT</th>
     <th>Contacto</th>
     <th>Correo Electrónico</th>
     <th>Página Web</th>
     <th>Teléfono</th>
	 <th>Vigente</th>
  </tr>
  <?php
      foreach ($contratos as $contrato) {
  ?>
  <tr>
     <td><?php echo $contrato['Contrato']['cont_id']; ?></td>
     <td><?php echo utf8_decode($contrato['Contrato']['cont_nombre']); ?></td>
     <td><?php echo $contrato['Contrato']['cont_nro_licitacion']; ?></td>
     <td><?php echo utf8_decode($contrato['Contrato']['cont_descripcion']); ?></td>
     <td><?php echo utf8_decode($contrato['TipoRenovacion']['tire_descripcion']); ?></td>
     <td><?php echo utf8_decode($contrato['Rubro']['rubr_descripcion']); ?></td>
     <td><?php echo utf8_decode($contrato['TipoContrato']['tico_descripcion']); ?></td>
     <td><?php echo utf8_decode($contrato['UnidadCompra']['unco_descripcion']); ?></td>
     <td><?php echo utf8_decode($contrato['Contrato']['cont_admin_tecnico']); ?></td>
     <td><?php echo utf8_decode($contrato['ModalidadCompra']['moco_descripcion']); ?></td>
     <td><?php echo utf8_decode($contrato['Contrato']['cont_resolucion']); ?></td>
     <td>
     	<?php
     		if (is_array($contrato['Documento'])) {
     			$docs = array();
     			foreach ($contrato['Documento'] as $doc) {
     				$docs[] = utf8_decode($doc['docu_nombre']);
     			}
     			echo implode(", ", $docs);
     		}
     	?>
     </td>
     <td><?php echo $contrato['Contrato']['cont_orden_compra']; ?></td>
     <td><?php echo $contrato['TipoMonto']['timo_descripcion']; ?></td>
     <td>
		<?php
			if (preg_match("/\./", $contrato['Contrato']['cont_monto_compra'])) {
				echo number_format($contrato['Contrato']['cont_monto_compra'], 3, ",", ".");
			} else {
				echo number_format($contrato['Contrato']['cont_monto_compra'], 0, "", ".");
			}
		?>
	 </td>
	 <td>
		<?php
			if (trim($contrato['Contrato']['cont_monto_mensual']) != "") {
				if (preg_match("/\./", $contrato['Contrato']['cont_monto_mensual'])) {
					echo $contrato['Contrato']['cont_monto_mensual'];
				} else {
					echo number_format($contrato['Contrato']['cont_monto_mensual'], 0, "", ".");
				}
			}
		?>
	 </td>
     <td><?php echo date("d-m-Y", strtotime($contrato['Contrato']['cont_fecha_suscripcion'])); ?></td>
     <td><?php echo date("d-m-Y", strtotime($contrato['Contrato']['cont_fecha_inicio'])); ?></td>
     <td><?php echo date("d-m-Y", strtotime($contrato['Contrato']['cont_fecha_termino'])); ?></td>
     <td>
     	<?php
			echo utf8_decode($contrato['Contrato']['cont_observaciones']);
		?>
     </td>
     <td>
     	<?php
			if ($contrato['Contrato']['cont_multas'] == 1) {
				echo "SI";
			} else {
				echo "NO";
			}
		?>
     </td>
     <td><?php echo $contrato['Contrato']['cont_detalle_multas']; ?></td>
     <td><?php echo utf8_decode($contrato['TipoMontoGarantia']['timo_descripcion']); ?></td>
     <td>
		<?php
			if (trim($contrato['Contrato']['cont_monto_garantia']) != "") {
				if (preg_match("/\./", $contrato['Contrato']['cont_monto_garantia'])) {
					echo $contrato['Contrato']['cont_monto_garantia'];
				} else {
					echo number_format($contrato['Contrato']['cont_monto_garantia'], 0, "", ".");
				}
			}
		?>
	 </td>
     <td><?php echo $contrato['Contrato']['cont_nro_boleta']; ?></td>
     <td><?php echo $contrato['Banco']['banc_nombre']; ?></td>
     <td>
		<?php
			if (trim($contrato['Contrato']['cont_vencimiento_garantia']) != "") {
				echo date("d-m-Y", strtotime($contrato['Contrato']['cont_vencimiento_garantia']));
			}
		?>
	</td>
     <td><?php echo $contrato[0]['nota_final']; ?></td>
     <td><?php echo utf8_decode($contrato['Proveedor']['prov_nombre']); ?></td>
     <td><?php echo utf8_decode($contrato['Proveedor']['prov_direccion']); ?></td>
     <td><?php echo utf8_decode($contrato['Proveedor']['prov_rut']); ?></td>
     <td><?php echo utf8_decode($contrato['Proveedor']['prov_contacto']); ?></td>
     <td><?php echo $contrato['Proveedor']['prov_email']; ?></td>
     <td><?php echo $contrato['Proveedor']['prov_web']; ?></td>
     <td><?php echo $contrato['Proveedor']['prov_telefono']; ?></td>
	 <td>
		<?php
			if ($contrato['Contrato']['cont_vigente'] == "" || $contrato['Contrato']['cont_vigente'] == 1) {
				echo "SI";
			} else {
				echo "NO";
			}
		?>
	 </td>
  </tr>
  <?php
  	  }
  ?>
</table>
