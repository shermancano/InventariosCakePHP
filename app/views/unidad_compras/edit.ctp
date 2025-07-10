<div class="unidadCompras form">
<?php echo $this->Form->create('UnidadCompra');?>
	<fieldset>
		<legend><?php __('Editar Unidad de Compra'); ?></legend>
	<?php
		echo $this->Form->input('unco_id');
		echo $this->Form->input('unco_descripcion', array('label' => 'Nombre'));
	?>
	</fieldset>
<?php echo $this->Form->end(__('Guardar', true));?>
</div>

<?php
	include("views/sidebars/menu.php");
?>
