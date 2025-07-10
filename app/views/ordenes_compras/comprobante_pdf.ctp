<html>
	<title>&nbsp;</title>
<body>
<table border="0" width="165%">
	<tr>
		<td width="88%"><img src="http://<?php echo $_SERVER['HTTP_HOST']?>/files/logo.png" /></td>
        <td valign="bottom" width="87%"><?php echo date("d-m-Y H:i:s");?></td>
	</tr>
</table>
<table border="0" width="100%">
	<tr>
		<td align="center" width="100%"><font size="5"><strong>ORDEN DE COMPRA N&deg; <?php echo $info['OrdenCompra']['orco_numero'];?></strong></font></td>
	</tr>
</table>
<table border="1" width="100%" rules="none">
	<tr>
    	<td width="15%"><strong>SE&Ntilde;OR (ES)</strong></td>
        <td width="40%">: <?php echo utf8_decode($info['Proveedor']['prov_nombre']);?></td>
        <td width="12%"><strong>A SR (A)</strong></td>
        <td width="38%">: <?php echo utf8_decode($info['Proveedor']['prov_contacto']);?></td>
    </tr>
    <tr>
    	<td width="15%"><strong>DIRECCI&Oacute;N</strong></td>
        <td width="35%">: <?php echo utf8_decode($info['Proveedor']['prov_direccion']);?></td>
        <td width="12%"><strong>FONO</strong></td>
        <td width="38%">: <?php echo $info['Proveedor']['prov_telefono'];?></td>
    </tr>
    <tr>
    	<td width="15%"><strong>RUT</strong></td>
        <td width="35%">: <?php echo $info['Proveedor']['prov_rut'];?></td>
        <td width="12%"><strong>EMAIL</strong></td>
        <td width="38%">: <?php echo $info['Proveedor']['prov_email'];?></td>
    </tr>
</table>
<table border="1" width="100%" rules="none">
	<tr>
    	<td width="35%"><strong>NOMBRE ORDEN DE COMPRA</strong></td> 
        <td width="65%">: <?php echo utf8_decode($info['OrdenCompra']['orco_nombre']);?></td>
    </tr>
    <tr>
    	<td width="35%"><strong>FECHA ENTREGA DE PRODUCTOS</strong></td>
        <td width="65%">: <?php echo date('d-m-Y', strtotime($info['OrdenCompra']['orco_fecha_entrega']));?></td>
    </tr>
    <tr>
    	<td width="35%"><strong>DIRECCI&Oacute;N ENV&Iacute;O FACTURA</strong></td>
        <td width="65%">: <?php echo $info['OrdenCompra']['orco_direccion_factura'];?></td>
    </tr>
    <tr>
    	<td width="35%"><strong>METODO DE DESPACHO</strong></td>
        <td width="65%">: <?php echo utf8_decode($info['MetodoDespacho']['mede_descripcion']);?></td>
    </tr>
    <tr>
    	<td width="35%"><strong>FORMA DE PAGO</strong></td>
        <td width="65%">: <?php echo utf8_decode($info['FormaPago']['fopa_descripcion']);?></td>
    </tr>
    <tr>
    	<td width="35%"><strong>EMITIDA POR</strong></td>
        <td width="65%">: <?php echo $info['OrdenCompra']['orco_responsable'];?></td>
    </tr>
</table>
<table border="2" width="100%" rules="rows">
	<tr>
    	<td><strong>C&oacute;digo</strong></td>
        <td><strong>Producto</strong></td>
        <td><strong>Unidad</strong></td>
        <td><strong>Especificaciones Comprador</strong></td>
        <td><strong>Especificaciones Proveedor</strong></td>
        <td><strong>Cantidad</strong></td>
        <td><strong>Precio Unitario</strong></td>
        <td><strong>Descuento</strong></td>
        <td><strong>Cargos</strong></td>
        <td><strong>Valor Total</strong></td>
    </tr>
    <?php
		$sum_total_neto = 0;
		$sum_descuentos = 0;
		$sum_cargos = 0;
    	foreach($info_deoc as $row) {
	?>
    <tr>
    	<td><?php echo $row['Producto']['prod_codigo'];?></td>
        <td><?php 
				if (isset($row['Producto']['prod_nombre'])) {
					echo utf8_decode($row['Producto']['prod_nombre']);
				} else {
					echo utf8_decode($row['DetalleOrdenCompra']['deor_nombre']);	
				}
		?></td>
        <td><?php echo $row['Unidad']['unid_nombre'];?></td>
        <td><?php echo utf8_decode($row['DetalleOrdenCompra']['deor_especifi_comprador']);?></td>
        <td><?php echo utf8_decode($row['DetalleOrdenCompra']['deor_especifi_proveedor']);?></td>
        <td><?php echo $row['DetalleOrdenCompra']['deor_cantidad'];?></td>
        <td align="right">$<?php echo number_format($row['DetalleOrdenCompra']['deor_precio'], 0, ',', '.');?></td>
        <td align="right"><?php
				if ($row['DetalleOrdenCompra']['deor_descuento'] != "") {
					echo $row['DetalleOrdenCompra']['deor_descuento']."%";
				} else {
					echo 0;
				}
		
		?></td>
        <td align="right">$<?php
				if ($row['DetalleOrdenCompra']['deor_descuento'] != "") {
					echo $row['DetalleOrdenCompra']['deor_cargos'];
				} else {
					echo 0;
				}
		?></td>
        <td align="right">
		<?php
				
			$valor_total = $row['DetalleOrdenCompra']['deor_cantidad']*$row['DetalleOrdenCompra']['deor_precio'];
			
			if ($row['DetalleOrdenCompra']['deor_descuento'] != "") {
				$descuento = ($row['DetalleOrdenCompra']['deor_cantidad']*$row['DetalleOrdenCompra']['deor_precio']*$row['DetalleOrdenCompra']['deor_descuento'])/100;
				$valor_total -= $descuento;
				$sum_descuentos += $descuento;
			}
			
			if ($row['DetalleOrdenCompra']['deor_cargos'] != "") {
				$cargo = $row['DetalleOrdenCompra']['deor_cargos'];
				$valor_total += $cargo;
				$sum_cargos += $cargo;
			}
			
			$sum_total_neto += $valor_total;
			echo "$".number_format($valor_total, 0, ',', '.');		
		?></td>
    </tr>
    <?php
			
		}
	?>
</table>
<table border="1" width="40%" align="right" rules="rows">
	<tr>
    	<td width="30%"><strong>Neto</strong></td>
        <td width="70%" align="right">$<?php echo number_format($sum_total_neto, 0, ',', '.');?></td>
    </tr>
    <tr>
    	<td width="30%"><strong>Descuento</strong></td>
        <td width="70%" align="right">$<?php echo number_format(round($sum_descuentos, 0), 0, ',', '.');?></td>
    </tr>
    <tr>
    	<td width="30%"><strong>Cargos</strong></td>
        <td width="70%" align="right">$<?php echo number_format(round($sum_cargos, 0), 0, ',', '.');?></td>
    </tr>
    <tr>
    	<td width="30%"><strong>SubTotal</strong></td>
        <td width="70%" align="right">$
		<?php
		$subtotal = $sum_total_neto + $sum_descuentos + $sum_cargos;
		echo number_format(round($subtotal, 0), 0, ',', '.');		
		?></td>
    </tr>
    <tr>
    	<td width="30%"><strong>IVA (19%)</strong></td>
        <td width="70%" align="right">$0</td>
    </tr>
    <tr>
    	<td width="30%"><strong>Total</strong></td>
        <td width="70%" align="right">$<?php echo number_format(round($subtotal, 0), 0, ",", "."); ?></td>
    </tr>
</table>
<table width="100%">
	<tr>
    	<td width="24%"><strong>Fuente de Financiamiento</strong></td>
        <td width="76%">: <?php echo utf8_decode($info['Financiamiento']['fina_nombre']);?></td>
    </tr>
    <tr>
    	<td width="24%"><strong>Observaciones</strong></td>
        <td width="76%">: <?php echo utf8_decode($info['OrdenCompra']['orco_observaciones']);?></td>
    </tr>
</table>
</html>
</body>
