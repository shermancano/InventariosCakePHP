<div class="items form">
<?php echo $this->Form->create('Item');?>
	<fieldset>
		<legend><?php __(utf8_encode('A�adir Item')); ?></legend>
	<?php
		echo $this->Form->input('tiit_id', array('label' => utf8_encode('Tipo de Item'), 'options' => $tipo_items));
		echo $this->Form->input('item_descripcion', array('label' => utf8_encode('Descripci�n'), 'style' => 'width:600px;'));
	?>
	</fieldset>
<?php echo $this->Form->end(__('Guardar', true));?>
</div>

<?php
	include("views/sidebars/menu.php");
?>