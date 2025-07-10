// JavaScript Document
function number_format (number, decimals, dec_point, thousands_sep) {
	number = (number + '').replace(/[^0-9+\-Ee.]/g, '');
   	var n = !isFinite(+number) ? 0 : +number,
    prec = !isFinite(+decimals) ? 0 : Math.abs(decimals), sep = (typeof thousands_sep === 'undefined') ? ',' : thousands_sep,
    dec = (typeof dec_point === 'undefined') ? '.' : dec_point,
    s = '',
    toFixedFix = function (n, prec) {
    	var k = Math.pow(10, prec);
    	return '' + Math.round(n * k) / k;
    };
    // Fix for IE parseFloat(0.55).toFixed(0) = 0;
    s = (prec ? toFixedFix(n, prec) : '' + Math.round(n)).split('.');
        
    if (s[0].length > 3) {
    	s[0] = s[0].replace(/\B(?=(?:\d{3})+(?!\d))/g, sep);
    }
    
    if ((s[1] || '').length < prec) {
    	s[1] = s[1] || '';
    	s[1] += new Array(prec - s[1].length + 1).join('0');
    }
    return s.join(dec);
}

$(document).ready(function() {
	$("#GastoResumenContNombre").autocomplete({
	     source: "/gastos/searchContratos",
		 select: function (event, ui) {
		 	$("#GastoResumenContId").val(ui.item.cont_id)
		 }
	});
	
	$("#buscarResumen").click(function() {
		var cont_id = $("#GastoResumenContId").val();
		var not_found = false;
	
		if (cont_id != "") {
			$.ajax({
				type: "POST",
				url: "/gastos/getResumen/"+cont_id,
				cache: false,
				dataType: "json",
				beforeSend: function() {
					$("#ajax_loader").show();
					$("#info_resumen").slideUp("slow");
				},
				success: function(data) {
					if (data == null) {
						alert('No se encontraron gastos asociados para el contrato seleccionado.');
						not_found = true;
						return;
					}
				
					$("#info_gastos").html("");
					$("#cont_nombre").html(data.info_cont.cont_nombre);
					$("#cont_nro_licitacion").html(data.info_cont.cont_nro_licitacion);
					$("#cont_monto_compra").html(number_format(data.info_cont.cont_monto_compra, 0, "", ".")+" ("+data.info_cont.timo_descripcion+")");
					$("#cont_fecha_inicio").html(data.info_cont.cont_fecha_inicio);
					$("#cont_fecha_termino").html(data.info_cont.cont_fecha_termino);
					$("#cont_fecha_informe").html(data.info_cont.cont_fecha_informe);
					
					var td_html = "";
					var gasto_acumulado = 0;
					var acumulado_presup = 0;
					var suma_gasto_fijo = 0;
					var suma_gasto_variable = 0;
					var suma_gasto_presupuestado = 0;
					var suma_diferencia = 0;
					
					td_html += "<tr>";
					td_html += "   <th>Meses</th>";
					td_html += "   <th>Gasto Fijo</th>";
					td_html += "   <th>Gasto Variable</th>";
					td_html += "   <th>Gasto Presupuestado</th>";
					td_html += "   <th>Diferencia</th>";
					td_html += "</tr>";
					
					$.each(data.info_res, function(i, obj) {
						gasto_acumulado = gasto_acumulado+(parseInt(obj.total_gasto_fijo)+parseInt(obj.total_gasto_variable));
						acumulado_presup = acumulado_presup+parseInt(obj.total_gasto_presupuestado);
						suma_gasto_fijo = suma_gasto_fijo+parseInt(obj.total_gasto_fijo);
						suma_gasto_variable = suma_gasto_variable+parseInt(obj.total_gasto_variable);
						suma_gasto_presupuestado = suma_gasto_presupuestado+parseInt(obj.total_gasto_presupuestado);
						suma_diferencia = suma_diferencia+parseInt(obj.diferencia);  
						
						td_html += "<tr>";
						td_html += "   <td width=\"20%\" id=\"td_mes\">";
						td_html += "      <a rel=\""+obj.mes+" "+obj.anyo+"|"+gasto_acumulado+"|"+acumulado_presup+"|"+obj.gast_fecha+"\" class=\"detalle_mes\" href=\"javascript:;\">"+obj.mes+"</a>";
						td_html += "    </td>";
						td_html += "   <td id=\"td_total_gasto_fijo\">"+number_format(obj.total_gasto_fijo, 0, "", ".")+"</td>";
						td_html += "   <td id=\"td_total_gasto_variable\">"+number_format(obj.total_gasto_variable, 0, "", ".")+"</td>";
						td_html += "   <td id=\"td_total_gasto_presupuestado\">"+number_format(obj.total_gasto_presupuestado, 0, "", ".")+"</td>";
						td_html += "   <td id=\"td_diferencia\">"+number_format(obj.diferencia, 0, "", ".")+"</td>";
						td_html += "</tr>";
					});
					
					td_html += "<tr>";
					td_html += "   <th>Totales</th>";
					td_html += "   <th>"+number_format(suma_gasto_fijo, 0, "", ".")+"</th>";
					td_html += "   <th>"+number_format(suma_gasto_variable, 0, "", ".")+"</th>";
					td_html += "   <th>"+number_format(suma_gasto_presupuestado, 0, "", ".")+"</th>";
					td_html += "   <th>"+number_format(suma_diferencia, 0, "", ".")+"</th>";
					td_html += "</tr>";
					
					$("#info_gastos").append(td_html);
				},
				complete: function() {
					if (!not_found) {
						f_detalle_mes();
						f_excel_report();
						f_pdf_report();
						$("#info_resumen").slideDown("slow");
					}
					$("#ajax_loader").hide();
				},
				error: function() {
					alert('Error!');
					$("#ajax_loader").hide();
				}
			});
		}
	});
	
	var f_detalle_mes = function() {
		$(".detalle_mes").each(function() {
			var t = $(this);
			$(this).click(function() {
				var rel = $(this).attr("rel");
				var pieces = rel.split("|");
				var title = pieces[0];
				var gasto_acumulado = pieces[1];
				var acumulado_presupuestado = pieces[2];
				var gast_fecha = pieces[3];
				var cont_id = $("#GastoResumenContId").val();
				
				//busca las facturas x mes
				$.getJSON("/gastos/getFacturas/"+gast_fecha+"/"+cont_id, function(data) {
					if (data.length > 0) {
						var html_str = "<table border=\"0\" width=\"80%\">";
						html_str += "<tr>";
						html_str +=	"  <th colspan=\"2\">Facturas (n&uacute;mero y monto en pesos)</th>";
						html_str += "</tr>";
								
						$.each(data, function(i, obj) {
							if (obj.gast_nro_factura == null) obj.gast_nro_factura = "&nbsp;";
								html_str += "<tr>";
								html_str += "  <td width=\"40%\">"+obj.gast_nro_factura+"</td>";
								html_str += "  <td>"+number_format(obj.gast_monto_convertido, 0, "", ".")+"</td>";					
								html_str += "</tr>";		
						});
								
						html_str += "</table>";
						$("#tabla_facturas").html(html_str);
					}
				});
				
				$("#info_mes_dialog").dialog({
					title: "Resumen de Gastos - "+title,
					draggable: true,
					modal: true,
					width: 550,
					open: function() {
						var parent_tr = $(t).parent().parent();
						$("#tabla_facturas").html("");
						$("#dialog_mes").html($(parent_tr).find("#td_mes").find("a").html());
						$("#dialog_gasto_fijo").html($(parent_tr).find("#td_total_gasto_fijo").html());
						$("#dialog_gasto_variable").html($(parent_tr).find("#td_total_gasto_variable").html());
						$("#dialog_gasto_presupuestado").html($(parent_tr).find("#td_total_gasto_presupuestado").html());
						$("#dialog_diferencia").html($(parent_tr).find("#td_diferencia").html());
						$("#dialog_acumulado").html(number_format(gasto_acumulado, 0, "", "."));
						
						var cont_monto_compra = $("#cont_monto_compra").html();
							cont_monto_compra = parseInt(cont_monto_compra.replace(/\./g, ""));
						
						$("#porc_utilizado").html(number_format((gasto_acumulado*100)/cont_monto_compra, 2, ".", "") + "%");
						$("#dialog_saldo_x_utilizar").html(number_format(cont_monto_compra-gasto_acumulado, 0, "", "."));
						$("#dialog_diper").html(number_format((gasto_acumulado-acumulado_presupuestado), 0, "", "."));
					}
				});
			});
		});
	}
	
	var f_excel_report = function() {
		$(".excel_export").click(function() {
			var cont_id = $("#GastoResumenContId").val();
			if (cont_id != "") {
				location.href = "/gastos/excel/"+cont_id;
			}
		});
	}
	
	var f_pdf_report = function() {
		$(".pdf_export").click(function() {
			var cont_id = $("#GastoResumenContId").val();
			if (cont_id != "") {
				location.href = "/gastos/pdf/"+cont_id;
			}
		});
	}
});