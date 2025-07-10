
<div class="financiamientos form">
<?php echo $this->Form->create('Financiamiento', array('url' => '/financiamientos/add'));?>
	<fieldset>
		<legend><?php __(utf8_encode('Añadir Financiamiento')); ?></legend>
	<?php
		echo $this->Form->input('fina_nombre', array('label' => 'Nombre'));
	?>
	</fieldset>
<?php echo $this->Form->end(__('Guardar', true));?>
</div>
<?php
	include("views/sidebars/menu.php");
?>
