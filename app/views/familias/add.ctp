<div class="familias form">
<?php echo $this->Form->create('Familia');?>
	<fieldset>
		<legend><?php __(utf8_encode('Añadir Familia')); ?></legend>
	<?php
		echo $this->Form->input('tifa_id', array('label' => 'Tipo de Familia', 'options' => $tipos_familia));
		echo $this->Form->input('cuco_id', array('label' => 'Cuenta Contable', 'options' => $cuentas_contables));
		echo $this->Form->input('fami_nombre', array('label' => 'Nombre'));
	?>
	</fieldset>
<?php echo $this->Form->end(__('Guardar', true));?>
</div>

<?php
	include("views/sidebars/menu.php");
?>