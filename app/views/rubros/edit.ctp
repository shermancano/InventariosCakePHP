<div class="rubros form">
<?php echo $this->Form->create('Rubro');?>
	<fieldset>
		<legend><?php __('Editar Rubro'); ?></legend>
	<?php
		echo $this->Form->input('rubr_id');
		echo $this->Form->input('rubr_descripcion', array('label' => utf8_encode('Descripci�n')));
	?>
	</fieldset>
<?php echo $this->Form->end(__('Guardar', true));?>
</div>
<?php
	include("views/sidebars/menu.php");
?>