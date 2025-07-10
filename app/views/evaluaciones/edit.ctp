<script language="Javascript" type="text/javascript" src="/js/evaluaciones/edit.js"></script>

<div class="evaluaciones form">
<?php echo $this->Form->create('Evaluacion', array('url' => '/evaluaciones/edit', 'id' => 'evaluaciones_form'));?>
	<fieldset>
		<legend><?php __(utf8_encode('Editar Evaluación')); ?></legend>
	<?php
		
		echo $this->Form->input('Evaluacion.eval_id', array('value' => $eval_id, 'type' => 'hidden'));
		echo $this->Form->input('cont_id', array('name' => 'data[Contrato][cont_id]', 'type' => 'hidden', 'value' => $this->data['Contrato']['cont_id']));
		echo $this->Form->input('cont_nombre', array('readonly' => 'readonly', 'name' => 'data[Contrato][cont_nombre]', 'value' => $this->data['Contrato']['cont_nombre'], 'style' => 'width:40%;', 'type' => 'text', 'label' => 'Nombre del Contrato'));
	
	?>
	
	<br />
	<p>* Para registrar la evaluaci&oacute;n del contrato, debe ingresar la ponderaci&oacute;n y notas de los items.
	Si el item no aplica, debe marcar la casilla para deshabilitarlo. Si el conjunto total de items no aplica, por favor ponderelo con un porcentaje igual a 0.</p>
	<br />
	<br />

	<?php
		echo $this->Form->input('cont_nro_licitacion', array('value' => $this->data['Contrato']['cont_nro_licitacion'], 'readonly' => 'readonly', 'style' => 'width:40%;', 'type' => 'text', 'label' => utf8_encode('Número de licitación')));
		echo $this->Form->input('Contrato.Proveedor.prov_nombre', array('readonly' => 'readonly', 'style' => 'width:40%;', 'label' => 'Proveedor'));
	?>
	
	<table border="0" id="items_table">
		 <?php
		 	$count = 0;
		 	foreach ($edit_info['DetalleEvaluacion'] as $info) {
		 ?>
	     <tr>
	         <th colspan="2"><?php echo $info['tiit_descripcion']; ?></th>
	         <th colspan="1">
	         	<input name="data[DetalleEvaluacion][<?php echo $count; ?>][deev_id]" value="<?php echo $info['deev_id']; ?>" type="hidden" />
	         	<input name="data[DetalleEvaluacion][<?php echo $count; ?>][tiit_id]" value="<?php echo $info['tiit_id']; ?>" type="hidden" />
	         	<input name="data[DetalleEvaluacion][<?php echo $count; ?>][deev_ponderacion]" value="<?php echo $info['deev_ponderacion']; ?>" maxlength="3" type="text" style="width:35px" class="deev_ponderacion" />%
	         </th>
	     </tr>
	     <?php
	     		$count_items = 0;
	     		foreach ($info['Nota'] as $nota) {
	     ?>
	     <tr>
	         <td width="40%">
	         	<?php echo $nota['item_descripcion']; ?>
	         </td>
	         <td width="10%">
	            <input type="hidden" name="data[DetalleEvaluacion][<?php echo $count; ?>][Nota][<?php echo $count_items; ?>][nota_id]" value="<?php echo $nota['nota_id']; ?>" />
	         	<input class="item_id" name="data[DetalleEvaluacion][<?php echo $count; ?>][Nota][<?php echo $count_items; ?>][item_id]" type="hidden" value="<?php echo $nota['item_id']; ?>"/>
	         	<select <?php if ($nota['nota_nota'] == "") { echo " disabled=\"disabled\" "; } ?> name="data[DetalleEvaluacion][<?php echo $count; ?>][Nota][<?php echo $count_items; ?>][nota_nota]">
	               <?php 
	                   for ($i=1.0; $i<=7.0; $i=$i+0.1) {
	                       $i = number_format($i, 1);
	                       
	                       if ($nota['nota_nota'] == "" || $nota['nota_nota'] == null) {
	                           $nota_ = "4.0";
	                       } else {
	                           $nota_ = $nota['nota_nota'];
	                       }  
	                       
	                       if ($i == $nota_) {
	                           echo "<option selected=\"selected\" value=\"".$i."\">".$i."</option>\n";
	                       } else {
	                           echo "<option value=\"".$i."\">".$i."</option>\n";
	                       }
	                   }
	               ?>
	            </select>
	         </td>
	         <td width="10%">
	         	<?php
	         		if (trim($nota['nota_nota']) != "") {
	         	?>
	            	<input class="item_aplica" type="checkbox" />
	            <?php
	            	} else {
	            ?>
	            	<input checked="checked" class="item_aplica" type="checkbox" />
	            <?php
	            	}
	            ?>
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
	   
	   <?php
	       echo $this->Form->input('nota_final', array('readonly' => 'readonly', 'value' => number_format(round($nota_final,1), 1, ",", ""), 'style' => 'width:20%;', 'label' => utf8_encode('Evaluación Final')));
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
