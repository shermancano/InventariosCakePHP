<script language="Javascript" type="text/javascript" src="/js/gastos/resumen.js"></script>

<div class="gastos form">
<?php echo $this->Form->create('GastoResumen', array('onsubmit' => 'return false;'));?>
	<fieldset>
		<legend><?php __(utf8_encode('Resumen de Gastos')); ?></legend>
	<?php
		echo $this->Form->input('cont_id', array('type' => 'hidden'));
		echo $this->Form->input('cont_nombre', array('type' => 'text', 'style' => 'width:40%;', 'label' => 'Ingrese el nombre del Contrato/Nombre del Proveedor/RUT del Proveedor'));
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
      <th colspan="2">ANTECEDENTES DEL CONTRATO</th>
   </tr>
   <tr>
      <td width="40%">Nombre de Adquisici&oacute;n/Contrato</td>
      <td id="cont_nombre">&nbsp;</td>
   </tr>
   <tr>
      <td>N&uacute;mero de Licitaci&oacute;n</td>
      <td id="cont_nro_licitacion">&nbsp;</td>
   </tr>
</table>

<table border="0" width="50%">
   <tr>
      <th colspan="2">ANTECEDENTES DE CONTROL DE PRESUPUESTO Y GASTOS</th>
   </tr>
   <tr>
      <td width="40%">Presupuesto del Contrato</td>
      <td id="cont_monto_compra">&nbsp;</td>
   </tr>
   <tr>
      <td>Fecha de Inicio de Contrato</td>
      <td id="cont_fecha_inicio">&nbsp;</td>
   </tr>
   <tr>
      <td>Fecha de T&eacute;rmino de Contrato</td>
      <td id="cont_fecha_termino">&nbsp;</td>
   </tr>
   <tr>
      <td>Fecha de Informe</td>
      <td id="cont_fecha_informe">&nbsp;</td>
   </tr>
</table>
<br />
<table border="0" width="50%" id="info_gastos">
	<tr>
    	<th>&nbsp;</th>
    </tr>
</table>
<br />
  <div class="submit">
     <input type="button" value="Exportar a Excel" class="excel_export" />
	 <input type="button" value="Exportar a PDF" class="pdf_export" />
  </div>  


</div>

<div id="info_mes_dialog" style="display:none;">
<table border="0" width="80%">
   <tr>
      <th colspan="2">Resumen</th>
   </tr>
   <tr>
      <td width="40%"><strong>Mes</strong></td>
      <td id="dialog_mes">&nbsp;</td>
   </tr>
   <tr>
      <td><strong>Gasto Fijo</strong></td>
      <td id="dialog_gasto_fijo">&nbsp;</td>
   </tr>
   <tr>
      <td><strong>Gasto Variable</strong></td>
      <td id="dialog_gasto_variable">&nbsp;</td>
   </tr>
   <tr>
      <td><strong>Gasto Presupuestado</strong></td>
      <td id="dialog_gasto_presupuestado">&nbsp;</td>
   </tr>
   <tr>
      <td><strong>Diferencia</strong></td>
      <td id="dialog_diferencia">&nbsp;</td>
   </tr>
   <tr>
      <td><strong>Gasto Acumulado a la fecha</strong></td>
      <td id="dialog_acumulado">&nbsp;</td>
   </tr>
   <tr>
      <td><strong>% Presupuesto utilizado</strong></td>
      <td id="porc_utilizado">&nbsp;</td>
   </tr>
   <tr>
      <td><strong>Difer. gasto acumulado - presupuestado acumulado</strong></td>
      <td id="dialog_diper">&nbsp;</td>
   </tr>
   <tr>
      <td><strong>Saldo por utilizar</strong></td>
      <td id="dialog_saldo_x_utilizar">&nbsp;</td>
   </tr>
</table>

<div id="tabla_facturas">
</div>

</div>

</div>

<?php
	include("views/sidebars/menu.php");
?>
