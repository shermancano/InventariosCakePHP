// JavaScript Document
$(document).ready(function() {
	$("#busqueda_producto").autocomplete({
		source: "/proveedores/searchTodo",
		select: function (event, ui) {
			location.href = "/proveedores/edit/"+ ui.item.prov_id;
		}
	});
});