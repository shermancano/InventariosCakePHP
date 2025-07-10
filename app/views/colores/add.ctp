
<div class="colores form">
<?php echo $this->Form->create('Color', array('url' => '/colores/add'));?>
	<fieldset>
		<legend><?php __(utf8_encode('Añadir Color')); ?></legend>
	<?php
		echo $this->Form->input('colo_nombre', array('label' => 'Nombre'));
	?>
	</fieldset>
<?php echo $this->Form->end(__('Guardar', true));?>
</div>
<?php
	include("views/sidebars/menu.php");
?>
