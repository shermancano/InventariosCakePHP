<div class="tipoRenovacions form">
<?php echo $this->Form->create('TipoDocumento', array('url' => array('controller' => 'tipos_documentos', 'action' => 'add')));?>
	<fieldset>
		<legend><?php __(utf8_encode('A�adir Tipo de Documento')); ?></legend>
	<?php
		echo $this->Form->input('tido_descripcion', array('label' => utf8_encode('Descripci�n')));
	?>
	</fieldset>
<?php echo $this->Form->end(__('Guardar', true));?>
</div>

<?php
	include("views/sidebars/menu.php");
?>