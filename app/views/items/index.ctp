<div class="items index">
	<h2><?php __(utf8_encode('Items de Evaluación'));?></h2>
	<table cellpadding="0" cellspacing="0">
	<tr>
			<th><?php echo $this->Paginator->sort('Tipo de Item', 'tiit_descripcion');?></th>
			<th><?php echo $this->Paginator->sort(utf8_encode('Descripción'), 'item_descripcion');?></th>
			<th class="actions"><?php __('Acciones');?></th>
	</tr>
	<?php
	$i = 0;
	foreach ($items as $item):
		$class = null;
		if ($i++ % 2 == 0) {
			$class = ' class="altrow"';
		}
	?>
	<tr<?php echo $class;?>>
		<td><?php echo $item['TipoItem']['tiit_descripcion']; ?>&nbsp;</td>
		<td><?php echo $item['Item']['item_descripcion']; ?>&nbsp;</td>
		<td class="actions">
			<?php echo $this->Html->link(__('Editar', true), array('action' => 'edit', $item['Item']['item_id'])); ?>
			<?php
				//echo $this->Html->link(__('Eliminar', true), array('action' => 'delete', $item['Item']['item_id']), null, sprintf(__('Esta seguro de eliminar el Item ID# %s?, la accion tambien eliminara las notas de los contratos asociadas a este. Esta seguro de continuar?', true), $item['Item']['item_id']));
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