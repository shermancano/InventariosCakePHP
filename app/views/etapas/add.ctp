<script language="Javascript" type="text/javascript" src="/js/etapas/add.js"></script>
<div class="etapas form">
<?php echo $this->Form->create('Etapa', array('id' => 'form_etapas'));?>
	<fieldset>
		<legend><?php __('Nueva Etapa'); ?></legend>
	<?php
		echo $this->Form->input('cont_id', array('type' => 'hidden'));
		echo $this->Form->input('cont_nombre', array('label' => 'Ingrese el nombre del Contrato/Nombre del Proveedor/RUT del Proveedor'));
		echo $this->Form->input('etap_nombre', array('label' => 'Nombre de la etapa'));
		
		echo "<div class=\"input textarea\">";
		echo "   <label for=\"ContratoContDetalleMultas\">Actividades (nombre, fecha presupuestada, fecha real)";
		echo "	 <span><a id=\"mas_actividades\" href=\"javascript:;\"><img src=\"/img/add.png\" alt=\"0\" title=\"Agregar\" /></a></span></label>";
		echo "	 <span>";
		echo "	 <input class=\"acti_nombre\" name=\"data[Actividad][0][acti_nombre]\" />";
		echo "   &nbsp;&nbsp;&nbsp;&nbsp;<input readonly=\"readonly\" style=\"width:120px;\" class=\"fecha\" name=\"data[Actividad][0][acti_fecha_presupuestada]\" />";
		echo "   &nbsp;&nbsp;&nbsp;&nbsp;<input readonly=\"readonly\" style=\"width:120px;\" class=\"fecha\" name=\"data[Actividad][0][acti_fecha_real]\" />";
		echo "	 &nbsp;&nbsp;<span><a class=\"del_actividad\" href=\"javascript:;\"><img src=\"/img/delete.png\" alt=\"0\" title=\"Eliminar\" /></a></span>";
		echo "   <br /><br />";
		echo "	 </span>";
		echo "	 <span id=\"mas_actividades_span\"></span>";
		echo "</div>";
		
	?>
	</fieldset>
<?php echo $this->Form->end(__('Guardar', true));?>
</div>

<?php
	include("views/sidebars/menu.php");
?>