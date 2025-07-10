<script type="text/javascript" language="javascript" src="/js/reportes/activos_fijos_general.js"></script>
<div class="activos_fijos_general form">
	<?php echo $this->Form->create('Reporte', array('id' => 'FormReporte'));?>
    <fieldset>
		<legend><?php __(utf8_encode('Reporte Stock Activos Fijos (General)')); ?></legend>
	<?php
		// se saca pdf, solo se trabaja en excel
		//echo $this->Form->input('pdf', array('label' => 'Exportar a PDF', 'type' => 'checkbox'));
		
		echo $this->Form->input('ceco_id', array('label' => 'Centro de Costo', 'options' => $centros_costos));
		
		// se agrega opcion para multiples libros o no
		echo $this->Form->input('multiple', array('label' => 'Exportar a múltiples hojas', 'type' => 'checkbox'));
	?>
	</fieldset>
<?php echo $this->Form->end(__('Generar Reporte', true));?>
</div>
<?php
	include("views/sidebars/menu.php");
?>