// JavaScript Document
$(document).ready(function() {
	$("#mas_archivos").click(function() {
		var html_str = "<br /><br /><input type=\"file\" name=\"data[Documento][docs][]\" />";
		$("#mas_archivos_span").append(html_str);
	});
	
	$("#DocumentoContNombre").autocomplete({
	     source: "/contratos/searchContratos",
		 select: function (event, ui) {
		 	$("#DocumentoContId").val(ui.item.cont_id)
		 }
	});
});