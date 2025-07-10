<div class="rubros view">
<h2><?php  __('Rubro');?></h2>
	<dl><?php $i = 0; $class = ' class="altrow"';?>
		<dt<?php if ($i % 2 == 0) echo $class;?>><?php __('Rubr Id'); ?></dt>
		<dd<?php if ($i++ % 2 == 0) echo $class;?>>
			<?php echo $rubro['Rubro']['rubr_id']; ?>
			&nbsp;
		</dd>
		<dt<?php if ($i % 2 == 0) echo $class;?>><?php __('Rubr Descripcion'); ?></dt>
		<dd<?php if ($i++ % 2 == 0) echo $class;?>>
			<?php echo $rubro['Rubro']['rubr_descripcion']; ?>
			&nbsp;
		</dd>
	</dl>
</div>
<div class="actions">
	<h3><?php __('Actions'); ?></h3>
	<ul>
		<li><?php echo $this->Html->link(__('Edit Rubro', true), array('action' => 'edit', $rubro['Rubro']['rubr_id'])); ?> </li>
		<li><?php echo $this->Html->link(__('Delete Rubro', true), array('action' => 'delete', $rubro['Rubro']['rubr_id']), null, sprintf(__('Are you sure you want to delete # %s?', true), $rubro['Rubro']['rubr_id'])); ?> </li>
		<li><?php echo $this->Html->link(__('List Rubros', true), array('action' => 'index')); ?> </li>
		<li><?php echo $this->Html->link(__('New Rubro', true), array('action' => 'add')); ?> </li>
	</ul>
</div>
