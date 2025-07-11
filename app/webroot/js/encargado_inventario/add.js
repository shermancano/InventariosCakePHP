// JavaScript Document
$(document).ready(function() {
	$("#EncargadoInventarioRespNombre").autocomplete({
	     source: "/usuarios/searchUsuarios?",
		 select: function (event, ui) {
		 	$("#EncargadoInventarioUsuaId").val(ui.item.usua_id)
		 }
	});
});