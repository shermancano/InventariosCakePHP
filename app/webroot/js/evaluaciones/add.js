// JavaScript Document
$(document).ready(function() {
	$("#EvaluacionContNombre").autocomplete({
	     source: "/contratos/searchContratos",
		 select: function (event, ui) {
		 	$("#EvaluacionContId").val(ui.item.cont_id)
		 }
	});
	
	$("#evaluaciones_form").submit(function() {
		var f_errors = false;
		var cont_id = $("#EvaluacionContId").val();
		
		if (cont_id == "") {
			alert('Debe seleccionar un contrato');
			$("#EvaluacionContNombre").focus();
			return false;	
		}
		
		$(".deev_ponderacion").each(function() {
			if ($(this).val() == "") {
				alert('La ponderacion no debe ser vacia');
				$(this).focus();
				f_errors = true;
			} else {
				if (isNaN($(this).val())) {
					alert('La ponderacion debe ser numerica');
					$(this).focus();
					f_errors = true;
				}
			}
		});
		
		if (f_errors) {
			return false;
		} else {
			return true;	
		}
	});
	
	var item_aplica = function() {
		$(".item_aplica").each(function() {
			$(this).click(function() {
				var is_checked = $(this).attr("checked")? 1:0;
				if (is_checked) {
					$(this).parent().parent().find("select").attr("disabled", "disabled");
				} else {
					$(this).parent().parent().find("select").removeAttr("disabled");
				}
			});
		});
	}
	item_aplica();
});