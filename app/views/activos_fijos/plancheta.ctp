<script language="javascript" type="text/javascript" src="/js/reportes/plancheta.js"></script>
<div class="entradas form">
<?php echo $this->Form->create('ActivoFijo', array('id' => 'FormActivoFijo', 'url' => '/activos_fijos/plancheta'));?>
	<fieldset>
		<legend><?php __(utf8_encode('Plancheta')); ?></legend>
	<?php
		echo $this->Form->input('ceco_id', array('label' => 'Centro de Costo', 'options' => $centros_costos));
		echo $this->Form->input('plancheta', array('type' => 'radio', 'options' => array(1 => 'Normal', 2 => 'Agrupado'), 'value' => 1, 'legend' => false));
	?>
	</fieldset>
<?php echo $this->Form->end(__('Generar Reporte', true));?>
</div>

<?php
	include("views/sidebars/menu.php");
?>