// JavaScript Document
$(document).ready(function() {
	$("#busqueda_producto").autocomplete({
		source: "/modelos/searchTodo",
		select: function (event, ui) {
			location.href = "/modelos/edit/"+ ui.item.mode_id;
		}
	});
});