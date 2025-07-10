<script language="Javascript" type="text/javascript" src="/js/evaluaciones/resumen.js"></script>

<div class="resumen form">
<?php echo $this->Form->create('EvaluacionResumen', array('onsubmit' => 'return false;'));?>
	<fieldset>
		<legend><?php __(utf8_encode('Resumen de Evaluación')); ?></legend>
	<?php
		echo $this->Form->input('eval_id', array('type' => 'hidden'));
		echo $this->Form->input('cont_nombre', array('type' => 'text', 'style' => 'width:40%;', 'label' => 'Ingrese el nombre del Contrato/Nombre del Proveedor/RUT del Proveedor'));
	?>
	<div class="submit">
		<input id="buscarResumen" type="submit" value="Buscar" />
		<span id="ajax_loader" style="display:none;">&nbsp;&nbsp;<img src="/img/ajax-loader.gif" border="0" alt="0" /></span>
	</div>
	</fieldset>
</form>
<br />

<div id="resumen_eval" style="display:none;">
   <table>
      <tr>
          <td width="30%"><strong>Nombre del Proveedor:</strong></td>
          <td id="prov_nombre">&nbsp;</td>
      </tr>
      <tr>
          <td><strong>Nombre del Contrato:</strong></td>
          <td id="cont_nombre">&nbsp;</td>
      </tr>
   </table>
   <br />
   
   <div id="resumen_eval_table">
   </div>
  
   <br />                                
 
     <div class="submit">
        <input type="button" class="excel_export" value="Exportar a Excel" />
		<input type="button" class="pdf_export" value="Exportar a PDF" />
     </div>  
</div>

</div>

<?php
	include("views/sidebars/menu.php");
?>
