<div class="tipoItems view">
<h2><?php  __('Tipo Item');?></h2>
	<dl><?php $i = 0; $class = ' class="altrow"';?>
		<dt<?php if ($i % 2 == 0) echo $class;?>><?php __('Tiit Id'); ?></dt>
		<dd<?php if ($i++ % 2 == 0) echo $class;?>>
			<?php echo $tipoItem['TipoItem']['tiit_id']; ?>
			&nbsp;
		</dd>
		<dt<?php if ($i % 2 == 0) echo $class;?>><?php __('Tiit Descripcion'); ?></dt>
		<dd<?php if ($i++ % 2 == 0) echo $class;?>>
			<?php echo $tipoItem['TipoItem']['tiit_descripcion']; ?>
			&nbsp;
		</dd>
	</dl>
</div>
<div class="actions">
	<h3><?php __('Actions'); ?></h3>
	<ul>
		<li><?php echo $this->Html->link(__('Edit Tipo Item', true), array('action' => 'edit', $tipoItem['TipoItem']['tiit_id'])); ?> </li>
		<li><?php echo $this->Html->link(__('Delete Tipo Item', true), array('action' => 'delete', $tipoItem['TipoItem']['tiit_id']), null, sprintf(__('Are you sure you want to delete # %s?', true), $tipoItem['TipoItem']['tiit_id'])); ?> </li>
		<li><?php echo $this->Html->link(__('List Tipo Items', true), array('action' => 'index')); ?> </li>
		<li><?php echo $this->Html->link(__('New Tipo Item', true), array('action' => 'add')); ?> </li>
	</ul>
</div>
