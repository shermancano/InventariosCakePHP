// JavaScript Document
$(document).ready(function() {
	$("#FormReporte").submit(function() {
		var tibi_id = $("#ReporteTibiId").val();
		var checked = $("#ReportePdf").attr("checked") ? 1:0;
		
		if (tibi_id == "") {
			tibi_id = 'null';
		}
		
		if (checked == 1) {
			location.href = "/reportes/productos_pdf/"+ tibi_id;
		} else {
			location.href = "/reportes/productos_excel/"+ tibi_id;
		}
		
		return false;
	});
});