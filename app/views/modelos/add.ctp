
<div class="modelo form">
<?php echo $this->Form->create('Modelo', array('url' => '/modelos/add'));?>
	<fieldset>
		<legend><?php __(utf8_encode('Añadir Modelo')); ?></legend>
	<?php
		echo $this->Form->input('mode_nombre', array('label' => 'Nombre'));
	?>
	</fieldset>
<?php echo $this->Form->end(__('Guardar', true));?>
</div>
<?php
	include("views/sidebars/menu.php");
?>
