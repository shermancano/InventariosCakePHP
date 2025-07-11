// JavaScript Document
$(document).ready(function() {
	
	$("#FormReporteMigracion").submit(function() {
		var ceco_id = $("#ReporteCecoId").val();		
		var url = '/reportes/activos_fijos_general_excel_migracion/'+ceco_id;
			
		location.href = url;
		return false;		
	});
});
