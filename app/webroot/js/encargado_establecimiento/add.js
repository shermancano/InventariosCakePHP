// JavaScript Document
$(document).ready(function() {
	$("#EncargadoEstablecimientoRespNombre").autocomplete({
	     source: "/usuarios/searchUsuarios?",
		 select: function (event, ui) {
		 	$("#EncargadoEstablecimientoUsuaId").val(ui.item.usua_id)
		 }
	});
});