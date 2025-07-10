<div class="ordenes_compras index">
	<h2><?php __('Ordenes de Compra');?></h2>
	<table cellpadding="0" cellspacing="0">
	<tr>
			<th><?php echo $this->Paginator->sort('Número', 'OrdenCompra.orco_numero');?></th>
			<th><?php echo $this->Paginator->sort('Proveedor', 'Proveedor.prov_nombre');?></th>
			<th><?php echo $this->Paginator->sort('Nombre', 'OrdenCompra.orco_nombre');?></th>
			<th><?php echo $this->Paginator->sort('Fecha', 'OrdenCompra.orco_fecha');?></th>
			<th class="actions"><?php __('Acciones');?></th>
	</tr>
	<?php
	$i = 0;
	foreach ($ordenes as $orden):
		$class = null;
		if ($i++ % 2 == 0) {
			$class = ' class="altrow"';
		}
	?>
	<tr<?php echo $class;?>>
		<td><?php echo $orden['OrdenCompra']['orco_numero']; ?>&nbsp;</td>
		<td><?php echo $orden['Proveedor']['prov_nombre']; ?>&nbsp;</td>
		<td><?php echo $orden['OrdenCompra']['orco_nombre']; ?>&nbsp;</td>
		<td><?php echo date("d-m-Y H:i:s", strtotime($orden['OrdenCompra']['orco_fecha'])); ?>&nbsp;</td>
		<td class="actions">
			<?php echo $this->Html->link(__('Ver', true), array('action' => 'view', $orden['OrdenCompra']['orco_id'])); ?>
			<?php
				//echo $this->Html->link(__('Editar', true), array('action' => 'edit', $orden['OrdenCompra']['orco_id']));
			?>
			<?php echo $this->Html->link(__('Eliminar', true), array('action' => 'delete', $orden['OrdenCompra']['orco_id']), null, sprintf(__('La accion eliminara la orden de compra seleccionada y todo el detalle asociado a ella, ¿esta seguro que desea continuar?', true), $orden['OrdenCompra']['orco_id'])); ?>
			<?php echo $this->Html->link(__('Comprobante', true), array('action' => 'comprobante', $orden['OrdenCompra']['orco_id'])); ?>
		</td>
	</tr>
<?php endforeach; ?>
	</table>
	<p>
	<?php
	echo $this->Paginator->counter(array(
	'format' => __('Página %page% de %pages%, mostrando %current% registros de un total de %count% total, empezando en %start%, terminando en %end%', true)
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