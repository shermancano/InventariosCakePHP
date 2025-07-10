
<div class="propiedades form">
<?php echo $this->Form->create('Propiedad', array('url' => '/propiedades/add'));?>
	<fieldset>
		<legend><?php __(utf8_encode('Añadir Propiedad')); ?></legend>
	<?php
		echo $this->Form->input('prop_nombre', array('label' => 'Nombre'));
	?>
	</fieldset>
<?php echo $this->Form->end(__('Guardar', true));?>
</div>
<?php
	include("views/sidebars/menu.php");
?>
