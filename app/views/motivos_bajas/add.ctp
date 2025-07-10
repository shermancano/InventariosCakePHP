
<div class="motivos_bajas form">
<?php echo $this->Form->create('MotivoBaja', array('url' => '/motivos_bajas/add'));?>
	<fieldset>
		<legend><?php __(utf8_encode('Agregar Motivo de Baja')); ?></legend>
	<?php
		echo $this->Form->input('moba_nombre', array('label' => 'Nombre'));
	?>
	</fieldset>
<?php echo $this->Form->end(__('Guardar', true));?>
</div>
<?php
	include("views/sidebars/menu.php");
?>
