
<div class="marcas form">
<?php echo $this->Form->create('Marca', array('url' => '/marcas/add'));?>
	<fieldset>
		<legend><?php __(utf8_encode('Añadir Marca')); ?></legend>
	<?php
		echo $this->Form->input('marc_nombre', array('label' => 'Nombre'));
	?>
	</fieldset>
<?php echo $this->Form->end(__('Guardar', true));?>
</div>
<?php
	include("views/sidebars/menu.php");
?>
