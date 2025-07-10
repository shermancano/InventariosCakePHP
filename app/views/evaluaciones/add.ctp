<script language="Javascript" type="text/javascript" src="/js/evaluaciones/add.js"></script>

<div class="evaluaciones form">
<?php echo $this->Form->create('Evaluacion', array('url' => '/evaluaciones/add', 'id' => 'evaluaciones_form'));?>
	<fieldset>
		<legend><?php __(utf8_encode('Añadir Evaluación')); ?></legend>
	<?php
	
		$options = "";
		for ($i=1.0; $i<=7.0; $i=$i+0.1) {
			$i = number_format($i, 1);
			if ($i == 4.0) {
				$options .= "<option selected=\"selected\" value=\"".$i."\">".$i."</option>\n";
			} else {
				$options .= "<option value=\"".$i."\">".$i."</option>\n";
			}
		}
	
		if (isset($cont_id) && isset($cont_nombre)) {
			echo $this->Form->input('cont_id', array('value' => $cont_id, 'name' => 'data[Contrato][cont_id]', 'type' => 'hidden'));
			echo $this->Form->input('cont_nombre', array('value' => $cont_nombre, 'name' => 'data[Contrato][cont_nombre]', 'style' => 'width:40%;', 'type' => 'text', 'label' => 'Ingrese el nombre del Contrato/Nombre del Proveedor/RUT del Proveedor'));
		} else {
			echo $this->Form->input('cont_id', array('name' => 'data[Contrato][cont_id]', 'type' => 'hidden'));
			echo $this->Form->input('cont_nombre', array('name' => 'data[Contrato][cont_nombre]', 'style' => 'width:40%;', 'type' => 'text', 'label' => 'Ingrese el nombre del Contrato/Nombre del Proveedor/RUT del Proveedor'));
		}
	?>
	
	<br />
	<p>* Para registrar la evaluaci&oacute;n del contrato, debe ingresar la ponderaci&oacute;n y notas de los items.
	Si el item no aplica, debe marcar la casilla para deshabilitarlo. Si el conjunto total de items no aplica, por favor ponderelo con un porcentaje igual a 0.</p>
	<br />
	<br />
	
	<table border="0" id="items_table">
		 <?php
		 	$count = 0;
		 	foreach ($tipos_item as $tipo_item) {
		 ?>
	     <tr>
	         <th colspan="2"><?php echo $tipo_item['TipoItem']['tiit_descripcion']; ?></th>
	         <th colspan="1">
	         	<input name="data[DetalleEvaluacion][<?php echo $count; ?>][tiit_id]" value="<?php echo $tipo_item['TipoItem']['tiit_id']; ?>" type="hidden" />
	         	<input name="data[DetalleEvaluacion][<?php echo $count; ?>][deev_ponderacion]" maxlength="3" type="text" style="width:35px" class="deev_ponderacion" />%
	         </th>
	     </tr>
	     <?php
	     		$count_items = 0;
	     		foreach ($tipo_item['Item'] as $item) {
	     ?>
	     <tr>
	         <td width="40%">
	         	<?php echo $item['item_descripcion']; ?>
	         </td>
	         <td width="10%">
	         	<input class="item_id" name="data[DetalleEvaluacion][<?php echo $count; ?>][Nota][<?php echo $count_items; ?>][item_id]" type="hidden" value="<?php echo $item['item_id']; ?>"/>
	            <select name="data[DetalleEvaluacion][<?php echo $count; ?>][Nota][<?php echo $count_items; ?>][nota_nota]">
	               <?php echo $options; ?>
	            </select>
	         </td>
	         <td width="10%">
	            <input class="item_aplica" type="checkbox" />
	         </td>
	     </tr>
	     <?php		$count_items++;
	     		}
	     		$count++;
	     	}
	     ?>
	   </table>
	   
	   <?php
	       echo $this->Form->input('eval_observaciones', array('style' => 'width:100%;', 'label' => 'Observaciones', 'name' => 'data[Evaluacion][eval_observaciones]'));
	   ?>
	   
	</fieldset>
	
	<div class="submit">
		<input type="submit" value="Guardar" />
	</div>
</form>
</div>
<?php
	include("views/sidebars/menu.php");
?>
