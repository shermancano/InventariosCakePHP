<script language="Javascript" type="text/javascript">
/*
$(document).ready(function() {
	$("#TipoCambioTimoId").change(function() {
		if ($(this).val() == 2) {
			alert('asdaadg');
		}
	});
});
*/
</script>

<div class="tipoCambios form">
<?php echo $this->Form->create('TipoCambio');?>
	<fieldset>
		<legend><?php __(utf8_encode('Añadir Tipo de Cambio')); ?></legend>
	<?php
		echo $this->Form->input('timo_id', array('label' => 'Tipo de Monto', 'options' => $arr_montos));
		echo $this->Form->input('tica_fecha', array('label' => 'Fecha', 'dateFormat' => 'DMY', 'minYear' => date('Y') - 70, 'maxYear' => date('Y')+2));
		echo $this->Form->input('tica_monto', array('label' => 'Monto'));
	?>
	</fieldset>
<?php echo $this->Form->end(__('Guardar', true));?>
<br />
<p>* Las UF son tratadas mensualmente.</p>
</div>

<?php
	include("views/sidebars/menu.php");
?>