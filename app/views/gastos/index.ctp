<div class="gastos index">
	<h2><?php __('Gastos');?></h2>
	<table cellpadding="0" cellspacing="0">
	<tr>
			<th><?php echo $this->Paginator->sort('Tipo de Gasto', 'tiga_id');?></th>
			<th><?php echo $this->Paginator->sort('Contrato', 'cont_descripcion');?></th>
			<th><?php echo $this->Paginator->sort('Monto', 'gast_monto');?></th>
			<th><?php echo $this->Paginator->sort('Tipo de Monto', 'timo_descripcion');?></th>
			<th><?php echo $this->Paginator->sort('Fecha', 'gast_fecha');?></th>
			<th class="actions"><?php __('Acciones');?></th>
	</tr>
	<?php
	$i = 0;
	foreach ($gastos as $gasto):
		$class = null;
		if ($i++ % 2 == 0) {
			$class = ' class="altrow"';
		}
	?>
	<tr<?php echo $class;?>>
		<td><?php echo $gasto['TipoGasto']['tiga_descripcion']; ?>&nbsp;</td>
		<td><?php echo $gasto['Contrato']['cont_nombre']; ?>&nbsp;</td>
		<td><?php echo number_format($gasto['Gasto']['gast_monto'], 0, "", "."); ?>&nbsp;</td>
		<td><?php echo $gasto['TipoMonto']['timo_descripcion']; ?>&nbsp;</td>
		<td><?php echo date("d-m-Y", strtotime($gasto['Gasto']['gast_fecha'])); ?>&nbsp;</td>
		
		<td class="actions">
			<?php echo $this->Html->link(__('Ver', true), array('action' => 'view', $gasto['Gasto']['gast_id'])); ?>
			<?php echo $this->Html->link(__('Editar', true), array('action' => 'edit', $gasto['Gasto']['gast_id'])); ?>
			<?php echo $this->Html->link(__('Eliminar', true), array('action' => 'delete', $gasto['Gasto']['gast_id']), null, sprintf(__('Esta seguro de eliminar el gasto ID # %s?', true), $gasto['Gasto']['gast_id'])); ?>
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