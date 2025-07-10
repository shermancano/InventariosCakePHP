<div class="contratos index">
	<h2><?php __(utf8_encode('Stock Crítico por Centro de Costo'));?></h2>
	<table cellpadding="0" cellspacing="0">
	<tr>
			<th><?php echo $this->Paginator->sort('Centro de Costo', 'CentroCosto.ceco_nombre');?></th>
			<th><?php echo $this->Paginator->sort('Producto', 'Producto.prod_nombre');?></th>
			<th><?php echo $this->Paginator->sort(utf8_encode('Stock Crítico'), 'StockCentroCosto.stcc_stock_critico');?></th>
			<th class="actions"><?php __('Acciones');?></th>
	</tr>
	<?php
	$i = 0;
	
	foreach ($stocks as $stock):
		$class = null;
		if ($i++ % 2 == 0) {
			$class = ' class="altrow"';
		}
	?>
	<tr<?php echo $class;?>>
		<td><?php echo $stock['CentroCosto']['ceco_nombre']; ?>&nbsp;</td>
		<td><?php echo $stock['Producto']['prod_nombre']; ?>&nbsp;</td>
		<td><?php echo $stock['StockCentroCosto']['stcc_stock_critico']; ?>&nbsp;</td>
		<td class="actions">
			<?php echo $this->Html->link(__('Editar', true), array('action' => 'edit', $stock['StockCentroCosto']['stcc_id'])); ?>
			<?php echo $this->Html->link(__('Eliminar', true), array('action' => 'delete', $stock['StockCentroCosto']['stcc_id']), null, sprintf(__('Esta seguro de eliminar el stock ID #%s?', true), $stock['StockCentroCosto']['stcc_id'])); ?>
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