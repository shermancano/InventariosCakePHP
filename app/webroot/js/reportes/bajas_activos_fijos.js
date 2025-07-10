// JavaScript Document
$(document).ready(function() {
	$("#ReporteProdNombre").attr('autocomplete', 'off');
	$("#ReporteProdNombre").autocomplete({
		source: '/productos/searchActivosFijos',
		select: function (event, ui) {
			$("#ReporteProdId").val(ui.item.prod_id);
		}
	});
	
	$("#ReporteFechaDesde").datepicker({
		changeMonth: true,
		changeYear: true,
		dateFormat: "dd-mm-yy"		
	});
	
	$("#ReporteFechaHasta").datepicker({
		changeMonth: true,
		changeYear: true,
		dateFormat: "dd-mm-yy"		
	});
	
	$("#FormReporteBajas").submit(function() {
		
		var ceco_id = $("#ReporteCecoId").val();
		var prod_id = $("#ReporteProdId").val();
		var fecha_desde = $("#ReporteFechaDesde").val();
		var fecha_hasta = $("#ReporteFechaHasta").val();
		
		if (fecha_desde != '' && fecha_hasta == '') {
			alert('Debe ingresar la fecha hasta');
			return false;
		}

		if (fecha_hasta != '' && fecha_desde == '') {
			alert('Debe ingresar la fecha desde');
			return false;
		}
		
		if (fecha_desde > fecha_hasta) {
			alert('La fecha desde debe ser menor a la fecha hasta');
			return false;
		}
		
		if (prod_id == "") {
			prod_id = 'null';
		}
		
		if (fecha_desde == "") {
			fecha_desde = 'null';
		}
		
		if (fecha_hasta == "") {
			fecha_hasta = 'null';
		}
		
		var url = '/reportes/bajas_activos_fijos_excel/'+ ceco_id +'/'+ prod_id +'/'+ fecha_desde +'/'+ fecha_hasta;
		
		$("#ReporteProdNombre").val("");
		$("#ReporteProdId").val("");
		$("#ReporteFechaDesde").val("");
		$("#ReporteFechaHasta").val("");
			
		location.href = url;
		return false;		
	});
});// JavaScript Document