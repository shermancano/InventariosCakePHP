<div class="rechazos_existencias form">
<?php echo $this->Form->create('RechazoSolicitud', array('url' => '/rechazos_solicitudes/edit')); ?>
	<fieldset>
		<legend><?php __('Editar Rechazo'); ?></legend>
	<?php
		echo $this->Form->input('reso_id', array('type' => 'hidden'));
		echo $this->Form->input('reso_motivo', array('label' => 'Motivo'));
	?>
	</fieldset>
<?php echo $this->Form->end(__('Guardar', true));?>
</div>
<?php
	include("views/sidebars/menu.php");
?>
