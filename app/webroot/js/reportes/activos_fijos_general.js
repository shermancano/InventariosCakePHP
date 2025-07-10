// JavaScript Document
$(document).ready(function() {
	
	$("#FormReporte").submit(function() {
		
		var checked = $("#ReportePdf").attr("checked") ? 1:0;
		var multiple = $("#ReporteMultiple").attr("checked") ? 1:0;
		var ceco_id = $("#ReporteCecoId").val();
		
		if (checked == 1) {			
			var url = '/reportes/activos_fijos_general_pdf/'+multiple+'/'+ceco_id;
		} else {
			var url = '/reportes/activos_fijos_general_excel/'+multiple+'/'+ceco_id;
		}
			
		location.href = url;
		return false;		
	});
});