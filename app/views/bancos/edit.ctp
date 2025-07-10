<div class="bancos form">
<?php echo $this->Form->create('Banco');?>
	<fieldset>
		<legend><?php __('Editar Banco'); ?></legend>
	<?php
		echo $this->Form->input('banc_id');
		echo $this->Form->input('banc_nombre', array('label' => 'Nombre'));
	?>
	</fieldset>
<?php echo $this->Form->end(__('Guardar', true));?>
</div>

<?php
	include("views/sidebars/menu.php");
?>