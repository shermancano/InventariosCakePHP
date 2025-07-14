// JavaScript Document
$(document).ready(function (){	
	$("#FormReporte").submit(function() {
		
		var ceco_id = $("#ReporteCecoId").val();
		var pdf = $("#ReportePdf").attr("checked") ? 1:0;

		if (pdf == 1) { // Stock General Pdf
            var url = '/reportes/bienes_muebles_general_pdf/'+ ceco_id;
        } else if (pdf == 0) {
            var url = '/reportes/bienes_muebles_general_excel/'+ ceco_id;
        }
		
		location.href = url;
		return false;		
	});
});