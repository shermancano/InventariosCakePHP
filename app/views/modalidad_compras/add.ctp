<div class="modalidadCompras form">
<?php echo $this->Form->create('ModalidadCompra');?>
	<fieldset>
		<legend><?php __(utf8_encode('Añadir Modalidad de Compra')); ?></legend>
	<?php
		echo $this->Form->input('moco_descripcion', array('label' => 'Nombre'));
	?>
	</fieldset>
<?php echo $this->Form->end(__('Guardar', true));?>
</div>

<?php
	include("views/sidebars/menu.php");
?>