// JavaScript Document
$(document).ready(function() {
	$("#EtapaContNombre").autocomplete({
	     source: "/contratos/searchContratos",
		 select: function (event, ui) {
		 	$("#EtapaContId").val(ui.item.cont_id)
		 }
	});
	
	var load_calendar = function() {
		$(".fecha").each(function(i, val) {
			$(this).datepicker({dateFormat: 'dd-mm-yy'});	
		});
	}
	load_calendar();
	
	var acti_nombre_check = function() {
		var empty = false;
		$(".acti_nombre").each(function(i, val) {
			if ($(this).val() == "") {
				alert('Debe ingresar el nombre de la actividad');
				$(this).focus();
				empty = true;
				return;
			}
		});
		
		if (empty) {
			return false;
		} else {
			return true;
		}
	}
	
	var cant_acti = 0;
	$("#mas_actividades").click(function() {
		cant_acti += 1;
		var html_str  = "<span><input class=\"acti_nombre\" name=\"data[Actividad]["+cant_acti+"][acti_nombre]\" />";
			html_str += "&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<input readonly=\"readonly\" style=\"width:120px;\" class=\"fecha\" name=\"data[Actividad]["+cant_acti+"][acti_fecha_presupuestada]\" />";
			html_str += "&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<input readonly=\"readonly\"s style=\"width:120px;\" class=\"fecha\" name=\"data[Actividad]["+cant_acti+"][acti_fecha_real]\" />";
			html_str += "&nbsp;&nbsp;&nbsp;<span><a class=\"del_actividad\" href=\"javascript:;\"><img src=\"/img/delete.png\" alt=\"0\" /></a></span>";
			html_str += "<br /><br />";
			html_str += "</span>";
		$("#mas_actividades_span").append(html_str);
		load_calendar();
		del_actividad();
	});
	
	$("#form_etapas").submit(function() {
		if (!acti_nombre_check()) {
			return false;
		} else {
			var cont_id = $("#EtapaContId").val();
			
			if (cont_id == "") {
				alert('Debe seleccionar el contrato');
				$("#EtapaContId").focus();
				return false;
			}
			return true;
		}
	});
	
	var del_actividad = function() {
		$(".del_actividad").each(function() {
			$(this).click(function() {
				$(this).parent().parent().hide(0, function() {
					$(this).remove();
				});
			});	
		});
	}
	del_actividad();
});