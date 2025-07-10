// JavaScript Document
$(document).ready(function() {
	
	$("#rechazo").dialog({
		modal: true,
		autoOpen: false,
		title: 'Rechazo de recepci&oacute;n', 
		resizable: true,
		width: 450,
		close: function() {
			$("#motivo").tipsy("hide");
		}
	});
	
	$(".recepcionar").each(function() {
		$(this).click(function() {
			var pieces = $(this).attr("rel");
				pieces = pieces.split("|");
			var ceco_nombre = pieces[0];
			var exis_id = pieces[1];
			var c = confirm('Tiene una recepci\u00f3n pendiente de '+ceco_nombre+', presione "Aceptar" para recepcionarla o "Cancelar" para rechazar el Traslado.');

			if (c) {
				location.href = '/existencias/acepta_recepcion/'+ exis_id;
			} else {
				$("#dialog_exis_id").val(exis_id);
				$("#rechazo").dialog("open");
			}
		});
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
	
	var trim = function(str) {
		str = str.replace(/^\s*|\s*$/g,"");
		return str;
	}
	
	$("#motivo").focus(function() {
		hideTipsy($(this));
	});
	
	$("#rechazar_entrada").click(function() {
		var exis_id = $(this).parent().parent().parent().find('#dialog_exis_id').val();
		var motivo = $("#motivo").val();
		
		if (trim(motivo) == "") {
			showTipsy($("#motivo"), 'Debe ingresar el motivo del rechazo');
			return;
		}
		
		$.ajax({
			type: "POST",
			cache: false,
			url: "/existencias/rechaza_recepcion/"+ exis_id,
			data: "motivo="+ motivo,
			beforeSend: function() {
				$("#loader").show();
			},
			success: function(data) {
				if (data == "email") {
					location.href = "/existencias/index_entrada";
				} else if (data == "rechazo") {
					$("#rechazo").dialog("close");
					location.href = "/existencias/index_entrada";
				} else {
					$("#rechazo").dialog("close");
					location.href = "/existencias/index_entrada";
				}
			},
			complete: function() {
				$("#loader").hide();
			}
		});
	});
	
	$("#busqueda_entrada_existencia").autocomplete({
		source: "/existencias/searchAll/1",
		select: function (event, ui) {
			location.href = "/existencias/view_entrada/"+ ui.item.exis_id;
		}
	});
	
});