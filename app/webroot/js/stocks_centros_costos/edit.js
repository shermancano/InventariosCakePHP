// JavaScript Document
$(document).ready(function() {
	$("#StockCentroCostoProdNombre").autocomplete({
		source: '/productos/searchExistencias',
		select: function (event, ui) {
			$("#StockCentroCostoProdId").val(ui.item.prod_id);
		}
	});
});