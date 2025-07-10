
<div class="cuentas_contable form">
<?php echo $this->Form->create('CuentaContable', array('url' => '/cuentas_contables/edit/'.$this->data['CuentaContable']['cuco_id']));?>
	<fieldset>
		<legend><?php __(utf8_encode('Editar Cuenta Contable')); ?></legend>
	<?php
		echo $this->Form->input('cuco_id', array('type' => 'hidden'));
		echo $this->Form->input('cuco_nombre', array('label' => 'Nombre'));
	?>
	</fieldset>
<?php echo $this->Form->end(__('Guardar', true));?>
</div>
<?php
	include("views/sidebars/menu.php");
?>

