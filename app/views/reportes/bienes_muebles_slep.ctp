<script type="text/javascript" language="javascript" src="/js/reportes/bienes_muebles.js"></script>

<div class="stock_ceco form">
<?php echo $this->Form->create('Reporte', array('id' => 'FormReporte'));?>
<fieldset>
	<legend><?php echo __('Reporte Bienes Muebles SLEP');?></legend>
    <?php
    	echo $this->Form->input('ceco_id', array('label' => 'Centro de Costo', 'options' => $centros_costos));
		echo $this->Form->input('pdf', array('type' => 'checkbox', 'label' => 'Exportar a PDF'));
	?>
</fieldset>
<?php echo $this->Form->end(__('Generar Reporte', true));?>
</div>
<?php
	include("views/sidebars/menu.php");
?>