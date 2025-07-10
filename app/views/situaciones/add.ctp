
<div class="situaciones form">
<?php echo $this->Form->create('Situacion', array('url' => '/situaciones/add'));?>
	<fieldset>
		<legend><?php __(utf8_encode('Añadir Situación')); ?></legend>
	<?php
		echo $this->Form->input('situ_nombre', array('label' => 'Nombre'));
	?>
	</fieldset>
<?php echo $this->Form->end(__('Guardar', true));?>
</div>
<?php
	include("views/sidebars/menu.php");
?>
