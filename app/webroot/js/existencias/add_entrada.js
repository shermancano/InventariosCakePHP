function validchars(evt,allowedchars) {

	kcode=(evt.keyCode>0)?evt.keyCode:evt.charCode;
	if (allowedchars.indexOf(String.fromCharCode(kcode))==-1 && kcode!=8 && kcode!=9 && kcode!=13)
		return false;
	else 
		return true;
}
function conMayusculas(obj) {
	var _value = $(obj).val();
	$(obj).val(_value.toUpperCase());
}

var nums = "1234567890.";
var num  = "1234567890";

function number_format (number, decimals, dec_point, thousands_sep) {
    number = (number + '').replace(/[^0-9+\-Ee.]/g, '');
    var n = !isFinite(+number) ? 0 : +number,
        prec = !isFinite(+decimals) ? 0 : Math.abs(decimals), sep = (typeof thousands_sep === 'undefined') ? ',' : thousands_sep,
        dec = (typeof dec_point === 'undefined') ? '.' : dec_point,
        s = '',
        toFixedFix = function (n, prec) {
            var k = Math.pow(10, prec);            return '' + Math.round(n * k) / k;
        };
    // Fix for IE parseFloat(0.55).toFixed(0) = 0;
    s = (prec ? toFixedFix(n, prec) : '' + Math.round(n)).split('.');
    if (s[0].length > 3) {        s[0] = s[0].replace(/\B(?=(?:\d{3})+(?!\d))/g, sep);
    }
    if ((s[1] || '').length < prec) {
        s[1] = s[1] || '';
        s[1] += new Array(prec - s[1].length + 1).join('0');    }
    return s.join(dec);
}

$(document).ready(function() {

		$("#prod_nombre").autocomplete({
			source: "/productos/searchExistencias",
			select: function (event, ui) {
				$("#prod_id").val(ui.item.prod_id);
			}
		});
		
	var showTipsy = function(id, msg) {
		var options = {
			delayIn: 500,
			trigger: 'manual',
			gravity: 's',
			title: function() {
				return msg;
			}
		};
		$(id).tipsy(options);
		$(id).tipsy("show");
	}
	
	var hideTipsy = function(id) {
		$(id).tipsy("hide");
	}
	
	var row_count = 0;
	$("#nuevo_detalle").click(function() {
		var errors = false;
		var prod_id = $("#prod_id").val();
		var prod_nombre = $("#prod_nombre").val();
		var deex_precio = $("#deex_precio").val();
		var deex_cantidad = $("#deex_cantidad").val();
		var deex_serie = $("#deex_serie").val();
		var deex_fecha_vencimiento = $("#deex_fecha_vencimiento").val();
		
		if (prod_nombre == "") {
			showTipsy($("#prod_nombre"), 'Debe seleccionar un producto');
			errors = true;
		}
		
		if (deex_precio == "") {
			showTipsy($("#deex_precio"), 'Debe ingresar el precio');
			errors = true;
		}
		
		if (deex_cantidad == "") {
			showTipsy($("#deex_cantidad"), 'Debe ingresar la cantidad');
			errors = true;
		}
		
		if (deex_fecha_vencimiento == "") {
			showTipsy($("#deex_fecha_vencimiento"), 'Debe ingresar la fecha');
			errors = true;
		}
		
		if (isNaN(deex_precio)) {
			showTipsy($("#deex_precio"), 'Debe ser numerico');
			errors = true;
		}
		
		if (isNaN(deex_cantidad)) {
			showTipsy($("#deex_cantidad"), 'Debe ser numerico');
			html: true,
			errors = true;
		}
		
		if (deex_serie == "") {
			showTipsy($("#deex_serie"), 'Debe ingresar la serie');
			errors = true;
		}
		
		if (errors) {
			return;
		}
		
		var html_str  = "<tr>";
			html_str += "   <td>";
			html_str += "      <input name=\"data[DetalleExistencia]["+ row_count +"][prod_id]\" type=\"hidden\" value=\""+ prod_id +"\" />";
			html_str += "      "+ prod_nombre +" ";
			html_str += "   </td>";
			html_str += "   <td>";
			html_str += "      <input name=\"data[DetalleExistencia]["+ row_count +"][deex_cantidad]\" type=\"hidden\" value=\""+ deex_cantidad +"\" />";
			html_str += "      "+ deex_cantidad +" ";
			html_str += "   </td>";
			html_str += "   <td>";
			html_str += "      <input name=\"data[DetalleExistencia]["+ row_count +"][deex_precio]\" type=\"hidden\" value=\""+ deex_precio +"\" />";
			html_str += "      "+ deex_precio +" ";
			html_str += "   </td>";
			html_str += "   <td id=\"valor_neto\">";
			html_str += "      "+ Math.round(deex_precio*deex_cantidad) +" ";
			html_str += "   </td>";
			html_str += "   <td>";
			html_str += "      <input name=\"data[DetalleExistencia]["+ row_count +"][deex_fecha_vencimiento]\" type=\"hidden\" value=\""+ deex_fecha_vencimiento +"\" />";
			html_str += "      "+ deex_fecha_vencimiento +" ";
			html_str += "   </td>";
			html_str += "	<td>";
			html_str += "      <a href=\"javascript:;\"><img class=\"del_row\" src=\"/img/delete.png\" border=\"0\" title=\"Eliminar\" /></a>";
			html_str += "      <a href=\"javascript:;\"><img class=\"ver_detalles\" src=\"/img/magnifier.png\" border=\"0\" title=\"Ver Detalle\" /></a>";
			html_str += "      <input id=\"deex_serie\" name=\"data[DetalleExistencia]["+ row_count +"][deex_serie]\" type=\"hidden\" value=\""+ deex_serie +"\" />";
			html_str += "   </td>";
			html_str += "</tr>";
			
		$("#table_detalle").append(html_str);
		
		calculos_resumen();
		$("#table_detalle").show();
		$("#table_resumen").show();
		del_row();
		ver_detalles();
		limpia_form();
		row_count++;
	});
	
	$("#deex_fecha_vencimiento").datepicker({
		changeMonth: true,
		changeYear: true,
		dateFormat: "dd-mm-yy"		
	});
	
	var calculos_resumen = function() {
		// calculos de resumen
		var sum_valor_neto = 0;
		$("#table_detalle tr").each(function() {
			var valor_neto = parseInt($(this).find("#valor_neto").html());
			if (!isNaN(valor_neto)) {
				sum_valor_neto += valor_neto;
			}
		});
		
		$("#td_valor_neto").html("$"+ number_format(sum_valor_neto, 0, ",", "."));
		$("#td_iva").html("$"+ number_format(Math.round(0), 0, ",", "."));
		$("#td_total").html("$"+ number_format(Math.round(sum_valor_neto), 0, ",", "."));
	}
	
	var del_row = function() {
		$(".del_row").each(function() {
			$(this).click(function() {
				$(this).parent().parent().parent().remove();
				calculos_resumen();
			});
		});
	}
	
	var ver_detalles = function() {
		$(".ver_detalles").each(function() {
			$(this).click(function() {
				var prod_serie = $(this).parent().parent().parent().find("#deex_serie").val();
				$("#prod_serie").html(prod_serie);
				
				$("#prod_details").dialog({
					modal: true,
					title: "Ver detalles"
				});
			});
		});
	}
	
	var limpia_form = function() {
		$(".detalle_form input[type='text']").each(function() {
			$(this).val("");
		});
	}
	
	$(".detalle_form input, select").each(function() {
		$(this).focus(function() {
			hideTipsy($(this));
		});
	});
	
	$("#form_submit").click(function() {
		$('#FormExistencia').submit();
	});
	
	$(".checkMayuscula").each(function() {
		$(this).change(function() {
			conMayusculas($(this));
		});
	});
});