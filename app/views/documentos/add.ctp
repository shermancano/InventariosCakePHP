<script language="Javascript" type="text/javascript" src="/js/documentos/add.js"></script>

<div class="documentos form">
<?php echo $this->Form->create('Documento', array('type' => 'file'));?>
	<fieldset>
		<legend><?php __(utf8_encode('Añadir Documento')); ?></legend>
	<?php
		echo $this->Form->input('cont_id', array('type' => 'hidden'));
		echo $this->Form->input('cont_nombre', array('label' => utf8_encode('Contrato/Adquisición')));
		
		
		echo "<div class=\"input textarea\">";
		echo "   <label for=\"ContratoContDetalleMultas\">Adjuntar Documentos</label>";
		echo "	 <input type=\"file\" name=\"data[Documento][docs][]\" />&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<a id=\"mas_archivos\" href=\"javascript:;\">[Mas archivos]</a>";
		echo "	 <span id=\"mas_archivos_span\"></span>";
		
		if (isset($file_error)) {
			echo "   <div class=\"error-message\">Debe seleccionar al menos un archivo</div>";
		}
		
		echo "</div>";
		
	?>
	</fieldset>
<?php echo $this->Form->end(__('Guardar', true));?>
</div>

<?php
	include("views/sidebars/menu.php");
?>