
<div class="dependencias_virtuales form">
<?php echo $this->Form->create('DependenciaVirtual', array('url' => '/dependencias_virtuales/add'));?>
	<fieldset>
		<legend><?php __('Añadir Dependencia Virtual'); ?></legend>
	<?php
		echo $this->Form->input('devi_nombre', array('label' => 'Nombre'));
	?>
	</fieldset>
<?php echo $this->Form->end(__('Guardar', true));?>
</div>
<?php
	include("views/sidebars/menu.php");
?>
