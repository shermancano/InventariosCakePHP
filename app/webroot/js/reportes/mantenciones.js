// JavaScript Document
$(document).ready(function(e) {
    var ceco_id = $("#ReporteCecoId").val();
	$(".codigo").autocomplete({		
		source: "/ubicaciones_activos_fijos/searchChildrenCc/"+ ceco_id,
		select: function (event, ui) {								
			$("#ReporteUbafCodigo").val(ui.item.ubaf_codigo);
		}
	});
	
	$('#FormReporte').submit(function() {
		var ubaf_codigo = $("#ReporteUbafCodigo").val();
		
		if (ubaf_codigo == '') {
			alert('Debe ingresar un producto para poder generar el reporte.');
			return false;
		} else {
			location.href = '/reportes/mantenciones_excel/' + ubaf_codigo;
			return false;
		}
	});
});