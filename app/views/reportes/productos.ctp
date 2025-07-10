<script language="Javascript" type="text/javascript" src="/js/reportes/productos.js"></script>

<div class="productos form">
<?php echo $this->Form->create('Reporte', array('id' => 'FormReporte'));?>
	<fieldset>
		<legend><?php __(utf8_encode('Reporte de Productos')); ?></legend>
	<?php
		echo $this->Form->input('tibi_id', array('label' => 'Tipo de Bien', 'options' => $tipos_bienes, 'empty' => utf8_encode('-- Seleccione Opción --')));	
		echo $this->Form->input('pdf', array('label' => 'Exportar a PDF', 'type' => 'checkbox'));
	?>
	</fieldset>
<?php echo $this->Form->end(__('Generar Reporte', true));?>
</div>

<?php
	include("views/sidebars/menu.php");
?>