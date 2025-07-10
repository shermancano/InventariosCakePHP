<script language="Javascript" type="text/javascript" src="/js/reportes/mantenciones.js"></script>
<div class="mantenciones form">
<?php echo $this->Form->create('Reporte', array('id' => 'FormReporte'));?>
	<fieldset>
		<legend><?php __('Reporte de Mantenciones'); ?></legend>
        <div class="input select required">
            <label>Ingrese nombre o c&oacute;digo de producto</label>
            <input type="text" class="codigo" />
            <input type="hidden" name="data[Reporte][ubaf_codigo]" id="ReporteUbafCodigo" />
        </div>
        <?php echo $this->Form->input('ceco_id', array('type' => 'hidden', 'value' => $ceco_id));?>
	</fieldset>
<?php echo $this->Form->end(__('Generar Reporte', true));?>
</div>
<?php
	include("views/sidebars/menu.php");
?>