<script language="Javascript" type="text/javascript" src="/js/depreciaciones/add.js"></script>
<div class="entradas form">
<?php echo $this->Form->create('Depreciacion', array('id' => 'FormDepreciacion', 'url' => '/depreciaciones/add'));?>
    <fieldset>
        <legend><?php __('Nuevo cálculo de depreciación'); ?></legend>
    <?php
		if (isset($ipc)) {
			echo $this->Form->input('depr_ipc', array('value' => $ipc, 'label' => 'IPC', 'onkeypress' => 'return(validchars(event,nums))'));
		} else {
			echo $this->Form->input('depr_ipc', array('label' => 'IPC', 'onkeypress' => 'return(validchars(event,nums))'));
		}
		
		if (isset($ano)) {
			echo $this->Form->input('depr_ano', array('value' => $ano, 'selected' => $ano, 'type' => 'date', 'label' => 'Año', 'dateFormat' => 'Y', 'minYear' => date('Y') - 6, 'maxYear' => date('Y')+88));
		} else {
			echo $this->Form->input('depr_ano', array('type' => 'date', 'label' => 'Año', 'dateFormat' => 'Y', 'minYear' => date('Y') - 6, 'maxYear' => date('Y')+88));
		}
	?>        
    </fieldset>
<?php echo $this->Form->end(__('Depreciar', true));?>
	<br />
	<div class="instrucciones_depr">
		<ol>
			<li><strong>Ingrese el IPC correspondiente del a&ntilde;o a depreciar (ej: 10% = 0.10).</strong></li>
			<li><strong>Seleccione a&ntilde;o.</strong></li>
			<li><strong>Click en botón "Depreciar".</strong></li>
			<li><strong>La operaci&oacute;n puede durar unos minutos (dependiendo de la cantidad de productos ingresados en el periodo).</strong></li>
			<li><strong>El periodo contempla desde el 01-07-(A&Ntilde;O-1) hasta 30-06-(A&Ntilde;O) (se puede configurar en la secci&oacute;n "Depreciaci&oacute;n" en el menu de configuraci&oacute;n del sistema).</strong></li>
			<li><strong>Pueden existir m&uacute;ltiples depreciaciones en el a&ntilde;o (&eacute;stas son identificadas por el nro de revisi&oacute;n).</strong></li>
			<li><strong>Se considera siempre el acumulado de la &uacute;ltima revisi&oacute;n del a&ntilde;o.</strong></li>
			<li><strong>Es requisito que los bienes tengan vida &uacute;til (en a&ntilde;os) y estén marcados como depreciables.</strong></li>
			<li><strong>La acci&oacute;n depreciar afecta directamente la vida &uacute;til de sus bienes. Utilice con precauci&oacute;n.</strong></li>

		</ol>
	</div>

</div>
<?php
	include("views/sidebars/menu.php");
?>