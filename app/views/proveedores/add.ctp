<div class="proveedores form">
<?php echo $this->Form->create('Proveedor', array('url' => '/proveedores/add'));?>
	<fieldset>
		<legend><?php __('Agregar Proveedor'); ?></legend>
	<?php
		echo $this->Form->input('prov_nombre', array('label' => utf8_encode('Razón Social')));
		echo $this->Form->input('prov_rut', array('label' => 'RUT'));
		echo $this->Form->input('prov_direccion', array('label' => utf8_encode('Dirección')));
		echo $this->Form->input('prov_telefono', array('label' => utf8_encode('Teléfono')));
		echo $this->Form->input('prov_email', array('label' => utf8_encode('Correo Electrónico')));
		echo $this->Form->input('prov_web', array('label' => utf8_encode('Página Web')));
		echo $this->Form->input('prov_contacto', array('label' => 'Contacto'));
	?>
	</fieldset>
<?php echo $this->Form->end(__('Guardar', true));?>
</div>

<?php
	include("views/sidebars/menu.php");
?>