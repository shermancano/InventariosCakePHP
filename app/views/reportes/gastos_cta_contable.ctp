<script type="text/javascript" src="/js/reportes/gastos_cta_contable.js"></script>

<div class="stock form">
<?php echo $this->Form->create('Reporte', array('id' => 'FormReporte'));?>
	<fieldset>
		<legend><?php __(utf8_encode('Gastos por Cuenta Contable y Centro de Costo')); ?></legend>
	<?php
		echo $this->Form->input('cuco_id', array('label' => 'Cuenta Contable', 'options' => $ctas_contables));
		echo $this->Form->input('ceco_id', array('empty' => utf8_encode('-- Seleccione Opción ---'), 'label' => 'Centro de Costo', 'options' => $centros_costos));
	?>
	</fieldset>
<?php echo $this->Form->end(__('Generar Reporte', true));?>
</div>
<?php
	include("views/sidebars/menu.php");
?>