// JavaScript Document
$(document).ready(function() {
	$("#to_excel").click(function() {
		location.href = "/contratos/excelAll";
	});
	
	$("#busca_contrato").autocomplete({
	     source: "/contratos/searchContratos",
		 select: function (event, ui) {
		 	location.href = "/contratos/view/"+ui.item.cont_id;
		 }
	});
});