// JavaScript Document
$(document).ready(function() {
	$("#busqueda_producto").autocomplete({
		source: "/grupos/searchTodo",
		select: function (event, ui) {
			location.href = "/grupos/edit/"+ ui.item.grup_id;
		}
	});
});