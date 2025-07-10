<script language="Javascript" type="text/javascript" src="/js/etapas/resumen.js"></script>

<div class="gastos form">
<?php echo $this->Form->create('EtapaResumen', array('onsubmit' => 'return false;'));?>
	<fieldset>
		<legend><?php __('Resumen de Monitoreo'); ?></legend>
	<?php
		echo $this->Form->input('cont_id', array('type' => 'hidden'));
		echo $this->Form->input('cont_nombre', array('style' => 'width:40%;', 'type' => 'text', 'label' => 'Ingrese el nombre del Contrato/Nombre del Proveedor/RUT del Proveedor'));
	?>
	<div class="submit">
		<input id="buscarResumen" type="submit" value="Buscar" />
		<span id="ajax_loader" style="display:none;">&nbsp;&nbsp;<img src="/img/ajax-loader.gif" border="0" alt="0" /></span>
	</div>
	</fieldset>
</form>
<br />

<div id="info_resumen" style="display:none;">
<table border="0" width="50%">
   <tr>
      <th colspan="2">MONITOREO DE EJECUCI&Oacute;N DE CONTRATOS</th>
   </tr>
   <tr>
      <td width="40%">Nombre del Proveedor</td>
      <td id="prov_razon_social">&nbsp;</td>
   </tr>
   <tr>
      <td width="40%">Nombre de Adquisici&oacute;n/Contrato</td>
      <td id="cont_nombre">&nbsp;</td>
   </tr>
</table>
<br />

<table border="0" width="50%" id="resumen_table">
	<tr>
    	<td>&nbsp;</td>
    </tr>
</table>

<br />

  <div class="submit">
     <input type="button" value="Exportar a Excel" class="excel_export" />
	 <input type="button" value="Exportar a PDF" class="pdf_export" />
  </div>  

</div>

</div>


<?php
	include("views/sidebars/menu.php");
?>
