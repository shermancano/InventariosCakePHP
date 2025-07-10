<script language="Javascript" type="text/javascript" src="/js/reportes/stock.js"></script>

<div class="stock form">
<?php echo $this->Form->create('Reporte', array('id' => 'FormReporte'));?>
	<fieldset>
		<legend><?php __(utf8_encode('Reporte de Stock')); ?></legend>
	<?php
		echo $this->Form->input('ceco_id', array('label' => 'Centro de Costo', 'options' => $centros_costos));
		echo $this->Form->input('tibi_id', array('label' => 'Tipo de Bien', 'options' => $tipos_bienes));
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