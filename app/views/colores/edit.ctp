
<div class="colores form">
<?php echo $this->Form->create('Color', array('url' => '/colores/edit/'.$this->data['Color']['colo_id']));?>
	<fieldset>
		<legend><?php __(utf8_encode('Editar Color')); ?></legend>
	<?php
		echo $this->Form->input('colo_id', array('type' => 'hidden'));
		echo $this->Form->input('colo_nombre', array('label' => 'Nombre'));
	?>
	</fieldset>
<?php echo $this->Form->end(__('Guardar', true));?>
</div>
<?php
	include("views/sidebars/menu.php");
?>
