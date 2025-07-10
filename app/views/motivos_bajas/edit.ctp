<div class="motivos_bajas form">
<?php echo $this->Form->create('MotivoBaja', array('url' => '/motivos_bajas/edit/'.$this->data['MotivoBaja']['moba_id']));?>
	<fieldset>
		<legend><?php __(utf8_encode('Editar Motivo de Baja')); ?></legend>
	<?php
		echo $this->Form->input('moba_id', array('type' => 'hidden'));
		echo $this->Form->input('moba_nombre', array('label' => 'Nombre'));
	?>
	</fieldset>
<?php echo $this->Form->end(__('Guardar', true));?>
</div>
<?php
	include("views/sidebars/menu.php");
?>