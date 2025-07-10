<div class="tipoContratos form">
<?php echo $this->Form->create('TipoContrato');?>
	<fieldset>
		<legend><?php __('Editar Tipo de Contrato'); ?></legend>
	<?php
		echo $this->Form->input('tico_id');
		echo $this->Form->input('tico_descripcion', array('label' => 'Nombre'));
	?>
	</fieldset>
<?php echo $this->Form->end(__('Guardar', true));?>
</div>
<?php
	include("views/sidebars/menu.php");
?>