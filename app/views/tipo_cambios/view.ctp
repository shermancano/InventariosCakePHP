<div class="tipoCambios view">
<h2><?php  __('Tipo Cambio');?></h2>
	<dl><?php $i = 0; $class = ' class="altrow"';?>
		<dt<?php if ($i % 2 == 0) echo $class;?>><?php __('Tica Id'); ?></dt>
		<dd<?php if ($i++ % 2 == 0) echo $class;?>>
			<?php echo $tipoCambio['TipoCambio']['tica_id']; ?>
			&nbsp;
		</dd>
		<dt<?php if ($i % 2 == 0) echo $class;?>><?php __('Tica Fecha'); ?></dt>
		<dd<?php if ($i++ % 2 == 0) echo $class;?>>
			<?php echo $tipoCambio['TipoCambio']['tica_fecha']; ?>
			&nbsp;
		</dd>
		<dt<?php if ($i % 2 == 0) echo $class;?>><?php __('Timo Id'); ?></dt>
		<dd<?php if ($i++ % 2 == 0) echo $class;?>>
			<?php echo $tipoCambio['TipoCambio']['timo_id']; ?>
			&nbsp;
		</dd>
	</dl>
</div>
<div class="actions">
	<h3><?php __('Actions'); ?></h3>
	<ul>
		<li><?php echo $this->Html->link(__('Edit Tipo Cambio', true), array('action' => 'edit', $tipoCambio['TipoCambio']['tica_id'])); ?> </li>
		<li><?php echo $this->Html->link(__('Delete Tipo Cambio', true), array('action' => 'delete', $tipoCambio['TipoCambio']['tica_id']), null, sprintf(__('Are you sure you want to delete # %s?', true), $tipoCambio['TipoCambio']['tica_id'])); ?> </li>
		<li><?php echo $this->Html->link(__('List Tipo Cambios', true), array('action' => 'index')); ?> </li>
		<li><?php echo $this->Html->link(__('New Tipo Cambio', true), array('action' => 'add')); ?> </li>
	</ul>
</div>
