<div class="financiamiento form">
<?php echo $this->Form->create('Financiamiento', array('id' => 'Financiamiento', 'url' => '/reportes/financiamiento'));?>
	<fieldset>
		<legend><?php __('Gastos Históricos por fuente de financiamiento'); ?></legend>
	<?php
		echo $this->Form->input('fina_nombre', array('label' => 'Financiamiento', 'empty' => '-- Seleccione Opción --', 'options' => $financiamientos));		
	?>
	</fieldset>
<?php echo $this->Form->end(__('Generar Reporte', true));?>
</div>
<?php
	include("views/sidebars/menu.php");
?>