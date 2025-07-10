<div class="tipoMontos form">
<?php echo $this->Form->create('TipoMonto');?>
	<fieldset>
		<legend><?php __('Editar Tipo Monto'); ?></legend>
	<?php
		echo $this->Form->input('timo_id');
		echo $this->Form->input('timo_descripcion', array('label' => 'Nombre'));
	?>
	</fieldset>
<?php echo $this->Form->end(__('Guardar', true));?>
</div>

<?php
	include("views/sidebars/menu.php");
?>