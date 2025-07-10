// JavaScript Document
$(document).ready(function() {
	$("#FormActivoFijo").submit(function() {	
		var ceco_id = $("#ActivoFijoCecoId").val();
		var radio = $('#ActivoFijoPlancheta1').is(':checked');
		
		if (radio == true) {
			var url = '/reportes/plancheta_activo_fijo_pdf/'+ ceco_id;
		} else {
			var url = '/reportes/plancheta_activo_fijo_agrupado_pdf/'+ ceco_id;
		}	
		
		location.href = url;
		return false;	
	});    
});