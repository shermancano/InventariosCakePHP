<div class="financiamientos index">
	<h2><?php __('Financiamientos');?></h2>
	<table cellpadding="0" cellspacing="0">
	<tr>
			<th><?php echo $this->Paginator->sort('Nombre', 'marc_nombre');?></th>
			<th class="actions"><?php __('Acciones');?></th>
	</tr>
	<?php
	$i = 0;
	foreach ($financiamientos as $fina):
		$class = null;
		if ($i++ % 2 == 0) {
			$class = ' class="altrow"';
		}
	?>
	<tr<?php echo $class;?>>
		<td><?php echo $fina['Financiamiento']['fina_nombre']; ?>&nbsp;</td>
		<td class="actions">
			<?php echo $this->Html->link(__('Editar', true), array('action' => 'edit', $fina['Financiamiento']['fina_id'])); ?>
			<?php echo $this->Html->link(__('Eliminar', true), array('action' => 'delete', $fina['Financiamiento']['fina_id']), null, __('La accion eliminara la fuente de financiamiento, esta seguro que desea continuar?', true)); ?>
		</td>
	</tr>
<?php endforeach; ?>
	</table>
	<p>
	<?php
	echo $this->Paginator->counter(array(
	'format' => __(utf8_encode('P�gina %page% de %pages%, mostrando %current% registros de un total de %count% total, empezando en %start%, terminando en %end%'), true)
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
