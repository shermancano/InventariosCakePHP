<div class="tipoContratos view">
<h2><?php  __('Tipos de Contrato');?></h2>
	<dl><?php $i = 0; $class = ' class="altrow"';?>
		<dt<?php if ($i % 2 == 0) echo $class;?>><?php __('ID'); ?></dt>
		<dd<?php if ($i++ % 2 == 0) echo $class;?>>
			<?php echo $tipoContrato['TipoContrato']['tico_id']; ?>
			&nbsp;
		</dd>
		<dt<?php if ($i % 2 == 0) echo $class;?>><?php __(utf8_encode('Descripción')); ?></dt>
		<dd<?php if ($i++ % 2 == 0) echo $class;?>>
			<?php echo $tipoContrato['TipoContrato']['tico_descripcion']; ?>
			&nbsp;
		</dd>
	</dl>
</div>
<?php
	include("views/sidebars/menu.php");
?>
