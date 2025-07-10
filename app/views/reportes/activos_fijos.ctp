<script language="Javascript" type="text/javascript" src="/js/reportes/activos_fijos.js"></script>

<div class="acfi form">
<?php echo $this->Form->create('Reporte', array('id' => 'FormReporte'));?>
	<fieldset>
		<legend><?php __(utf8_encode('Reporte de Activos Fijos')); ?></legend>
	<?php
		echo $this->Form->input('ceco_id', array('label' => 'Centro de Costo', 'options' => $centros_costos));
		echo $this->Form->input('prod_id', array('type' => 'hidden'));
		echo $this->Form->input('prod_nombre', array('label' => 'Producto', 'type' => 'text'));
		echo $this->Form->input('pdf', array('label' => 'Exportar a PDF', 'type' => 'checkbox'));
	?>
	</fieldset>
<?php echo $this->Form->end(__('Generar Reporte', true));?>
</div>
<?php
	include("views/sidebars/menu.php");
?>