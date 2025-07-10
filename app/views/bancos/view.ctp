<div class="bancos view">
<h2><?php  __('Banco');?></h2>
	<dl><?php $i = 0; $class = ' class="altrow"';?>
		<dt<?php if ($i % 2 == 0) echo $class;?>><?php __('Banc Id'); ?></dt>
		<dd<?php if ($i++ % 2 == 0) echo $class;?>>
			<?php echo $banco['Banco']['banc_id']; ?>
			&nbsp;
		</dd>
		<dt<?php if ($i % 2 == 0) echo $class;?>><?php __('Banc Nombre'); ?></dt>
		<dd<?php if ($i++ % 2 == 0) echo $class;?>>
			<?php echo $banco['Banco']['banc_nombre']; ?>
			&nbsp;
		</dd>
	</dl>
</div>
<div class="actions">
	<h3><?php __('Actions'); ?></h3>
	<ul>
		<li><?php echo $this->Html->link(__('Edit Banco', true), array('action' => 'edit', $banco['Banco']['banc_id'])); ?> </li>
		<li><?php echo $this->Html->link(__('Delete Banco', true), array('action' => 'delete', $banco['Banco']['banc_id']), null, sprintf(__('Are you sure you want to delete # %s?', true), $banco['Banco']['banc_id'])); ?> </li>
		<li><?php echo $this->Html->link(__('List Bancos', true), array('action' => 'index')); ?> </li>
		<li><?php echo $this->Html->link(__('New Banco', true), array('action' => 'add')); ?> </li>
	</ul>
</div>
