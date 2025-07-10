<div class="solicitudes index">
	<h2><?php __('Solicitudes');?></h2>
	<table cellpadding="0" cellspacing="0">
	<tr>
			<th>&nbsp;</th>
			<th><?php echo $this->Paginator->sort('Código', 'Solicitud.soli_correlativo');?></th>
			<th><?php echo $this->Paginator->sort('Desde', 'CentroCosto.ceco_nombre');?></th>
            <th><?php echo $this->Paginator->sort('Hacia', 'CentroCosto2.ceco_nombre');?></th>
			<th><?php echo $this->Paginator->sort('Proveedor', 'Proveedor.prov_nombre');?></th>
            <th><?php echo $this->Paginator->sort('Fecha', 'Solicitud.soli_fecha');?></th>
            <th><?php echo $this->Paginator->sort('Tipo Solicitud', 'TipoSolicitud.tiso_nombre');?></th>
			<th class="actions"><?php __('Acciones');?></th>
	</tr>
	<?php
	$i = 0;
	foreach ($solicitudes as $soli):
		$class = null;
		if ($i++ % 2 == 0) {
			$class = ' class="altrow"';
		}
	?>
	<tr<?php echo $class;?>>
		<td>
			<?php
				if ($soli['EstadoSolicitud']['esso_id'] == 1) {
					echo "<img border=\"0\" alt=\"0\" title=\"Solicitud recepcionada\" src=\"/img/test-pass-icon.png\" />";
				} else {
					if (trim($soli['Solicitud']['prov_id']) != "") {
						echo "<img border=\"0\" alt=\"0\" title=\"Solicitud no recepcionada por proveedor ".$soli['Proveedor']['prov_nombre']."\" src=\"/img/test-fail-icon.png\" />";
					} else {
						echo "<img border=\"0\" alt=\"0\" title=\"Solicitud no recepcionada por Centro de Costo ".$soli['CentroCosto2']['ceco_nombre']."\" src=\"/img/test-fail-icon.png\" />";
					}
				}
			?>
			&nbsp;
		</td>
		<td><?php echo sprintf("%012d", $soli['Solicitud']['soli_correlativo']); ?>&nbsp;</td>
		<td><?php echo $soli['CentroCosto']['ceco_nombre']; ?>&nbsp;</td>
        <td><?php echo $soli['CentroCosto2']['ceco_nombre'];?>&nbsp;</td>
		<td><?php echo $soli['Proveedor']['prov_nombre'];?></td>
        <td><?php echo date("d-m-Y H:i:s", strtotime($soli['Solicitud']['soli_fecha']));?></td>
        <td><?php echo $soli['TipoSolicitud']['tiso_nombre'];?></td>
		<td class="actions">
			<?php
				echo $this->Html->link(__('Ver', true), array('action' => 'view', $soli['Solicitud']['soli_id']));
				echo $this->Html->link(__('Comprobante', true), array('action' => 'comprobante', $soli['Solicitud']['soli_id']));
				echo $this->Html->link(__('Eliminar', true), array('action' => 'delete', $soli['Solicitud']['soli_id']), null, sprintf(__('La accion eliminara la solicitud, esta seguro que desea continuar?', true), $soli['Solicitud']['soli_id']));

			?>
		</td>
	</tr>
<?php endforeach; ?>
	</table>
	<p>
	<?php
	echo $this->Paginator->counter(array(
	'format' => __('Página %page% de %pages%, mostrando %current% registros de un total de %count% total, empezando en %start%, terminando en %end%', true)
	));
	?>	</p>

	<div class="paging">
		<?php echo $this->Paginator->prev('<< ' . __('anterior', true), array(), null, array('class'=>'disabled'));?>
	 | 	<?php echo $this->Paginator->numbers();?>
 |
		<?php echo $this->Paginator->next(__('siguiente', true) . ' >>', array(), null, array('class' => 'disabled'));?>
	</div>
	
	<br />
	<br />
	<table id="avisos">
		<tbody><tr>
			<td width="3%"><img border="0" alt="0" src="/img/test-fail-icon.png"></td>
			<td>Solicitudes no recepcionadas.</td>
		</tr>
		<tr>
			<td width="3%"><img border="0" alt="0" src="/img/test-pass-icon.png"></td>
			<td>Solicitudes recepcionadas.</td>
		</tr>
	</tbody></table>
</div>

<?php
	include("views/sidebars/menu.php");
?>