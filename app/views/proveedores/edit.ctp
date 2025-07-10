<div class="proveedores form">
<?php echo $this->Form->create('Proveedor', array('url' => '/proveedores/edit/'.$this->data['Proveedor']['prov_id']));?>
	<fieldset>
		<legend><?php __('Editar Proveedor - '.utf8_encode($this->data['Proveedor']['prov_nombre'])); ?></legend>
	<?php
		echo $this->Form->input('prov_id');
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