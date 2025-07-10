<div class="usuarios form">
<?php echo $this->Form->create('Usuario');?>
	<fieldset>
		<legend><?php __(utf8_encode('A�adir Usuario')); ?></legend>
	<?php
		echo $this->Form->input('perf_id', array('label' => 'Perfil', 'options' => $perfiles));
		echo $this->Form->input('usua_rut', array('label' => 'RUT'));
		echo $this->Form->input('usua_nombre', array('label' => 'Nombre'));
		echo $this->Form->input('usua_username', array('label' => 'Usuario'));
		echo $this->Form->input('usua_email', array('label' => utf8_encode('Correo Electr�nico')));
		echo $this->Form->input('usua_password', array('type' => 'password', 'label' => utf8_encode('Contrase�a')));
		echo $this->Form->input('usua_password2', array('type' => 'password', 'label' => utf8_encode('Repetir contrase�a')));
		echo $this->Form->input('esre_id', array('label' => utf8_encode('Estado'), 'options' => $estados));
	?>
	</fieldset>
<?php echo $this->Form->end(__('Guardar', true));?>
</div>

<?php
	include("views/sidebars/menu.php");
?>