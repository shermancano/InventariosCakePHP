<html>
	<head>
		<title>&nbsp;</title>
	</head>
<body>

<table border="1" width="100%">
   <tr>
      <th colspan="2">ANTECEDENTES DEL CONTRATO</th>
   </tr>
   <tr>
      <td width="40%">Nombre de Adquisici&oacute;n/Contrato</td>
      <td id="cont_nombre"><?php echo utf8_decode($info_res->info_cont->cont_nombre); ?></td>
   </tr>
   <tr>
      <td>N&uacute;mero de Licitaci&oacute;n</td>
      <td id="cont_nro_adquisicion"><?php echo $info_res->info_cont->cont_nro_licitacion; ?></td>
   </tr>
</table>
<br />

<table border="1" width="100%">
   <tr>
      <th colspan="2">ANTECEDENTES DE CONTROL DE PRESUPUESTO Y GASTOS</th>
   </tr>
   <tr>
      <td width="40%">Presupuesto del Contrato</td>
      <td id="cont_monto_compra"><?php echo number_format($info_res->info_cont->cont_monto_compra, 0, "", "."); ?> (<?php echo $info_res->info_cont->timo_descripcion; ?>)</td>
   </tr>
   <tr>
      <td>Fecha de Inicio de Contrato</td>
      <td id="cont_fecha_inicio"><?php echo $info_res->info_cont->cont_fecha_inicio; ?></td>
   </tr>
   <tr>
      <td>Fecha de T&eacute;rmino de Contrato</td>
      <td id="cont_fecha_termino"><?php echo $info_res->info_cont->cont_fecha_termino; ?></td>
   </tr>
   <tr>
      <td>Fecha de Informe</td>
      <td id="cont_fecha_informe"><?php echo $info_res->info_cont->cont_fecha_informe; ?></td>
   </tr>
</table>
<br />

<table border="1" width="100%" id="info_gastos">
   <tr>
      <th>Meses</th>
      <th>Gasto Fijo</th>
      <th>Gasto Variable</th>
      <th>Gasto Presupuestado</th>
      <th>Diferencia</th>
   </tr>       
   <?php
   	   $suma_gasto_fijo = $suma_gasto_variable = $suma_gasto_presupuestado = $suma_diferencia = 0;
   
       foreach ($info_res->info_res as $info) {
           $suma_gasto_fijo = $suma_gasto_fijo+$info->total_gasto_fijo;
           $suma_gasto_variable = $suma_gasto_variable+$info->total_gasto_variable;
           $suma_gasto_presupuestado = $suma_gasto_presupuestado+$info->total_gasto_presupuestado;
           $suma_diferencia = $suma_diferencia+$info->diferencia;  
   ?>
   <tr>   
      <td width="20%"><?php echo $info->mes; ?></td>
      <td><?php echo number_format($info->total_gasto_fijo, 0, "", "."); ?></td>
      <td><?php echo number_format($info->total_gasto_variable, 0, "", ".");; ?></td>
      <td><?php echo number_format($info->total_gasto_presupuestado, 0, "", ".");; ?></td>
      <td><?php echo number_format($info->diferencia, 0, "", ".");; ?></td>
   </tr>
   
   <?php
       }
   ?>
   <tr>   
      <td width="20%">Totales</td>
      <td><strong><?php echo number_format($suma_gasto_fijo, 0, "", "."); ?></strong></td>
      <td><strong><?php echo number_format($suma_gasto_variable, 0, "", ".");; ?></strong></td>
      <td><strong><?php echo number_format($suma_gasto_presupuestado, 0, "", ".");; ?></strong></td>
      <td><strong><?php echo number_format($suma_diferencia, 0, "", ".");; ?></strong></td>
   </tr>
</table>

<br />

</body>
</html>

