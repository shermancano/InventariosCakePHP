<div class="rechazos_existencias form">
<?php echo $this->Form->create('RechazoExistencia', array('url' => '/rechazos_existencias/edit')); ?>
	<fieldset>
		<legend><?php __('Editar Rechazo'); ?></legend>
	<?php
		echo $this->Form->input('reex_id', array('type' => 'hidden'));
		echo $this->Form->input('reex_motivo', array('label' => 'Motivo'));
	?>
	</fieldset>
<?php echo $this->Form->end(__('Guardar', true));?>
</div>
<?php
	include("views/sidebars/menu.php");
?>
