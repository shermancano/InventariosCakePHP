<div class="etapas index">
	<h2><?php __('Etapas');?></h2>
	<table cellpadding="0" cellspacing="0">
	<tr>
			<th><?php echo $this->Paginator->sort('Contrato', 'cont_nombre');?></th>
			<th><?php echo $this->Paginator->sort('Etapa', 'etap_nombre');?></th>
			<th class="actions"><?php __('Acciones');?></th>
	</tr>
	<?php
	$i = 0;
	foreach ($etapas as $etapa):
		$class = null;
		if ($i++ % 2 == 0) {
			$class = ' class="altrow"';
		}
	?>
	<tr<?php echo $class;?>>
		<td><?php echo $etapa['Contrato']['cont_nombre']; ?>&nbsp;</td>
		<td><?php echo $etapa['Etapa']['etap_nombre']; ?>&nbsp;</td>
		<td class="actions">
			<?php echo $this->Html->link(__('Editar', true), array('action' => 'edit', $etapa['Etapa']['etap_id'])); ?>
			<?php echo $this->Html->link(__('Eliminar', true), array('action' => 'delete', $etapa['Etapa']['etap_id']), null, sprintf(__('Esta seguro de eliminar la etapa ID #%s, la accion eliminara tambien todas las actividades asociadas a ella, seguro que desea continuar?', true), $etapa['Etapa']['etap_id'])); ?>
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