<div class="tipoRenovacions view">
<h2><?php  __('Tipo Renovacion');?></h2>
	<dl><?php $i = 0; $class = ' class="altrow"';?>
		<dt<?php if ($i % 2 == 0) echo $class;?>><?php __('Tire Id'); ?></dt>
		<dd<?php if ($i++ % 2 == 0) echo $class;?>>
			<?php echo $tipoRenovacion['TipoRenovacion']['tire_id']; ?>
			&nbsp;
		</dd>
		<dt<?php if ($i % 2 == 0) echo $class;?>><?php __('Tire Descripcion'); ?></dt>
		<dd<?php if ($i++ % 2 == 0) echo $class;?>>
			<?php echo $tipoRenovacion['TipoRenovacion']['tire_descripcion']; ?>
			&nbsp;
		</dd>
	</dl>
</div>
<div class="actions">
	<h3><?php __('Actions'); ?></h3>
	<ul>
		<li><?php echo $this->Html->link(__('Edit Tipo Renovacion', true), array('action' => 'edit', $tipoRenovacion['TipoRenovacion']['tire_id'])); ?> </li>
		<li><?php echo $this->Html->link(__('Delete Tipo Renovacion', true), array('action' => 'delete', $tipoRenovacion['TipoRenovacion']['tire_id']), null, sprintf(__('Are you sure you want to delete # %s?', true), $tipoRenovacion['TipoRenovacion']['tire_id'])); ?> </li>
		<li><?php echo $this->Html->link(__('List Tipo Renovacions', true), array('action' => 'index')); ?> </li>
		<li><?php echo $this->Html->link(__('New Tipo Renovacion', true), array('action' => 'add')); ?> </li>
	</ul>
</div>
