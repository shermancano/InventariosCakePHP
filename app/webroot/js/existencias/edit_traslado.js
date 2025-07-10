// JavaScript Document
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

function validchars(evt,allowedchars) {

	kcode=(evt.keyCode>0)?evt.keyCode:evt.charCode;
	if(allowedchars.indexOf(String.fromCharCode(kcode))==-1&&kcode!=8&&kcode!=9&&kcode!=13)
		return false;
	else 
		return true;
}

var num = "1234567890";

$(document).ready(function() {
	var ceco_id = $("#TrasladoExistenciaCecoId").val();
	
	$("#prod_nombre").autocomplete({
		source: "/detalles_existencias/searchStockOnly/"+ ceco_id,
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
	
	var format_date = function(date) {
		var n_date = date.split("-");
		return n_date[2] +"-"+ n_date[1] +"-"+ n_date[0];
	}
	
	var row_count = parseInt($("#TrasladoExistenciaSizeDetalleExistencia").val())+1;
	$("#nuevo_detalle").click(function() {
		var errors = false;
		var prod_id = $("#prod_id").val();
		var prod_nombre = $("#prod_nombre").val();
		
		if (prod_id == "") {
			showTipsy($("#prod_nombre"), 'Debe seleccionar un producto');
			errors = true;
		}
		
		if (prod_nombre == "") {
			showTipsy($("#prod_nombre"), 'Debe seleccionar un producto');
			errors = true;
		}
		
		if (errors) {
			return;
		}
		
		// vamos a buscar a las entradas
		var html_str = "";
		
		$.ajax({
			type: 'POST',
			url: '/detalles_existencias/searchAllByProd/'+ ceco_id +'/'+ prod_id,
			cache: false,
			dataType: 'json',
			beforeSend: function() {
				$("#ajax_loader").show();
			},
			success: function(data) {
				$.each(data.info, function(i, obj) {
					var class_ = obj.prod_id+obj.deex_serie+obj.deex_fecha_vencimiento+obj.deex_precio+obj.total;
					
					if (!checkRepetido(class_)) {
						html_str += "<tr class=\""+class_+"\">";
						html_str += "   <td>";
						html_str += "      <input type=\"hidden\" name=\"data[DetalleExistencia]["+ row_count +"][prod_id]\" value=\""+ obj.prod_id +"\" />";
						html_str += "      "+ obj.prod_nombre +" ";
						html_str += "   </td>";
						
						
						if (obj.deex_serie == null) {
							obj.deex_serie = "";
						}
						
						html_str += "   <td>";
						html_str += "      <input type=\"hidden\" name=\"data[DetalleExistencia]["+ row_count +"][deex_serie]\" value=\""+ obj.deex_serie +"\" />";
						html_str += "      "+ obj.deex_serie +" ";
						html_str += "   </td>";
						html_str += "   <td>";
						html_str += "      <input type=\"hidden\" name=\"data[DetalleExistencia]["+ row_count +"][deex_fecha_vencimiento]\" value=\""+ obj.deex_fecha_vencimiento +"\" />";
						html_str += "      "+ format_date(obj.deex_fecha_vencimiento) +" ";
						html_str += "   </td>";
						html_str += "   <td>";
						html_str += "      <input type=\"hidden\" name=\"data[DetalleExistencia]["+ row_count +"][deex_precio]\" value=\""+ obj.deex_precio +"\" />";
						html_str += "      "+ obj.deex_precio +" ";
						html_str += "   </td>";
						html_str += "   <td class=\"deex_total\">";
						html_str += "      "+ obj.total +" ";
						html_str += "   </td>";
						html_str += "   <td>";
						html_str += "      <input class=\"deex_cantidad\" maxlength=\"10\" name=\"data[DetalleExistencia]["+ row_count +"][deex_cantidad]\" value=\"0\" autocomplete=\"off\" onkeypress=\"return(validchars(event, num))\" />";
						html_str += "   </td>";
						html_str += "	<td>";
						html_str += "      <a href=\"javascript:;\" class=\"del_row\"><img src=\"/img/delete.png\" border=\"0\" title=\"Eliminar\" /></a>";
						html_str += "   </td>";
						html_str += "</tr>";
					
						row_count++;
					}
				});
			},
			complete: function() {
				$("#table_detalle").append(html_str);
				$("#table_detalle").show();
				$("#ajax_loader").hide();
				table_detalle_tipsy();
				limpia_form();
				del_row();
			}
		});
	});
	
	var checkRepetido = function(id) {
		var ret = false;
		
		$("#table_detalle tr").each(function() {
			var id_ = $(this).attr("class");
			
			if (id_ != undefined) {
				if (id_ == id) {
					ret = true;
				}
			}
		});
		
		if (ret) {
			return true;
		} else {
			return false;
		}
	}
	
	var del_row = function() {
		$(".del_row").each(function() {
			$(this).click(function() {
				var c = confirm("La acci\u00f3n eliminar\u00e1 el item seleccionado, est\u00e1 seguro de continuar?");
				var t = $(this);
				
				if (c) {
					var deex_id = $(this).parent().find(".deex_id").val();
					
					if (deex_id == undefined) {
						$(this).parent().parent().remove();
					} else {
						$.ajax({
							type: "POST",
							url: "/detalles_existencias/delete_ajax/"+ deex_id,
							async: false,
							cache: false,
							success: function(data) {
								if (data == "err") {
									showTipsy(t, "Error al eliminar el detalle");
								} else {
									$(t).parent().parent().remove();
								}
							}
						});
					}
				}
			});
		});
	}
	
	var limpia_form = function() {
		$("#detalle_form input[type='text']").each(function() {
			$(this).val("");
		});
	}
	
	$("#detalle_form input").each(function() {
		$(this).focus(function() {;
			hideTipsy($(this));
		});
	});
	
	var table_detalle_tipsy = function() {
		$("#table_detalle input").each(function() {
			$(this).focus(function() {
				hideTipsy($(this));
			});
		});
	}
	
	$("#form_submit").click(function() {
		var errors = false;
		
		$("#table_detalle tr").each(function() {
			var deex_total = parseInt($(this).find(".deex_total").html());
			var deex_cantidad = $(this).find(".deex_cantidad").val();
			
			if (!isNaN(deex_total)) {
				if ((!isNaN(deex_cantidad) && !isNaN(deex_total)) && (deex_cantidad > deex_total)) {
					showTipsy($(this).find(".deex_cantidad"), 'La cantidad a transferir debe ser menor o igual al total');
					errors = true;
				}
			}
		});
		
		if (errors == false) {
			$('#FormTraslado').submit();
		}
	});
	
	del_row();
});