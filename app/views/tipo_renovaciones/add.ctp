<div class="tipoRenovacions form">
<?php echo $this->Form->create('TipoRenovacion', array('url' => array('controller' => 'tipo_renovaciones', 'action' => 'add')));?>
	<fieldset>
		<legend><?php __(utf8_encode('Añadir Tipo de Renovación')); ?></legend>
	<?php
		echo $this->Form->input('tire_descripcion', array('label' => 'Nombre'));
	?>
	</fieldset>
<?php echo $this->Form->end(__('Guardar', true));?>
</div>

<?php
	include("views/sidebars/menu.php");
?>