<div class="usuarios form">
<?php echo $this->Form->create('Usuario', array('url' => '/usuarios/micuenta'));?>
	<fieldset>
		<legend><?php __('Mi Cuenta'); ?></legend>
	<?php
		echo $this->Form->input('usua_id', array('value' => $usua_id, 'type' => 'hidden'));
		echo $this->Form->input('usua_username', array('label' => 'Usuario', 'readonly' => 'readonly'));
		echo $this->Form->input('usua_rut', array('label' => 'RUT'));
		echo $this->Form->input('usua_nombre', array('label' => 'Nombre'));
		echo $this->Form->input('usua_email', array('label' => utf8_encode('Correo Electr�nico')));
		echo $this->Form->input('usua_password', array('type' => 'password', 'label' => utf8_encode('Contrase�a')));
		echo $this->Form->input('usua_password2', array('type' => 'password', 'label' => utf8_encode('Repetir contrase�a')));
	?>
	</fieldset>
<?php echo $this->Form->end(__('Guardar', true));?>
</div>

<?php
	include("views/sidebars/menu.php");
?>
