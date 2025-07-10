<script language="Javascript" type="text/javascript" src="/js/configuraciones/index.js"></script>

<div id="cod_barra_dialog">
</div>

<div id="logo_dialog">
	<img src="data:image/png;base64,<?php echo $conf['site_logo']; ?>" title="Logo" alt="0"/>
</div>

<div class="configuraciones form">
<?php echo $this->Form->create('Configuracion', array('type' => 'file', 'url' => '/configuraciones/index'));?>
	<p><strong>* Algunos cambios se ver&aacute;n reflejados al entrar nuevamente al sistema.</strong></p>
	<br />
	<fieldset>
		<legend><?php __(utf8_encode('Configuración del sitio')); ?></legend>
	<?php
	
		if (isset($conf)) {
			echo $this->Form->input('site_title', array('value' => $conf['site_title'], 'type' => 'text', 'label' => utf8_encode('Título')));
			echo $this->Form->input('site_logo', array('class' => 'file_conf', 'after' => '<a id="logo" href="javascript:;">Ver Logo</a>', 'type' => 'file', 'label' => 'Logo'));
		} else {
			echo $this->Form->input('site_title', array('type' => 'text', 'label' => utf8_encode('Título')));
			echo $this->Form->input('site_logo', array('class' => 'file_conf', 'type' => 'file', 'label' => 'Logo'));
		}
	?>
	</fieldset>
	<br />
    
    <fieldset>
    	<legend><?php echo __(utf8_encode('Parámetro IVA'));?></legend>
    <?php
    	if (isset($conf['param_iva'])) {
			echo $this->Form->input('param_iva', array('label' => 'IVA', 'value' => $conf['param_iva'], 'onKeyPress' => 'return validchars(event, num)'));
		} else {
			echo $this->Form->input('param_iva', array('label' => 'IVA', 'onKeyPress' => 'return validchars(event, num)'));
		}
	?>
    </fieldset>
    <br />	
	<fieldset>
		<legend><?php __(utf8_encode('Códigos de Barra')); ?></legend>
	<?php	
		if (isset($conf)) {
			echo $this->Form->input('barcode_type', array('after' => '<a id="ver_cod_barra_demo" href="javascript:;">Ver</a>', 'selected' => $conf['barcode_type'], 'label' => utf8_encode('Tipo de Código'), 'options' => $barcode_types));
			echo $this->Form->input('barcode_logo', array('selected' => $conf['barcode_logo'], 'label' => utf8_encode('¿Usar Logo?'), 'options' => $booleans));
			echo $this->Form->input('barcode_prod', array('selected' => $conf['barcode_prod'], 'label' => utf8_encode('¿Mostrar nombre del producto?'), 'options' => $booleans));
			echo $this->Form->input('barcode_date', array('selected' => $conf['barcode_date'], 'label' => utf8_encode('¿Mostrar fecha y hora?'), 'options' => $booleans));
			echo $this->Form->input('barcode_cc', array('selected' => $conf['barcode_cc'], 'label' => utf8_encode('¿Mostrar Centro de Costo de destino?'), 'options' => $booleans));
			echo $this->Form->input('barcode_serie', array('selected' => $conf['barcode_serie'], 'label' => utf8_encode('¿Mostrar número de Serie?'), 'options' => $booleans));
			echo $this->Form->input('barcode_titulo', array('value' => $conf['barcode_titulo'], 'label' => 'Titulo', 'options' => $booleans));
	?>
    <div id="titulo">
		<?php	
			echo $this->Form->input('barcode_titulo_nombre', array('value' => $conf['barcode_titulo_nombre'], 'label' => 'Nombre de Titulo', 'maxLength' => 38));
		?>
    </div>
  	<?php
			echo $this->Form->input('barcode_height', array('value' => $conf['barcode_height'], 'label' => 'Alto de la imagen'));
			echo $this->Form->input('barcode_width', array('value' => $conf['barcode_width'], 'label' => 'Ancho de la imagen'));
		} else {
			$data = $this->data['Configuracion'];
		
			echo $this->Form->input('barcode_type', array('after' => '<a id="ver_cod_barra_demo" href="javascript:;">Ver</a>', 'selected' => $data['barcode_type'], 'label' => utf8_encode('Tipo de Código'), 'options' => $barcode_types));
			echo $this->Form->input('barcode_logo', array('selected' => $data['barcode_logo'], 'label' => utf8_encode('¿Usar Logo?'), 'options' => $booleans));
			echo $this->Form->input('barcode_prod', array('selected' => $data['barcode_prod'], 'label' => utf8_encode('¿Mostrar nombre del producto?'), 'options' => $booleans));
			echo $this->Form->input('barcode_date', array('selected' => $data['barcode_date'], 'label' => utf8_encode('¿Mostrar fecha y hora?'), 'options' => $booleans));
			echo $this->Form->input('barcode_cc', array('selected' => $data['barcode_cc'], 'label' => utf8_encode('¿Mostrar Centro de Costo de destino?'), 'options' => $booleans));
			echo $this->Form->input('barcode_serie', array('selected' => $data['barcode_serie'], 'label' => utf8_encode('¿Mostrar número de serie?'), 'options' => $booleans));
			echo $this->Form->input('barcode_titulo', array('value' => $data['barcode_titulo'], 'label' => 'Titulo'));
	?>
    <div id="titulo">
		<?php	
			echo $this->Form->input('barcode_titulo_nombre', array('value' => $data['barcode_titulo_nombre'], 'label' => 'Nombre de Titulo', 'maxLength' => 38));
		?>
    </div>
    <?php
			echo $this->Form->input('barcode_height', array('value' => $data['barcode_height'], 'label' => 'Alto de la imagen'));
			echo $this->Form->input('barcode_width', array('value' => $data['barcode_width'], 'label' => 'Ancho de la imagen'));			
		}
	?>
	</fieldset>
	<br />
	
	<fieldset>
		<legend><?php __(utf8_encode('Depreciación')); ?></legend>
	<?php
		if (isset($conf)) {
			if (!isset($conf['depr_date_ini'])) {
				$conf['depr_date_ini'] = null;
			} else {
				list($mes, $dia) = preg_split("/\-/", $conf['depr_date_ini']);
				$conf['depr_date_ini'] = $dia."-".$mes;
			}
			
			if (!isset($conf['depr_date_end'])) {
				$conf['depr_date_end'] = null;
			} else {
				list($mes, $dia) = preg_split("/\-/", $conf['depr_date_end']);
				$conf['depr_date_end'] = $dia."-".$mes;
			}
			
			echo $this->Form->input('depr_date_ini', array('type' => 'text', 'value' => $conf['depr_date_ini'], 'label' => 'Fecha de inicio del periodo'));
			echo $this->Form->input('depr_date_end', array('type' => 'text', 'value' => $conf['depr_date_end'], 'label' => utf8_encode('Fecha de término del periodo')));
		} else {
			echo $this->Form->input('depr_date_ini', array('type' => 'text', 'value' => $data['depr_date_ini'], 'label' => 'Fecha de inicio del periodo'));
			echo $this->Form->input('depr_date_end', array('type' => 'text', 'value' => $data['depr_date_end'], 'label' => utf8_encode('Fecha de término del periodo')));
		}
	?>
	</fieldset>
	<br />
	
	<fieldset>
		<legend><?php __(utf8_encode('Servidor de Correo')); ?></legend>
	<?php
		if (isset($conf)) {
			echo $this->Form->input('mail_from_name', array('label' => utf8_encode('Remitente'), 'value' => $conf['mail_from_name']));
			echo $this->Form->input('mail_from', array('label' => utf8_encode('Dirección de correo'), 'value' => $conf['mail_from']));

			if ($conf['use_smtp'] == 0) {
				echo $this->Form->input('use_smtp', array('type' => 'checkbox', 'label' => 'Usar servidor SMTP', 'value' => $conf['use_smtp']));
				echo $this->Form->input('smtp_host', array('readonly' => 'readonly', 'label' => 'SMTP Host'));
				echo $this->Form->input('smtp_port', array('readonly' => 'readonly', 'label' => 'SMTP Puerto'));
				echo $this->Form->input('smtp_timeout', array('readonly' => 'readonly', 'label' => 'SMTP Timeout'));
			
			} else {
				echo $this->Form->input('use_smtp', array('checked' => 'checked', 'type' => 'checkbox', 'label' => 'Usar servidor SMTP', 'value' => $conf['use_smtp']));
				echo $this->Form->input('smtp_host', array('label' => 'SMTP Host', 'value' => $conf['smtp_host']));
				echo $this->Form->input('smtp_port', array('label' => 'SMTP Puerto', 'value' => $conf['smtp_port']));
				echo $this->Form->input('smtp_timeout', array('label' => 'SMTP Timeout', 'value' => $conf['smtp_timeout']));
			}
			
			if ($conf['smtp_auth'] == 0) {
				echo $this->Form->input('smtp_auth', array('type' => 'checkbox', 'label' => utf8_encode('Autenticación SMTP'), 'value' => $conf['smtp_auth']));
				echo $this->Form->input('smtp_user', array('readonly' => 'readonly', 'label' => 'Usuario SMTP'));
				echo $this->Form->input('smtp_pass', array('readonly' => 'readonly', 'type' => 'password', 'label' => utf8_encode('Contraseña SMTP')));
			
			} else {
				echo $this->Form->input('smtp_auth', array('ckecked' => 'checked', 'type' => 'checkbox', 'label' => utf8_encode('Autenticación SMTP'), 'value' => $conf['smtp_auth']));
				echo $this->Form->input('smtp_user', array('label' => 'Usuario SMTP', 'value' => $conf['smtp_user']));
				echo $this->Form->input('smtp_pass', array('type' => 'password', 'label' => utf8_encode('Contraseña SMTP'), 'value' => $conf['smtp_pass']));
			
			}
		
		} else {
			echo $this->Form->input('mail_from_name', array('label' => utf8_encode('Remitente')));
			echo $this->Form->input('mail_from', array('label' => utf8_encode('Dirección de correo')));
		
			if ($this->data['Configuracion']['use_smtp'] == 0) {
				echo $this->Form->input('use_smtp', array('type' => 'checkbox', 'label' => 'Usar servidor SMTP'));
				echo $this->Form->input('smtp_host', array('readonly' => 'readonly', 'label' => 'SMTP Host'));
				echo $this->Form->input('smtp_port', array('readonly' => 'readonly', 'label' => 'SMTP Puerto'));
				echo $this->Form->input('smtp_timeout', array('readonly' => 'readonly', 'label' => 'SMTP Timeout'));
			} else {
				echo $this->Form->input('use_smtp', array('checked' => 'checked', 'type' => 'checkbox', 'label' => 'Usar servidor SMTP'));
				echo $this->Form->input('smtp_host', array('label' => 'SMTP Host'));
				echo $this->Form->input('smtp_port', array('label' => 'SMTP Puerto'));
				echo $this->Form->input('smtp_timeout', array('label' => 'SMTP Timeout'));
			}
			
			if ($this->data['Configuracion']['smtp_auth'] == 0) {
				echo $this->Form->input('smtp_auth', array('type' => 'checkbox', 'label' => utf8_encode('Autenticación SMTP')));
				echo $this->Form->input('smtp_user', array('readonly' => 'readonly', 'label' => 'Usuario SMTP'));
				echo $this->Form->input('smtp_pass', array('readonly' => 'readonly', 'type' => 'password', 'label' => utf8_encode('Contraseña SMTP')));
			} else {
				echo $this->Form->input('smtp_auth', array('checked' => 'checked', 'type' => 'checkbox', 'label' => utf8_encode('Autenticación SMTP')));
				echo $this->Form->input('smtp_user', array('label' => 'Usuario SMTP'));
				echo $this->Form->input('smtp_pass', array('type' => 'password', 'label' => utf8_encode('Contraseña SMTP')));
			}
		}
	?>
	</fieldset>
<?php echo $this->Form->end(__('Guardar', true));?>
</div>

<?php
	include("views/sidebars/menu.php");
?>
