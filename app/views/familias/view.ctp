<div class="familias view">
<h2><?php  __('Familia');?></h2>
	<dl><?php $i = 0; $class = ' class="altrow"';?>
		<dt<?php if ($i % 2 == 0) echo $class;?>><?php __('Fami Id'); ?></dt>
		<dd<?php if ($i++ % 2 == 0) echo $class;?>>
			<?php echo $familia['Familia']['fami_id']; ?>
			&nbsp;
		</dd>
		<dt<?php if ($i % 2 == 0) echo $class;?>><?php __('Fami Nombre'); ?></dt>
		<dd<?php if ($i++ % 2 == 0) echo $class;?>>
			<?php echo $familia['Familia']['fami_nombre']; ?>
			&nbsp;
		</dd>
	</dl>
</div>

<?php
	include("views/sidebars/menu.php");
?>
