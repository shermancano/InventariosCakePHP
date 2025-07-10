$(document).ready(function() {
	$("#busqueda_traslado_existencia").autocomplete({
		source: "/existencias/searchAll/2",
		select: function (event, ui) {
			location.href = "/existencias/view_traslado/"+ ui.item.exis_id;
		}
	});
});