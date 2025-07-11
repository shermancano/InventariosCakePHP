<script type="text/javascript" language="javascript" src="/js/reportes/activos_fijos_migracion.js"></script>
<div class="activos_fijos_general form">
	<?php echo $this->Form->create('Reporte', array('id' => 'FormReporteMigracion'));?>
    <fieldset>
		<legend><?php __(utf8_encode('Reporte Stock Activos Fijos Migracion (General)')); ?></legend>
	<?php
		echo $this->Form->input('ceco_id', array('label' => 'Centro de Costo', 'options' => $centros_costos));
	?>
	</fieldset>
<?php echo $this->Form->end(__('Generar Reporte Migración', true));?>
</div>
<?php
	include("views/sidebars/menu.php");
?>
