<div class="perfiles form">
<?php echo $this->Form->create('Perfil', array('url' => '/perfiles/add'));?>
	<fieldset>
		<legend><?php __(utf8_encode('Añadir Perfil')); ?></legend>
	<?php
		echo $this->Form->input('perf_nombre', array('label' => 'Nombre'));
	?>
	</fieldset>
<?php echo $this->Form->end(__('Guardar', true));?>
</div>
<?php
	include("views/sidebars/menu.php");
?>