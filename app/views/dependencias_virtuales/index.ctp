<div class="dependencias_virtuales index">
	<h2><?php __('Dependencias Virtuales');?></h2>
	<table cellpadding="0" cellspacing="0">
	<tr>
			<th><?php echo $this->Paginator->sort('Nombre', 'devi_nombre');?></th>
			<th class="actions"><?php __('Acciones');?></th>
	</tr>
	<?php
	$i = 0;
	foreach ($dependenciaVirtual as $devi):
		$class = null;
		if ($i++ % 2 == 0) {
			$class = ' class="altrow"';
		}
	?>
	<tr<?php echo $class;?>>
		<td><?php echo $devi['DependenciaVirtual']['devi_nombre']; ?>&nbsp;</td>
		<td class="actions">
			<?php echo $this->Html->link(__('Editar', true), array('action' => 'edit', $devi['DependenciaVirtual']['devi_id'])); ?>
			<?php echo $this->Html->link(__('Eliminar', true), array('action' => 'delete', $devi['DependenciaVirtual']['devi_id']), null, __('La accion eliminara la dependencia virtual seleccionada, esta seguro que desea continuar?', true)); ?>
		</td>
	</tr>
<?php endforeach; ?>
	</table>
	<p>
	<?php
	echo $this->Paginator->counter(array(
	'format' => __(utf8_encode('Pagina %page% de %pages%, mostrando %current% registros de un total de %count% total, empezando en %start%, terminando en %end%'), true)
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