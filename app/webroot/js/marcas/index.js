// JavaScript Document
$(document).ready(function() {
	$("#busqueda_producto").autocomplete({
		source: "/marcas/searchTodo",
		select: function (event, ui) {
			location.href = "/marcas/edit/"+ ui.item.marc_id;
		}
	});
});