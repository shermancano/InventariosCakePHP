<div class="modalidadCompras view">
<h2><?php  __('Modalidad Compra');?></h2>
	<dl><?php $i = 0; $class = ' class="altrow"';?>
		<dt<?php if ($i % 2 == 0) echo $class;?>><?php __('Moco Id'); ?></dt>
		<dd<?php if ($i++ % 2 == 0) echo $class;?>>
			<?php echo $modalidadCompra['ModalidadCompra']['moco_id']; ?>
			&nbsp;
		</dd>
		<dt<?php if ($i % 2 == 0) echo $class;?>><?php __('Moco Descripcion'); ?></dt>
		<dd<?php if ($i++ % 2 == 0) echo $class;?>>
			<?php echo $modalidadCompra['ModalidadCompra']['moco_descripcion']; ?>
			&nbsp;
		</dd>
	</dl>
</div>
<div class="actions">
	<h3><?php __('Actions'); ?></h3>
	<ul>
		<li><?php echo $this->Html->link(__('Edit Modalidad Compra', true), array('action' => 'edit', $modalidadCompra['ModalidadCompra']['moco_id'])); ?> </li>
		<li><?php echo $this->Html->link(__('Delete Modalidad Compra', true), array('action' => 'delete', $modalidadCompra['ModalidadCompra']['moco_id']), null, sprintf(__('Are you sure you want to delete # %s?', true), $modalidadCompra['ModalidadCompra']['moco_id'])); ?> </li>
		<li><?php echo $this->Html->link(__('List Modalidad Compras', true), array('action' => 'index')); ?> </li>
		<li><?php echo $this->Html->link(__('New Modalidad Compra', true), array('action' => 'add')); ?> </li>
	</ul>
</div>
