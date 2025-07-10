<div class="unidadCompras view">
<h2><?php  __('Unidad Compra');?></h2>
	<dl><?php $i = 0; $class = ' class="altrow"';?>
		<dt<?php if ($i % 2 == 0) echo $class;?>><?php __('Unco Id'); ?></dt>
		<dd<?php if ($i++ % 2 == 0) echo $class;?>>
			<?php echo $unidadCompra['UnidadCompra']['unco_id']; ?>
			&nbsp;
		</dd>
		<dt<?php if ($i % 2 == 0) echo $class;?>><?php __('Unco Descripcion'); ?></dt>
		<dd<?php if ($i++ % 2 == 0) echo $class;?>>
			<?php echo $unidadCompra['UnidadCompra']['unco_descripcion']; ?>
			&nbsp;
		</dd>
	</dl>
</div>
<div class="actions">
	<h3><?php __('Actions'); ?></h3>
	<ul>
		<li><?php echo $this->Html->link(__('Edit Unidad Compra', true), array('action' => 'edit', $unidadCompra['UnidadCompra']['unco_id'])); ?> </li>
		<li><?php echo $this->Html->link(__('Delete Unidad Compra', true), array('action' => 'delete', $unidadCompra['UnidadCompra']['unco_id']), null, sprintf(__('Are you sure you want to delete # %s?', true), $unidadCompra['UnidadCompra']['unco_id'])); ?> </li>
		<li><?php echo $this->Html->link(__('List Unidad Compras', true), array('action' => 'index')); ?> </li>
		<li><?php echo $this->Html->link(__('New Unidad Compra', true), array('action' => 'add')); ?> </li>
	</ul>
</div>
