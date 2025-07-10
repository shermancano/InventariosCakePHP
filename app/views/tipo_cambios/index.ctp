<div class="tipoCambios index">
	<h2><?php __('Tipos de Cambios');?></h2>
	<table cellpadding="0" cellspacing="0">
	<tr>
			<th><?php echo $this->Paginator->sort('Fecha', 'tica_fecha');?></th>
			<th><?php echo $this->Paginator->sort('Tipo de Monto', 'timo_id');?></th>
			<th><?php echo $this->Paginator->sort('Monto', 'tica_monto');?></th>
			<th class="actions"><?php __('Acciones');?></th>
	</tr>
	<?php
	$i = 0;
	foreach ($tipoCambios as $tipoCambio):
		$class = null;
		if ($i++ % 2 == 0) {
			$class = ' class="altrow"';
		}
	?>
	<tr<?php echo $class;?>>
		<td>
		<?php
			if ($tipoCambio['TipoCambio']['timo_id'] != 2) {
				echo date("d-m-Y", strtotime($tipoCambio['TipoCambio']['tica_fecha']));
			} else {
				echo date("m-Y", strtotime($tipoCambio['TipoCambio']['tica_fecha']));
			}
		?>
		&nbsp;
		</td>
		<td><?php echo $tipoCambio['TipoMonto']['timo_descripcion']; ?>&nbsp;</td>
		<td><?php echo $tipoCambio['TipoCambio']['tica_monto']; ?>&nbsp;</td>
		<td class="actions">
			<?php echo $this->Html->link(__('Editar', true), array('action' => 'edit', $tipoCambio['TipoCambio']['tica_id'])); ?>
			<?php echo $this->Html->link(__('Eliminar', true), array('action' => 'delete', $tipoCambio['TipoCambio']['tica_id']), null, sprintf(__('Seguro que desea eliminar el tipo de cambio ID # %s?', true), $tipoCambio['TipoCambio']['tica_id'])); ?>
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