<script language="Javascript" type="text/javascript" src="/js/etapas/edit.js"></script>
<div class="etapas form">
<?php echo $this->Form->create('Etapa', array('id' => 'form_etapas'));?>
	<fieldset>
		<legend><?php __('Editar Etapa'); ?></legend>
	<?php
		
		echo $this->Form->input('etap_id');
		echo $this->Form->input('cont_id', array('type' => 'hidden', 'value' => $this->data['Contrato']['cont_id']));
		echo $this->Form->input('cont_nombre', array('label' => 'Ingrese el nombre del Contrato/Nombre del Proveedor/RUT del Proveedor', 'value' => $this->data['Contrato']['cont_nombre']));
		echo $this->Form->input('etap_nombre', array('label' => 'Nombre de la etapa'));
		
		echo "<div class=\"input textarea\">";
		echo "   <label for=\"ContratoContDetalleMultas\">Actividades (nombre, fecha presupuestada, fecha real)";
		echo "	 <span><a id=\"mas_actividades\" href=\"javascript:;\"><img src=\"/img/add.png\" alt=\"0\" title=\"Agregar\" /></a></span></label>";
		$acti_count = 0;
		
		foreach ($this->data['Actividad'] as $acti) {
			echo "   <span>";
			echo "	 <input type=\"hidden\" name=\"data[Actividad][".$acti_count."][acti_id]\" value=\"".$acti['acti_id']."\" />";
			echo "	 <input value=\"".$acti['acti_nombre']."\" class=\"acti_nombre\" name=\"data[Actividad][".$acti_count."][acti_nombre]\" />";
			
			if ($acti['acti_fecha_presupuestada'] != "") {
				echo "   &nbsp;&nbsp;&nbsp;&nbsp;<input value=\"".date("d-m-Y", strtotime($acti['acti_fecha_presupuestada']))."\" readonly=\"readonly\" style=\"width:120px;\" class=\"fecha\" name=\"data[Actividad][".$acti_count."][acti_fecha_presupuestada]\" />";
			} else {
				echo "   &nbsp;&nbsp;&nbsp;&nbsp;<input readonly=\"readonly\" style=\"width:120px;\" class=\"fecha\" name=\"data[Actividad][".$acti_count."][acti_fecha_presupuestada]\" />";
			}
			
			if ($acti['acti_fecha_real'] != "") {
				echo "   &nbsp;&nbsp;&nbsp;&nbsp;<input value=\"".date("d-m-Y", strtotime($acti['acti_fecha_real']))."\" readonly=\"readonly\" style=\"width:120px;\" class=\"fecha\" name=\"data[Actividad][".$acti_count."][acti_fecha_real]\" />";
			} else {
				echo "   &nbsp;&nbsp;&nbsp;&nbsp;<input readonly=\"readonly\" style=\"width:120px;\" class=\"fecha\" name=\"data[Actividad][".$acti_count."][acti_fecha_real]\" />";
			}
			
			echo "	 &nbsp;&nbsp;<span><a rel=\"".$acti['acti_id']."\" class=\"del_actividad\" href=\"javascript:;\"><img src=\"/img/delete.png\" alt=\"0\" title=\"Eliminar\" /></a>&nbsp;<span style=\"display:none;\"><img src=\"/img/ajax-loader.gif\" alt=\"0\" /></span></span>";
			echo "   <br /><br />";
			echo "   </span>";
			$acti_count++;
		}
		
		echo "	 <span id=\"mas_actividades_span\"></span>";
		echo "</div>";
	?>
	</fieldset>
<?php echo $this->Form->end(__('Guardar', true));?>
</div>

<?php
	include("views/sidebars/menu.php");
?>