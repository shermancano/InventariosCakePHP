
<script language="Javascript" type="text/javascript" src="/js/centros_costos/edit_centro_costo.js"></script>
<div class="centros_costos form">
<?php echo $this->Form->create('CentroCosto', array('id' => 'CentroCostoEdit', 'url' => '/centros_costos/edit/'.$this->data['CentroCosto']['ceco_id']));?>
	<fieldset>
		<legend><?php __(utf8_encode('Editar Centro de Costo')); ?></legend>
	<?php
		echo $this->Form->input('ceco_id', array('type' => 'hidden'));
		echo $this->Form->input('comu_id', array('label' => 'Comuna', 'options' => $comunas));
		echo $this->Form->input('ceco_id_padre', array('label' => 'Centro de Costo Padre', 'options' => $centros_costos, 'after' => '&nbsp;&nbsp;<img id="tifa_loader" src="/img/info.png" alt="0" />&nbsp; Verifique que el valor seleccionado o cargado sea el <strong>padre</strong> del centro de costo a editar'));
		echo $this->Form->input('ceco_nombre', array('label' => 'Nombre'));
		echo $this->Form->input('ceco_direccion', array('type' => 'text', 'label' => utf8_encode('Dirección')));
		echo $this->Form->input('ceco_campana', array('type' => 'checkbox', 'label' => utf8_encode('Es Campaña?')));
	?>
	</fieldset>
    <fieldset>
    	<legend><?php __(utf8_encode('Información Opcional'));?></legend>
    <?php
		echo $this->Form->input('tilo_id', array('label' => 'Tipo Localizacion', 'empty' => utf8_encode('-- Seleccione Opción --'), 'options' => $tipos_localizaciones));
    	echo $this->Form->input('ceco_rut', array('label' => 'RUT'));
		echo $this->Form->input('ceco_razon_social', array('label' => 'Razon Social'));
		echo $this->Form->input('ceco_telefono', array('label' => 'Telefono'));
		echo $this->Form->input('ceco_fax', array('label' => 'Fax'));
		echo $this->Form->input('ceco_email', array('label' => utf8_encode('Correo Electrónico')));
		echo $this->Form->input('ceco_rut_representante', array('label' => 'Rut Responsable'));
		echo $this->Form->input('ceco_nombre_representante', array('label' => 'Nombre'));
		echo $this->Form->input('ceco_direccion_representante', array('label' => utf8_encode('Dirección')));
	?>
    </fieldset>
<?php echo $this->Form->end(__('Guardar', true));?>
</div>
<?php
	include("views/sidebars/menu.php");
?>
