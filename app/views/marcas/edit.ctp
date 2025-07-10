
<div class="marca form">
<?php echo $this->Form->create('Marca', array('url' => '/marcas/edit/'.$this->data['Marca']['marc_id']));?>
	<fieldset>
		<legend><?php __(utf8_encode('Editar Marca')); ?></legend>
	<?php
		echo $this->Form->input('marc_id', array('type' => 'hidden'));
		echo $this->Form->input('marc_nombre', array('label' => 'Nombre'));
	?>
	</fieldset>
<?php echo $this->Form->end(__('Guardar', true));?>
</div>
<?php
	include("views/sidebars/menu.php");
?>
