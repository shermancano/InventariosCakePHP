// JavaScript Document
function validchars(evt,allowedchars) {

	kcode=(evt.keyCode>0)?evt.keyCode:evt.charCode;
	if(allowedchars.indexOf(String.fromCharCode(kcode))==-1&&kcode!=8&&kcode!=9&&kcode!=13)
		return false;
	else 
		return true;
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

var valida_archivo = function() {
	// Variables para la validación del archivo upload
	var archivo = $("#ActivoFijoDocumentoAcfdContenido");
	var file = archivo[0].files[0];
	var ctype = (file.type == "") ? 'n/a': file.type;
	var bytes = file.size;
	var extensiones_permitidas = new Array(".jpg", ".jpeg", ".png", ".gif");
	var extension = (ctype.substring(ctype.lastIndexOf("/"))).toLowerCase();
	extension = extension.replace("/",".");
	
	// Compruebamos si la extensión está entre las permitidas
	var permitida = false;
	for (var i = 0; i < extensiones_permitidas.length; i++) {
		if (extensiones_permitidas[i] == extension) {
			permitida = true;
			break;
		}
	} 
	
	if (!permitida) {
		 alert("Compruebe la extensión de los archivos a subir. \nSólo se pueden subir archivos con extensiones: " + extensiones_permitidas.join());
		 $("#ActivoFijoDocumentoAcfdContenido").val("");
		 return false;
	}
	
	if (bytes >= (1024*100)) {
		alert('Compruebe el tamaño del archivo a subir. No puede superar los 100KB, debe disminuir la resolución de la fotografía.');
		$("#ActivoFijoDocumentoAcfdContenido").val("");
		return false;
	}
}


$(document).ready(function() {
	$("#ActivoFijoDocumentoAcfdContenido").change(function() {
		valida_archivo();
	});
	
	$(".prod_nombre").autocomplete({
		source: "/productos/searchActivosFijos",
		select: function (event, ui) {
			$(".prod_id").val(ui.item.prod_id);
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
	
	var row_count = parseInt($("#ActivoFijoDeafSize").val())+1;
	
	$("#nuevo_detalle").click(function() {
		var errors = false;
		var prod_id = $(".prod_id").val();
		var prod_nombre = $(".prod_nombre").val();
		var marc_id = $(".marc_id").val();
		var prop_id = $(".prop_id").val();
		var situ_id = $(".situ_id").val();
		var colo_id = $(".colo_id").val();
		var mode_id = $(".mode_id").val();
		var deaf_serie = $(".deaf_serie").val();
		var deaf_precio = $(".deaf_precio").val();
		var deaf_cantidad = $(".deaf_cantidad").val();
		var deaf_depreciable = $(".deaf_depreciable").attr("checked") ? 1:0;
		var deaf_vida_util = $(".deaf_vida_util").val();
		var deaf_fecha_adquisicion = $(".deaf_fecha_adquisicion").val();
		var deaf_fecha_garantia = $(".deaf_fecha_garantia").val();
		
		var prop_nombre = $(".prop_id :selected").text();
		var situ_nombre = $(".situ_id :selected").text();
		var marc_nombre = $(".marc_id :selected").text();
		var colo_nombre = $(".colo_id :selected").text();
		var mode_nombre = $(".mode_id :selected").text();
		
		//escondemos los tipsy
		hideTipsy($(".deaf_vida_util"));
		
		if (prod_nombre == "") {
			showTipsy($(".prod_nombre"), 'Debe seleccionar un producto');
			errors = true;
		}
		
		if (prop_id == "") {
			showTipsy($(".prop_id"), 'Debe seleccionar la propiedad del bien');
			errors = true;
		}
		
		if (situ_id == "") {
			showTipsy($(".situ_id"), 'Debe seleccionar la situaci\u00f3n del bien');
			errors = true;
		}
		
		//if (marc_id == "") {
			//showTipsy($(".marc_id"), 'Debe seleccionar la marca del bien');		
			//errors = true;
		//}
		
		if (colo_id == "") {
			showTipsy($(".colo_id"), 'Debe seleccionar el color del bien');
			errors = true;
		}
		
		if (deaf_precio == "") {
			showTipsy($(".deaf_precio"), 'Debe ingresar el precio del activo fijo');
			errors = true;
		}
		
		if (deaf_cantidad == "") {
			showTipsy($(".deaf_cantidad"), 'Debe ingresar la cantidad total de activos fijos');
			errors = true;
		}
		
		if (deaf_depreciable == 1) {			
			if (deaf_vida_util == "") {
				showTipsy($(".deaf_vida_util"), 'Si el(los) items(es) son depreciable(s), debe ingresar la vida \u00fatil');
				errors = true;
			}
		}
		
		if (deaf_fecha_adquisicion == "") {
			showTipsy($(".deaf_fecha_adquisicion"), 'Debe seleccionar la fecha de adquisici\u00f3n');
			errors = true;
		}
		
		if (errors) {
			return;
		}
		
		for (i=0; i<deaf_cantidad; i++) {
			var html_str  = "<tr>";
				html_str += "   <td>";
				html_str += "      <label>-- Por asignar --</label>";
				html_str += "   </td>";
				html_str += "   <td>";
				html_str += "      <input name=\"data[DetalleActivoFijo]["+ row_count +"][prod_id]\" type=\"hidden\" value=\""+ prod_id +"\" />";
				html_str += "      "+ prod_nombre +" ";
				html_str += "   </td>";
				html_str += "   <td>";
				html_str += "      <input name=\"data[DetalleActivoFijo]["+ row_count +"][deaf_precio]\" type=\"hidden\" value=\""+ deaf_precio +"\" />";
				html_str += "      "+ deaf_precio +" ";
				html_str += "   </td>";
				html_str += "   <td>";
				
				if (deaf_depreciable == 1)	{
					html_str += "Si";
				} else {
					html_str += "No";
				}
				
				html_str += "   </td>";
				html_str += "	<td>";
				html_str += "      <a href=\"javascript:;\"><img class=\"del_row\" src=\"/img/delete.png\" border=\"0\" title=\"Eliminar\" alt=\"0\" /></a>";
				html_str += "      <a href=\"javascript:;\"><img class=\"ver_detalles\" src=\"/img/magnifier.png\" border=\"0\" title=\"Ver Detalle\" alt=\"0\" /></a>";
				html_str += "      <input class=\"valor_neto\" type=\"hidden\" value=\""+ deaf_precio +"\" />";
				html_str += "      <input name=\"data[DetalleActivoFijo]["+ row_count +"][prop_id]\" type=\"hidden\" value=\""+ prop_id +"\" />";
				html_str += "      <input class=\"prop_nombre\" type=\"hidden\" value=\""+ prop_nombre +"\" />";
				html_str += "      <input name=\"data[DetalleActivoFijo]["+ row_count +"][situ_id]\" type=\"hidden\" value=\""+ situ_id +"\" />";
				html_str += "      <input class=\"situ_nombre\" type=\"hidden\" value=\""+ situ_nombre +"\" />";
				html_str += "      <input name=\"data[DetalleActivoFijo]["+ row_count +"][marc_id]\" type=\"hidden\" value=\""+ marc_id +"\" />";
				html_str += "      <input class=\"marc_nombre\" type=\"hidden\" value=\""+ marc_nombre +"\" />";
				html_str += "      <input name=\"data[DetalleActivoFijo]["+ row_count +"][colo_id]\" type=\"hidden\" value=\""+ colo_id +"\" />";
				html_str += "      <input class=\"colo_nombre\" type=\"hidden\" value=\""+ colo_nombre +"\" />";
				html_str += "      <input name=\"data[DetalleActivoFijo]["+ row_count +"][mode_id]\" type=\"hidden\" value=\""+ mode_id +"\" />";
				html_str += "      <input class=\"mode_nombre\" type=\"hidden\" value=\""+ mode_nombre +"\" />";
				html_str += "      <input name=\"data[DetalleActivoFijo]["+ row_count +"][deaf_serie]\" class=\"deaf_serie\" type=\"hidden\" value=\""+ deaf_serie +"\" />";
				html_str += "      <input name=\"data[DetalleActivoFijo]["+ row_count +"][deaf_fecha_garantia]\" class=\"deaf_fecha_garantia\" type=\"hidden\" value=\""+ deaf_fecha_garantia +"\" />";
				html_str += "      <input name=\"data[DetalleActivoFijo]["+ row_count +"][deaf_depreciable]\" class=\"deaf_depreciable\" type=\"hidden\" value=\""+ deaf_depreciable +"\" />";
				html_str += "      <input name=\"data[DetalleActivoFijo]["+ row_count +"][deaf_vida_util]\" class=\"deaf_vida_util\" type=\"hidden\" value=\""+ deaf_vida_util +"\" />";
				html_str += "      <input name=\"data[DetalleActivoFijo]["+ row_count +"][deaf_fecha_adquisicion]\" class=\"deaf_fecha_adquisicion\" type=\"hidden\" value=\""+ deaf_fecha_adquisicion +"\" />";
				html_str += "   </td>";  
				html_str += "</tr>";
				
			$("#table_detalle").append(html_str);
			row_count++;
		}
		
		ver_detalles();
		del_row();
		limpia_form();
		calculos_resumen();
	});
	
	var calculos_resumen = function() {
		// calculos de resumen
		var sum_valor_neto = 0;
		$("#table_detalle tr").each(function() {
			var valor_neto = parseInt($(this).find(".valor_neto").val());
			
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
				var c = confirm('La acci\u00f3n eliminar\u00e1 el detalle seleccionado (la cantidad se rebajar\u00e1 automaticamente del stock utilizado para realizar traslados). Est\u00e1 seguro que desea continuar?');
				
				if (c) {
					// ajax elimina fila de detalle
					var success = true;
					var parent = $(this).parent();
					var deaf_id = $(parent).attr("rel");
					
					if (!isNaN(deaf_id)) {
						$.ajax({
							type: "POST",
							url: "/detalles_activos_fijos/delete_ajax/"+ deaf_id,
							cache: false,
							async: false,
							success: function(data) {
								if (data == "err") {
									success = false;
									showTipsy($(parent), 'No se pudo eliminar el detalle');
								}
							}
						});
					}
					
					if (success) {
						$(this).parent().parent().parent().remove();
						calculos_resumen();
					} 
				}
			});
		});
	}
	
	var ver_detalles = function() {
		$(".ver_detalles").each(function() {
			$(this).click(function() {
				var prop_nombre = $(this).parent().parent().parent().find(".prop_nombre").val();
				$("#dialog_prop_nombre").html(prop_nombre);
				var situ_nombre = $(this).parent().parent().parent().find(".situ_nombre").val();
				$("#dialog_situ_nombre").html(situ_nombre);
				var marc_nombre = $(this).parent().parent().parent().find(".marc_nombre").val();
				$("#dialog_marc_nombre").html(marc_nombre);
				var colo_nombre = $(this).parent().parent().parent().find(".colo_nombre").val();
				$("#dialog_colo_nombre").html(colo_nombre);
				var mode_nombre = $(this).parent().parent().parent().find(".mode_nombre").val();
				$("#dialog_mode_nombre").html(mode_nombre);
				var deaf_serie = $(this).parent().parent().parent().find(".deaf_serie").val();
				$("#dialog_deaf_serie").html(deaf_serie);
				var deaf_fecha_garantia = $(this).parent().parent().parent().find(".deaf_fecha_garantia").val();
				$("#dialog_deaf_fecha_garantia").html(deaf_fecha_garantia);
				var deaf_depreciable = $(this).parent().parent().parent().find(".deaf_depreciable").val();
				
				if (deaf_depreciable == 1) {
					deaf_depreciable = "Si";
				} else {
					deaf_depreciable = "No";
				}
				
				$("#dialog_deaf_depreciable").html(deaf_depreciable);
				var deaf_vida_util = $(this).parent().parent().parent().find(".deaf_vida_util").val();
				$("#dialog_deaf_vida_util").html(deaf_vida_util);
				var deaf_fecha_adquisicion = $(this).parent().parent().parent().find(".deaf_fecha_adquisicion").val();
				$("#dialog_deaf_fecha_adquisicion").html(deaf_fecha_adquisicion);
				
				$("#prod_details").dialog({
					modal: true,
					title: "Ver detalles",
					width: 380
				});
			});
		});
	}
	
	var limpia_form = function() {
		$(".detalle_form input[type='text']").each(function() {
			$(this).val("");
		});
		
		$(".detalle_form select").each(function() {
			$(this).val("");
		});
	}
	
	$(".detalle_form input, select").each(function() {
		$(this).focus(function() {
			hideTipsy($(this));
		});
	});
	
	$("#form_submit").click(function() {
		$('#FormActivoFijo').submit();
	});
	
	$(".deaf_fecha_garantia").datepicker({
		changeMonth: true,
		changeYear: true,
		dateFormat: "dd-mm-yy"		
	});
	
	$(".deaf_fecha_adquisicion").datepicker({
		changeMonth: true,
		changeYear: true,
		dateFormat: "dd-mm-yy"		
	});
	
	$(".deaf_depreciable").click(function() {
		var checked = $(this).attr("checked") ? 1:0;

		if (checked == 0) {
			$(".deaf_vida_util").val("");
			$(".deaf_vida_util").attr("readonly", "readonly");
			$(".deaf_vida_util").parent().removeClass("required");
		} else {
			$(".deaf_vida_util").removeAttr("readonly");
			$(".deaf_vida_util").parent().attr("class", "input required");
		}
	
	});
	
	del_row();
	ver_detalles();
});