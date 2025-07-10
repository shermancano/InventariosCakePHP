<script type="text/javascript" src="https://www.google.com/jsapi"></script>
<script language="Javascript" type="text/javascript" src="/js/gastos/graficos.js"></script>

<div class="gastos form">
<?php echo $this->Form->create('GastoGrafico', array('onsubmit' => 'return false;'));?>
	<fieldset>
		<legend><?php __(utf8_encode('Gráfico de Gastos')); ?></legend>
	<?php
		echo $this->Form->input('cont_id', array('type' => 'hidden'));
		echo $this->Form->input('cont_nombre', array('type' => 'text', 'style' => 'width:40%;', 'label' => 'Ingrese el nombre del Contrato/Nombre del Proveedor/RUT del Proveedor'));
	?>
	<div class="submit">
		<input id="buscarGrafico" type="submit" value="Buscar" />
		<span id="ajax_loader" style="display:none;">&nbsp;&nbsp;<img src="/img/ajax-loader.gif" border="0" alt="0" /></span>
	</div>
	<p>* Para hacer uso de esta funcionalidad, usted necesita tener una conexi&oacute;n a Internet activa.</p>
	</fieldset>
</form>

<div id="chart_dialog" style="display:none;">
   <div id="chart_div"></div>
</div>
</div>

<?php
	include("views/sidebars/menu.php");
?>
