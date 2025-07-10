<div class="documentos index">
	<h2><?php __('Documentos');?></h2>
	<table cellpadding="0" cellspacing="0">
	<tr>
			<th><?php echo $this->Paginator->sort('Contrato', 'cont_nombre');?></th>
			<th><?php echo $this->Paginator->sort('Nombre', 'docu_nombre');?></th>
			<th><?php echo $this->Paginator->sort('Tipo', 'docu_tipo');?></th>
			<th class="actions"><?php __('Acciones');?></th>
	</tr>
	<?php
	$i = 0;
	foreach ($documentos as $documento):
		$class = null;
		if ($i++ % 2 == 0) {
			$class = ' class="altrow"';
		}
	?>
	<tr<?php echo $class;?>>
		<td><?php echo $documento['Contrato']['cont_nombre']; ?>&nbsp;</td>
		<td><?php echo $documento['Documento']['docu_nombre']; ?>&nbsp;</td>
		<td><?php echo $documento['Documento']['docu_tipo']; ?>&nbsp;</td>
		<td class="actions">
			<?php echo $this->Html->link(__('Ver', true), array('action' => 'view', $documento['Documento']['docu_id'])); ?>
			<?php echo $this->Html->link(__('Eliminar', true), array('action' => 'delete', $documento['Documento']['docu_id']), null, sprintf(__('La accion eliminara el documento ID #%s, desea continuar?', true), $documento['Documento']['docu_id'])); ?>
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