<script language="Javascript" type="text/javascript" src="/js/productos/edit.js"></script>

<div class="productos form">
<?php echo $this->Form->create('Producto', array('type' => 'file'));?>
	<fieldset>
		<legend><?php __(utf8_encode('Editar Producto')); ?></legend>
	<?php
		echo $this->Form->input('prod_id', array('type' => 'hidden'));
		echo $this->Form->input('tibi_id', array('label' => 'Tipo de Bien', 'options' => $tipos_bienes));
		
		if (isset($this->data['Grupo']['Familia']['tifa_id'])) {
			echo $this->Form->input('tifa_id', array('value' => $this->data['Grupo']['Familia']['tifa_id'], 'after' => '&nbsp;<img id="tifa_loader" style="display:none;" src="/img/ajax-loader.gif" alt="0" />', 'label' => 'Tipo de Familia', 'options' => $tipos_familias, 'empty' => utf8_encode('-- Seleccione opción --')));
		} else {
			echo $this->Form->input('tifa_id', array('after' => '&nbsp;<img id="tifa_loader" style="display:none;" src="/img/ajax-loader.gif" alt="0" />', 'label' => 'Tipo de Familia', 'options' => $tipos_familias, 'empty' => utf8_encode('-- Seleccione opción --')));
		}
		
		if (isset($this->data['Grupo']['fami_id'])) {
			echo $this->Form->input('fami_id', array('value' => $this->data['Grupo']['fami_id'], 'after' => '&nbsp;<img id="fami_loader" style="display:none;" src="/img/ajax-loader.gif" alt="0" />', 'label' => 'Familia', 'options' => $familias, 'empty' => utf8_encode('-- Seleccione opción --')));
		} else {
			echo $this->Form->input('fami_id', array('after' => '&nbsp;<img id="fami_loader" style="display:none;" src="/img/ajax-loader.gif" alt="0" />', 'label' => 'Familia', 'options' => $familias, 'empty' => utf8_encode('-- Seleccione opción --')));
		}
		
		echo $this->Form->input('grup_id', array('options' => $grupos, 'after' => '&nbsp;<img id="grup_loader" style="display:none;" src="/img/ajax-loader.gif" alt="0" />', 'label' => 'Grupo', 'empty' => utf8_encode('-- Seleccione opción --')));
		echo $this->Form->input('unid_id', array('label' => 'Tipo de Unidad', 'options' => $unidades));
		echo $this->Form->input('prod_nombre', array('label' => 'Nombre'));
		echo $this->Form->input('prod_nombre_fantasia', array('label' => utf8_encode('Nombre de Fantasía')));
		echo $this->Form->input('prod_stock_critico', array('label' => utf8_encode('Stock Crítico')));
		echo $this->Form->input('prod_codigo', array('label' => utf8_encode('Código Interno')));
		echo $this->Form->input('ProductoImagen.prod_contenido', array('label' => 'Adjuntar imagen', 'type' => 'file'));
		if (sizeof($prim_id) > 0 && is_array($prim_id)) {
			echo $this->Form->input('ProductoImagen.prim_id', array('type' => 'hidden', 'value' => $prim_id['ProductoImagen']['prim_id']));
			echo "<div class='actions'>";
			echo $this->Html->link(__('Ver Imagen', true), array('action' => 'view_imagen', $prim_id['ProductoImagen']['prod_id']), array('target' => '_blank'));
			echo "</div>";
		}
	?>
	</fieldset>
<?php echo $this->Form->end(__('Guardar', true));?>
</div>

<?php
	include("views/sidebars/menu.php");
?>