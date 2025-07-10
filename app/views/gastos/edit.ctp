<script language="Javascript" type="text/javascript" src="/js/contratos/edit.js"></script>

<div class="gastos form">
<?php echo $this->Form->create('Gasto');?>
	<fieldset>
		<legend><?php __('Editar Gasto'); ?></legend>
	<?php
		echo $this->Form->input('gast_id');
		echo $this->Form->input('cont_id', array('type' => 'hidden'));
		echo $this->Form->input('cont_nombre', array('style' => 'width:40%;', 'label' => 'Contrato', 'value' => $this->data['Contrato']['cont_nombre']));
		echo $this->Form->input('tiga_id', array('label' => 'Tipo de Gasto', 'options' => $combo_gastos));
		echo $this->Form->input('gast_monto', array('label' => 'Monto'));
		echo $this->Form->input('timo_id', array('label' => 'Tipo de Monto', 'options' => $combo_tipo_montos));
		echo $this->Form->input('gast_descripcion', array('label' => utf8_encode('Descripción')));
		echo $this->Form->input('gast_nro_factura', array('label' => utf8_encode('Número de Factura')));
		echo $this->Form->input('gast_imp_presupuestaria', array('label' => utf8_encode('Imputación Presupuestaria')));
		echo $this->Form->input('gast_fecha', array('label' => 'Fecha', 'dateFormat' => 'DMY', 'minYear' => date('Y') - 70, 'maxYear' => date('Y')+2));
		echo $this->Form->input('gast_responsable', array('label' => 'Responsable'));
	?>
	</fieldset>
<?php echo $this->Form->end(__('Guardar', true));?>
</div>
<?php
	include("views/sidebars/menu.php");
?>