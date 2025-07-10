// JavaScript Document
$(document).ready(function (){	
	$("#FormReporte").submit(function() {
		
		var ceco_id = $("#ReporteCecoId").val();
		var check = $("#ReporteCheck").attr("checked") ? 1:0;
		var pdf = $("#ReportePdf").attr("checked") ? 1:0;
		
		if (check == 1) { // Stock agrupado
			if (pdf == 1) { // Stock agrupado Pdf
				var url = '/reportes/stock_centro_costo_pdf/'+ ceco_id;
			} else if (pdf == 0) {
				var url = '/reportes/stock_centro_costo_excel/'+ ceco_id;
			}
				
		} else if (check == 0){ // Stock General
			if (pdf == 1) { // Stock Genral Pdf
				var url = '/reportes/stock_centro_costo_general_pdf/'+ ceco_id;
			} else if (pdf == 0) {
				var url = '/reportes/stock_centro_costo_general_excel/'+ ceco_id;
			}
		}
		
		location.href = url;
		return false;		
	});
});