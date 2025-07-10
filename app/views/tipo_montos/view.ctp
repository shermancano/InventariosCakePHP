<div class="tipoMontos view">
<h2><?php  __('Tipo Monto');?></h2>
	<dl><?php $i = 0; $class = ' class="altrow"';?>
		<dt<?php if ($i % 2 == 0) echo $class;?>><?php __('Timo Id'); ?></dt>
		<dd<?php if ($i++ % 2 == 0) echo $class;?>>
			<?php echo $tipoMonto['TipoMonto']['timo_id']; ?>
			&nbsp;
		</dd>
		<dt<?php if ($i % 2 == 0) echo $class;?>><?php __('Time Descripcion'); ?></dt>
		<dd<?php if ($i++ % 2 == 0) echo $class;?>>
			<?php echo $tipoMonto['TipoMonto']['time_descripcion']; ?>
			&nbsp;
		</dd>
	</dl>
</div>
<div class="actions">
	<h3><?php __('Actions'); ?></h3>
	<ul>
		<li><?php echo $this->Html->link(__('Edit Tipo Monto', true), array('action' => 'edit', $tipoMonto['TipoMonto']['timo_id'])); ?> </li>
		<li><?php echo $this->Html->link(__('Delete Tipo Monto', true), array('action' => 'delete', $tipoMonto['TipoMonto']['timo_id']), null, sprintf(__('Are you sure you want to delete # %s?', true), $tipoMonto['TipoMonto']['timo_id'])); ?> </li>
		<li><?php echo $this->Html->link(__('List Tipo Montos', true), array('action' => 'index')); ?> </li>
		<li><?php echo $this->Html->link(__('New Tipo Monto', true), array('action' => 'add')); ?> </li>
	</ul>
</div>
