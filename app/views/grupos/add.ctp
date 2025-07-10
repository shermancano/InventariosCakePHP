<script language="Javascript" type="text/javascript" src="/js/grupos/add.js"></script>

<div class="grupos form">
<?php echo $this->Form->create('Grupo');?>
	<fieldset>
		<legend><?php __(utf8_encode('Añadir Grupo')); ?></legend>
	<?php
		echo $this->Form->input('tifa_id', array('after' => '&nbsp;<img id="tifa_loader" style="display:none;" src="/img/ajax-loader.gif" alt="0" />', 'label' => 'Tipo de Familia', 'options' => $tipos_familias, 'empty' => utf8_encode('-- Seleccione opción --')));
		echo $this->Form->input('fami_id', array('label' => 'Familia', 'empty' => utf8_encode('-- Seleccione opción --')));
		echo $this->Form->input('grup_nombre', array('label' => 'Nombre'));
	?>
	</fieldset>
<?php echo $this->Form->end(__('Guardar', true));?>
</div>

<?php
	include("views/sidebars/menu.php");
?>