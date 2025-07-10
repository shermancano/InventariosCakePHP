<div class="depreciaciones index">
	<h2><?php __('Depreciaciones');?></h2>
	<table cellpadding="0" cellspacing="0">
	<tr>
			<th><?php echo $this->Paginator->sort(utf8_encode('Año'), 'depr_ano');?></th>
			<th><?php echo $this->Paginator->sort('IPC', 'depr_ipc');?></th>
			<th><?php echo $this->Paginator->sort('Fecha', 'depr_fecha');?></th>			
			<th class="actions"><?php __('Acciones');?></th>
	</tr>
	<?php
	$i = 0;
	foreach ($depreciaciones as $depr):
		$class = null;
		if ($i++ % 2 == 0) {
			$class = ' class="altrow"';
		}
	?>
	<tr<?php echo $class;?>>
		<td><?php echo $depr['Depreciacion']['depr_ano']; ?>&nbsp;</td>
		<td><?php echo $depr['Depreciacion']['depr_ipc']; ?>&nbsp;</td>
		<td><?php echo date("d-m-Y H:i:s", strtotime($depr['Depreciacion']['depr_fecha'])); ?>&nbsp;</td>		
		<td class="actions">
			<?php
				echo $this->Html->link(__('Ver Excel', true), array('action' => 'excel', $depr['Depreciacion']['depr_id']), null, null);
				echo $this->Html->link(__('Eliminar', true), array('action' => 'delete', $depr['Depreciacion']['depr_id']), null, sprintf(__('La accion eliminara la depreciacion seleccionada y posteriormente actualizara los valores anteriores, esta seguro que desea continuar?', true), $depr['Depreciacion']['depr_id']));
			?>
		</td>
	</tr>
<?php endforeach; ?>
	</table>
	<p>
	<?php
	echo $this->Paginator->counter(array(
	'format' => __(utf8_encode('Página %page% de %pages%, mostrando %current% registros de un total de %count% total, empezando en %start%, terminando en %end%'), true)
	));
	?>	</p>

	<div class="paging">
		<?php echo $this->Paginator->prev('<< ' . __('anterior', true), array(), null, array('class'=>'disabled'));?>
	 | 	<?php echo $this->Paginator->numbers();?>
 |
		<?php echo $this->Paginator->next(__('siguiente', true) . ' >>', array(), null, array('class' => 'disabled'));?>
	</div>
</div>

<?php
	include("views/sidebars/menu.php");
?>