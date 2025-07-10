// JavaScript Document
$(document).ready(function() {
	$("#ReporteProdNombre").autocomplete({
		source: '/productos/searchActivosFijos',
		select: function (event, ui) {
			$("#ReporteProdId").val(ui.item.prod_id);
		}
	});
	
	$("#FormReporte").submit(function() {
		
		var ceco_id = $("#ReporteCecoId").val();
		var prod_id = $("#ReporteProdId").val();
		var checked = $("#ReportePdf").attr("checked") ? 1:0;
		
		if (prod_id == "") {
			prod_id = 'null';
		}
		
		if (checked == 1) {				
			var url = '/reportes/activos_fijos_pdf/'+ ceco_id +'/'+ prod_id;
		} else {				
			var url = '/reportes/activos_fijos_excel/'+ ceco_id +'/'+ prod_id;
		}
				
		$("#ReporteCecoId").val("");
		$("#ReporteProdId").val("");
			
		location.href = url;
		return false;		
	});
});