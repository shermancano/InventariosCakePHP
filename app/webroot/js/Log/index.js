// JavaScript Document
$(document).ready(function () {
	$("#params_dialog").dialog({
		modal: true,
		autoOpen: false,
		title: 'Parámetros',
		resizable: true,
		draggable: true,
		width: 500,
		height: 100
	});

	$(".btn_mostrar").each(function () {
		$(this).click(function() {
			var params = $(this).parent().find(".params").html();
			$("#params_dialog").html(params);
			$("#params_dialog").dialog("open");		
		});
	});
});
