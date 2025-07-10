<script language="Javascript" type="text/javascript" src="/js/stocks_centros_costos/edit.js"></script>

<div class="stocks_centros_costos form">
<?php echo $this->Form->create('StockCentroCosto', array('url' => '/stocks_centros_costos/edit'));?>
	<fieldset>
		<legend><?php __(utf8_encode('Editar Stock Crítico')); ?></legend>
	<?php
		echo $this->Form->input('stcc_id', array('type' => 'hidden'));
		echo $this->Form->input('ceco_id', array('label' => 'Centro de Costo', 'options' => $centros_costos));
		echo $this->Form->input('prod_id', array('type' => 'hidden'));
		echo $this->Form->input('prod_nombre', array('type' => 'text', 'label' => 'Producto', 'value' => $this->data['Producto']['prod_nombre']));
		echo $this->Form->input('stcc_stock_critico', array('type' => 'text', 'label' => utf8_encode('Stock Crítico')));
	?>
	</fieldset>
<?php echo $this->Form->end(__('Guardar', true));?>
</div>

<?php
	include("views/sidebars/menu.php");
?>