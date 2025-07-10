$(document).ready(function() {
	$("#busqueda_producto").autocomplete({
		source: "/productos/searchTodo",
		select: function (event, ui) {
			location.href = "/productos/edit/"+ ui.item.prod_id;
		}
	});
});
