// JavaScript Document
$(document).ready(function() {
	$("#GastoContNombre").autocomplete({
	     source: "/contratos/searchContratos",
		 select: function (event, ui) {
		 	$("#GastoContId").val(ui.item.cont_id)
		 }
	});
});