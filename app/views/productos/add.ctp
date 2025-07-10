<script language="Javascript" type="text/javascript" src="/js/productos/add.js"></script>

<div class="productos form">
<?php echo $this->Form->create('Producto', array('type' => 'file'));?>
	<fieldset>
		<legend><?php __(utf8_encode('Añadir Producto')); ?></legend>
	<?php
		echo $this->Form->input('tibi_id', array('label' => 'Tipo de Bien', 'options' => $tipos_bienes));
		echo $this->Form->input('tifa_id', array('after' => '&nbsp;<img id="tifa_loader" style="display:none;" src="/img/ajax-loader.gif" alt="0" />', 'label' => 'Tipo de Familia', 'options' => $tipos_familias, 'empty' => utf8_encode('-- Seleccione opción --')));
		echo $this->Form->input('fami_id', array('after' => '&nbsp;<img id="fami_loader" style="display:none;" src="/img/ajax-loader.gif" alt="0" />', 'label' => 'Familia', 'empty' => utf8_encode('-- Seleccione opción --')));
		echo $this->Form->input('grup_id', array('after' => '&nbsp;<img id="grup_loader" style="display:none;" src="/img/ajax-loader.gif" alt="0" />', 'label' => 'Grupo', 'empty' => utf8_encode('-- Seleccione opción --')));
		echo $this->Form->input('unid_id', array('label' => 'Tipo de Unidad', 'options' => $unidades));
		echo $this->Form->input('prod_nombre', array('label' => 'Nombre'));
		echo $this->Form->input('prod_nombre_fantasia', array('label' => utf8_encode('Nombre de Fantasía')));
		echo $this->Form->input('prod_codigo', array('label' => utf8_encode('Código Interno')));
		echo $this->Form->input('ProductoImagen.prod_contenido', array('label' => 'Adjuntar imagen', 'type' => 'file'));		
	?>
	</fieldset>
<?php echo $this->Form->end(__('Guardar', true));?>
</div>

<?php
	include("views/sidebars/menu.php");
?>