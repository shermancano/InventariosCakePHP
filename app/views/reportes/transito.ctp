<script type="text/javascript" src="/js/reportes/transito.js"></script>

<div class="historial form">
<?php echo $this->Form->create('Reporte', array('id' => 'FormReporte'));?>
	<fieldset>
		<legend><?php __(utf8_encode('Stock en Tránsito (General)')); ?></legend>
	<?php
		echo $this->Form->input('tibi_id', array('label' => 'Tipo de Bien', 'options' => $tipos_bienes));
		echo $this->Form->input('pdf', array('label' => 'Exportar a PDF', 'type' => 'checkbox'));
	?>
	</fieldset>
<?php echo $this->Form->end(__('Generar Reporte', true));?>
</div>
<?php
	include("views/sidebars/menu.php");
?>