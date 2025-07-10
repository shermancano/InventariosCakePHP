// JavaScript Document
function trim(str) {
	str = str.replace(/^\s*|\s*$/g,"");
	return str;
}

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
	
	var cant_acti = <?php echo $acti_count; ?>;
	$("#mas_actividades").click(function() {
		var html_str  = "<span><input class=\"acti_nombre\" name=\"data[Actividad]["+cant_acti+"][acti_nombre]\" />";
			html_str += "&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<input readonly=\"readonly\" style=\"width:120px;\" class=\"fecha\" name=\"data[Actividad]["+cant_acti+"][acti_fecha_presupuestada]\" />";
			html_str += "&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<input readonly=\"readonly\"s style=\"width:120px;\" class=\"fecha\" name=\"data[Actividad]["+cant_acti+"][acti_fecha_real]\" />";
			html_str += "&nbsp;&nbsp;&nbsp;<span><a class=\"del_actividad\" href=\"javascript:;\"><img src=\"/img/delete.png\" /></a>&nbsp;<span style=\"display:none;\" id=\"ajax_loader\"><img src=\"/img/ajax-loader.gif\" /></span></span>";
			html_str += "<br /><br /></span>";
		$("#mas_actividades_span").append(html_str);
		cant_acti += 1;
		load_calendar();
		del_actividad();
	});
	
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
				var acti_id = $(this).attr("rel");
				var t = $(this);
				var error = false;
				
				if (acti_id == null || acti_id == "undefined") {
					$(t).parent().parent().hide(0, function() {
						$(this).remove();
					});
					return;
				}
				
				$.ajax({
					type: "POST",
					url: "/actividades/delete/"+acti_id,
					cache: false,
					beforeSend: function() {
						$(t).parent().find("#ajax_loader").show();
					},
					success: function(data) {
						data = trim(data);
						
						if (data != "ok") {
							alert('Ha ocurrido un error al eliminar la actividad');
							error = true;
						}
					},
					complete: function() {
						$(t).parent().find("#ajax_loader").hide();
						if (!error) {
							$(t).parent().parent().hide(0, function() {
								$(this).remove();
							});
						}
					}
				});
			});	
		});
	}
	del_actividad();
});