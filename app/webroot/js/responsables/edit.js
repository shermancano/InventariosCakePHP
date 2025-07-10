// JavaScript Document
$(document).ready(function() {
	$("#ResponsableRespNombre").autocomplete({
	     source: "/usuarios/searchUsuarios?",
		 select: function (event, ui) {
		 	$("#ResponsableUsuaId").val(ui.item.usua_id)
		 }
	});
});