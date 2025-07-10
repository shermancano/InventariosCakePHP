<div class="rechazos_existencias form">
<?php echo $this->Form->create('RechazoActivoFijo', array('url' => '/rechazos_activos_fijos/edit')); ?>
	<fieldset>
		<legend><?php __('Editar Rechazo'); ?></legend>
	<?php
		echo $this->Form->input('reaf_id', array('type' => 'hidden'));
		echo $this->Form->input('reaf_motivo', array('label' => 'Motivo'));
	?>
	</fieldset>
<?php echo $this->Form->end(__('Guardar', true));?>
</div>
<?php
	include("views/sidebars/menu.php");
?>
