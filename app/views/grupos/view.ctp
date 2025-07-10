<div class="grupos view">
<h2><?php  __('Grupo');?></h2>
	<dl><?php $i = 0; $class = ' class="altrow"';?>
		<dt<?php if ($i % 2 == 0) echo $class;?>><?php __('Grup Id'); ?></dt>
		<dd<?php if ($i++ % 2 == 0) echo $class;?>>
			<?php echo $grupo['Grupo']['grup_id']; ?>
			&nbsp;
		</dd>
		<dt<?php if ($i % 2 == 0) echo $class;?>><?php __('Grupo Nombre'); ?></dt>
		<dd<?php if ($i++ % 2 == 0) echo $class;?>>
			<?php echo $grupo['Grupo']['grupo_nombre']; ?>
			&nbsp;
		</dd>
		<dt<?php if ($i % 2 == 0) echo $class;?>><?php __('Familia'); ?></dt>
		<dd<?php if ($i++ % 2 == 0) echo $class;?>>
			<?php echo $this->Html->link($grupo['Familia']['fami_id'], array('controller' => 'familias', 'action' => 'view', $grupo['Familia']['fami_id'])); ?>
			&nbsp;
		</dd>
	</dl>
</div>
<?php
	include("views/sidebars/menu.php");
?>
