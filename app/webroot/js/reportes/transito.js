$(document).ready(function() {
	$("#FormReporte").submit(function() {
		var tibi_id = $("#ReporteTibiId").val();
		var pdf = $("#ReportePdf").attr("checked")? 1:0;
		
		if (pdf == 1) {
			// fungibles no implementado aun
			if (tibi_id == 2) {
				return false;
			}
			
			location.href = "/reportes/transito_pdf/"+ tibi_id;
		} else {
			// fungibles no implementado aun
			if (tibi_id == 2) {
				return false;
			}
			
			location.href = "/reportes/transito_excel/"+ tibi_id;
		}	
		
		return false;
	});
});