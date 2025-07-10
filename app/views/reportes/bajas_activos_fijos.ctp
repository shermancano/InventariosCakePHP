<script language="Javascript" type="text/javascript" src="/js/reportes/bajas_activos_fijos.js"></script>

<div class="bajas form">
<?php echo $this->Form->create('Reporte', array('id' => 'FormReporteBajas'));?>
	<fieldset>
		<legend><?php __(utf8_encode('Reporte Bajas de Activos Fijos')); ?></legend>
	<?php
		echo $this->Form->input('ceco_id', array('label' => 'Centro de Costo', 'options' => $centros_costos));
		echo $this->Form->input('prod_id', array('type' => 'hidden'));
		echo $this->Form->input('prod_nombre', array('label' => 'Producto', 'type' => 'text', 'autocomplete' => 'off'));
		echo $this->Form->input('fecha_desde', array('label' => 'Fecha Desde', 'type' => 'text', 'style' => 'width:20%', 'readonly' => 'readonly'));
		echo $this->Form->input('fecha_hasta', array('label' => 'Fecha Hasta', 'type' => 'text', 'style' => 'width:20%', 'readonly' => 'readonly'));		
	?>
	</fieldset>
<?php echo $this->Form->end(__('Generar Reporte', true));?>
</div>
<?php
	include("views/sidebars/menu.php");
?>