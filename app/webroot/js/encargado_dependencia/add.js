// JavaScript Document
$(document).ready(function() {
	$("#EncargadoDependenciaRespNombre").autocomplete({
	     source: "/usuarios/searchUsuarios?",
		 select: function (event, ui) {
		 	$("#EncargadoDependenciaUsuaId").val(ui.item.usua_id)
		 }
	});
});