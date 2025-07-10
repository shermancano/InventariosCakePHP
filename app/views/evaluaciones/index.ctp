
<div class="evaluaciones index">
	<h2><?php __('Evaluaciones');?></h2>
	<table cellpadding="0" cellspacing="0">
	<tr>
			<th><?php echo $this->Paginator->sort('Contrato', 'cont_nombre');?></th>
			<th><?php echo $this->Paginator->sort('Proveedor', 'prov_nombre');?></th>
			<th>Nota Final</th>
			<th><?php echo $this->Paginator->sort(utf8_encode('Fecha de Evaluación'), 'eval_fecha');?></th>
			<th class="actions"><?php __('Acciones');?></th>
	</tr>
	<?php
	$i = 0;
	
	foreach ($evaluaciones as $evaluacion):
		$class = null;
		if ($i++ % 2 == 0) {
			$class = ' class="altrow"';
		}
	?>
	<tr<?php echo $class;?>>
		<td><?php echo $evaluacion['Contrato']['cont_nombre']; ?>&nbsp;</td>
		<td><?php echo $evaluacion['Contrato']['Proveedor']['prov_nombre']; ?>&nbsp;</td>
		<td><?php echo number_format($evaluacion['Evaluacion']['nota_final'], 1, ",", ""); ?>&nbsp;</td>
		<td><?php echo date("d-m-Y", strtotime($evaluacion['Evaluacion']['eval_fecha'])); ?>&nbsp;</td>
		<td class="actions">
			<?php
				//echo $this->Html->link(__('Ver', true), array('action' => 'view', $evaluacion['Evaluacion']['eval_id']));
			?>
			<?php echo $this->Html->link(__('Editar', true), array('action' => 'edit', $evaluacion['Evaluacion']['eval_id'])); ?>
			<?php echo $this->Html->link(__('Eliminar', true), array('action' => 'delete', $evaluacion['Evaluacion']['eval_id']), null, sprintf(__('Seguro que desea eliminar la evaluacion ID #%s?, tambien se borraran las notas e items asociadas a esta (en caso de existir). Desea continuar?', true), $evaluacion['Evaluacion']['eval_id'])); ?>
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
