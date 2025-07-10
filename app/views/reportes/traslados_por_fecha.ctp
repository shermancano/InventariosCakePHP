<script type="text/javascript" language="javascript" src="/js/reportes/traslados_por_fecha.js"></script>
<div class="traslados_por_fecha form">
<?php echo $this->Form->create('Reporte', array('id' => 'ReporteTraslado'));?>
	<fieldset>
    	<legend><?php echo __('Traslados por Fechas');?></legend>
        <?php
        	echo $this->Form->input('ceco_id', array('label' => 'Centro de Costo/Salud', 'options' => $centros_costos));    
			echo $this->Form->input('ceco_destino', array('label' => 'Centro de Costo/Salud (Destino)', 'empty' => '-- Seleccione Opción --', 'options' => $centros_costos_destino));
			echo $this->Form->input('tibi_nombre', array('label' => 'Tipo de Bien', 'options' => $tipos_bienes));
			echo $this->Form->input('fecha_desde', array('label' => 'Fecha Desde', 'style' => 'width: 215px', 'autocomplete' => 'off'));			
			echo $this->Form->input('fecha_hasta', array('label' => 'Fecha Hasta', 'style' => 'width: 215px', 'autocomplete' => 'off'));
			echo $this->Form->input('pdf', array('type' => 'checkbox', 'label' => 'Exportar a Pdf'))
		?>
    </fieldset>
<?php echo $this->Form->end(__('Generar Reporte', true));?>
</div>
<?php 
	include("views/sidebars/menu.php");
?>