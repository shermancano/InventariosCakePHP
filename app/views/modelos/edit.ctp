
<div class="modelo form">
<?php echo $this->Form->create('Modelo', array('url' => '/modelos/edit/'.$this->data['Modelo']['mode_id']));?>
	<fieldset>
		<legend><?php __(utf8_encode('Editar Modelo')); ?></legend>
	<?php
		echo $this->Form->input('mode_id', array('type' => 'hidden'));
		echo $this->Form->input('mode_nombre', array('label' => 'Nombre'));
	?>
	</fieldset>
<?php echo $this->Form->end(__('Guardar', true));?>
</div>
<?php
	include("views/sidebars/menu.php");
?>
