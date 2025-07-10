<div class="tipoContratos form">
<?php echo $this->Form->create('Perfil', array('url' => '/perfiles/edit'));?>
	<fieldset>
		<legend><?php __(utf8_encode('Editar Perfil')); ?></legend>
	<?php
		echo $this->Form->input('perf_id', array('type' => 'hidden'));
		echo $this->Form->input('perf_nombre', array('label' => 'Nombre'));
	?>
	</fieldset>
<?php echo $this->Form->end(__('Guardar', true));?>
</div>
<?php
	include("views/sidebars/menu.php");
?>