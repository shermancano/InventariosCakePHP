<div class="gastos index">
	<h2><?php __('Responsables');?></h2>
	<table cellpadding="0" cellspacing="0">
	<tr>
			<th><?php echo $this->Paginator->sort('Centro de Costo', 'ceco_nombre');?></th>
			<th><?php echo $this->Paginator->sort('Usuario', 'usua_nombre');?></th>
			<th><?php echo $this->Paginator->sort('Estado', 'esre_nombre');?></th>
			
			<th class="actions"><?php __('Acciones');?></th>
	</tr>
	<?php
	$i = 0;
	foreach ($responsables as $resp):
		$class = null;
		if ($i++ % 2 == 0) {
			$class = ' class="altrow"';
		}
	?>
	<tr<?php echo $class;?>>
		<td><?php echo $resp['CentroCosto']['ceco_nombre']; ?>&nbsp;</td>
		<td><?php echo $resp['Usuario']['usua_nombre']; ?>&nbsp;</td>
		<td><?php echo $resp['EstadoRegistro']['esre_nombre']; ?>&nbsp;</td>
		<td class="actions">
			<?php echo $this->Html->link(__('Editar', true), array('action' => 'edit', $resp['Responsable']['resp_id'])); ?>
			<?php echo $this->Html->link(__('Eliminar', true), array('action' => 'delete', $resp['Responsable']['resp_id']), null, __('La accion eliminara el responsable asociado al Centro de Costo, esta seguro que desea continuar?', true)); ?>
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
