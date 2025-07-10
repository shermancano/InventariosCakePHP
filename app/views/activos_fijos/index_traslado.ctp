<div class="traslado index">
	<h2><?php __('Traslados Activos Fijos');?></h2>
	<table cellpadding="0" cellspacing="0">
	<tr>
			<th width="4%">&nbsp;</th>
			<th><?php echo $this->Paginator->sort('Código', 'ActivoFijo.acfi_correlativo');?></th>
			<th><?php echo $this->Paginator->sort('Desde', 'CentroCostoPadre.ceco_nombre');?></th>
			<th><?php echo $this->Paginator->sort('Hacia', 'CentroCostoHijo.ceco_nombre');?></th>
			<th><?php echo $this->Paginator->sort('Fecha', 'ActivoFijo.acti_fecha');?></th>
			<th class="actions"><?php __('Acciones');?></th>
	</tr>
    <?php
	$i = 0;
	foreach ($activos_fijos as $tras):
		$class = null;
		if ($i++ % 2 == 0) {
			$class = ' class="altrow"';
		}
	?>
	<tr<?php echo $class;?>>
		<td>
			<?php
				if ($tras['ActivoFijo']['esre_id'] == 2) {
			?>
				<img src="/img/test-fail-icon.png" border="0" title="Este traslado a&uacute;n no ha sido recepcionado por <?php echo $tras['CentroCostoHijo']['ceco_nombre']; ?>" alt="0" />				
			<?php
				} else {
			?>
				<img src="/img/test-pass-icon.png" border="0" title="Traslado aceptado" alt="0" />
			<?php
				}
			?>
			&nbsp;
		</td>
		<td><?php echo sprintf("%012d", $tras['ActivoFijo']['acfi_correlativo']); ?>&nbsp;</td>
		<td><?php echo $tras['CentroCosto']['ceco_nombre']; ?>&nbsp;</td>
		<td><?php echo $tras['CentroCostoHijo']['ceco_nombre']; ?>&nbsp;</td>
		<td><?php echo date("d-m-Y H:i:s", strtotime($tras['ActivoFijo']['acfi_fecha'])); ?>&nbsp;</td>
		<td class="actions">
			<?php echo $this->Html->link(__('Ver', true), array('action' => 'view_traslado', $tras['ActivoFijo']['acfi_id']), null, null); ?>
			<?php
				//echo $this->Html->link(__('Editar', true), array('action' => 'edit_traslado', $tras['ActivoFijo']['acfi_id']), null, null, true);
			?>
			<?php echo $this->Html->link(__('Eliminar', true), array('action' => 'delete_traslado', $tras['ActivoFijo']['acfi_id']), array('title' => 'Eliminar toda la entrada'), __('La accion eliminara el traslado seleccionado, esta seguro que desea continuar?', true)); ?>
			<?php echo $this->Html->link(__('Comprobante', true), array('action' => 'comprobante_traslado', $tras['ActivoFijo']['acfi_id']), null, null); ?>
		</td>
	</tr>
<?php endforeach; ?>
    </table>
    <p>
	<?php
	echo $this->Paginator->counter(array(
	'format' => __(('Página %page% de %pages%, mostrando %current% registros de un total de %count% total, empezando en %start%, terminando en %end%'), true)
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
		<tr>
			<td width="3%"><img src="/img/test-fail-icon.png" border="0" alt="0" /></td>
			<td>Traslados no recepcionados.</td>
		</tr>
		<tr>
			<td width="3%"><img src="/img/test-pass-icon.png" border="0" alt="0" /></td>
			<td>Traslados recepcionados.</td>
		</tr>
	</table>
</div>
<?php
	include("views/sidebars/menu.php");
?>