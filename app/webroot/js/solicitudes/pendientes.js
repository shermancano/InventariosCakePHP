$(document).ready(function() {
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

	$("#rechazo").dialog({
		modal: true,
		autoOpen: false,
		title: 'Rechazo de solicitud', 
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
			var soli_id = pieces[1];
			var c = confirm('Tiene una solicitud pendiente de '+ceco_nombre+', presione "Aceptar" para recepcionarla o "Cancelar" para rechazar la solicitud.');

			if (c) {
				location.href = '/solicitudes/acepta_solicitud/'+ soli_id;
			} else {
				$("#dialog_soli_id").val(soli_id);
				$("#rechazo").dialog("open");
			}
		});
	});
	
	$("#rechazar_solicitud").click(function() {
		var soli_id = $(this).parent().parent().parent().find('#dialog_soli_id').val();
		var motivo = $("#motivo").val().trim();
		
		if (motivo == "") {
			showTipsy($("#motivo"), 'Debe ingresar el motivo del rechazo');
			return;
		}
		
		$.ajax({
			type: "POST",
			cache: false,
			url: "/solicitudes/rechaza_solicitud/"+ soli_id,
			data: "motivo="+ motivo,
			beforeSend: function() {
				$("#loader").show();
			},
			dataType: "json",
			success: function(data) {
				if (data.res == "email") {
					if (data.from == "int") {
						location.href = "/solicitudes/pendientes_internas";
					} else {
						location.href = "/solicitudes/pendientes_externas";
					}
				} else if (data.res == "rechazo") {
					$("#rechazo").dialog("close");
					if (data.from == "int") {
						location.href = "/solicitudes/pendientes_internas";
					} else {
						location.href = "/solicitudes/pendientes_externas";
					}
				} else {
					$("#rechazo").dialog("close");
					if (data.from == "int") {
						location.href = "/solicitudes/pendientes_internas";
					} else {
						location.href = "/solicitudes/pendientes_externas";
					}
				}
			},
			complete: function() {
				$("#loader").hide();
			}
		});
	});
	
});
