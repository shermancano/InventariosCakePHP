<div class="etapas view">
<h2><?php  __('Etapa');?></h2>
	<dl><?php $i = 0; $class = ' class="altrow"';?>
		<dt<?php if ($i % 2 == 0) echo $class;?>><?php __('Etap Id'); ?></dt>
		<dd<?php if ($i++ % 2 == 0) echo $class;?>>
			<?php echo $etapa['Etapa']['etap_id']; ?>
			&nbsp;
		</dd>
		<dt<?php if ($i % 2 == 0) echo $class;?>><?php __('Cont Id'); ?></dt>
		<dd<?php if ($i++ % 2 == 0) echo $class;?>>
			<?php echo $etapa['Etapa']['cont_id']; ?>
			&nbsp;
		</dd>
		<dt<?php if ($i % 2 == 0) echo $class;?>><?php __('Etap Nombre'); ?></dt>
		<dd<?php if ($i++ % 2 == 0) echo $class;?>>
			<?php echo $etapa['Etapa']['etap_nombre']; ?>
			&nbsp;
		</dd>
	</dl>
</div>
<div class="actions">
	<h3><?php __('Actions'); ?></h3>
	<ul>
		<li><?php echo $this->Html->link(__('Edit Etapa', true), array('action' => 'edit', $etapa['Etapa']['etap_id'])); ?> </li>
		<li><?php echo $this->Html->link(__('Delete Etapa', true), array('action' => 'delete', $etapa['Etapa']['etap_id']), null, sprintf(__('Are you sure you want to delete # %s?', true), $etapa['Etapa']['etap_id'])); ?> </li>
		<li><?php echo $this->Html->link(__('List Etapas', true), array('action' => 'index')); ?> </li>
		<li><?php echo $this->Html->link(__('New Etapa', true), array('action' => 'add')); ?> </li>
	</ul>
</div>
