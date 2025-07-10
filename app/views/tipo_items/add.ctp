<div class="tipoItems form">
<?php echo $this->Form->create('TipoItem');?>
	<fieldset>
		<legend><?php __(utf8_encode('Añadir Tipo de Item')); ?></legend>
	<?php
		echo $this->Form->input('tiit_descripcion', array('label' => utf8_encode('Descripción')));
	?>
	</fieldset>
<?php echo $this->Form->end(__('Guardar', true));?>
</div>

<?php
	include("views/sidebars/menu.php");
?>