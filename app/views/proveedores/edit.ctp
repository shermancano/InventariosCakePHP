<div class="proveedores form">
<?php echo $this->Form->create('Proveedor', array('url' => '/proveedores/edit/'.$this->data['Proveedor']['prov_id']));?>
	<fieldset>
		<legend><?php __('Editar Proveedor - '.utf8_encode($this->data['Proveedor']['prov_nombre'])); ?></legend>
	<?php
		echo $this->Form->input('prov_id');
		echo $this->Form->input('prov_nombre', array('label' => utf8_encode('Raz�n Social')));
		echo $this->Form->input('prov_rut', array('label' => 'RUT'));
		echo $this->Form->input('prov_direccion', array('label' => utf8_encode('Direcci�n')));
		echo $this->Form->input('prov_telefono', array('label' => utf8_encode('Tel�fono')));
		echo $this->Form->input('prov_email', array('label' => utf8_encode('Correo Electr�nico')));
		echo $this->Form->input('prov_web', array('label' => utf8_encode('P�gina Web')));
		echo $this->Form->input('prov_contacto', array('label' => 'Contacto'));
	?>
	</fieldset>
<?php echo $this->Form->end(__('Guardar', true));?>
</div>

<?php
	include("views/sidebars/menu.php");
?>