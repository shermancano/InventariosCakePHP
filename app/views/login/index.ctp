<?php echo $this->Form->create('Login', array('url' => '/login/index'));?>
	<fieldset style="margin-left: 350px; margin-right: 300px">
	<legend><?php __('Ingreso'); ?></legend>
	<?php
		echo $this->Form->input('usua_username', array('maxlength' => 250, 'label' => 'Usuario', 'style' => 'width:99%'));
		echo $this->Form->input('usua_password', array('maxlength' => 250, 'label' => utf8_encode('Contraseña'), 'type' => 'password', 'style' => 'width:99%'));
	?>
	<div class="submit" style="margin-left:10px">
		<input type="submit" value="Ingresar" id="login" />
	</div>
	</fieldset>	
</form>
	<div class="submit" id="imagen_login" style="margin-left:550px">
	</div>
<?php
	//echo $this->Form->end(__('Ingresar', true));
?>
