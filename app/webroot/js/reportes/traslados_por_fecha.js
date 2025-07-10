// JavaScript Document
$(document).ready(function() {
	
	$("#ReporteFechaDesde").datepicker({
		changeMonth: true,
		changeYear: true,
		dateFormat: 'dd-mm-yy'
	});    
	
	$("#ReporteFechaHasta").datepicker({
		changeMonth: true,
		changeYear: true,
		dateFormat: 'dd-mm-yy'
	});
	
	$("#ReporteTraslado").submit(function(){
		
		var ceco_id = $("#ReporteCecoId").val();
		var ceco_id_hijo = $("#ReporteCecoDestino").val();
		var tibi_id = $("#ReporteTibiNombre").val();
		var fecha_desde = $("#ReporteFechaDesde").val();
		var fecha_hasta = $("#ReporteFechaHasta").val();				
		var checked = $("#ReportePdf").attr("checked") ? 1:0;
		
		if (fecha_desde == "") {
			alert("Debe ingresar fecha desde");
			return false;
		}
		
		if (fecha_hasta == "") {
			alert("Debe ingresar fecha hasta");
			return false;
		}
		
		if (fecha_desde > fecha_hasta) {			
			alert("Fecha 'desde' debe ser menor a fecha 'hasta'");
			return false;			
		}
		
		if (ceco_id_hijo == "") {
			ceco_id_hijo = 'null';
		}
		
		if (checked == 1) {
			
			if (tibi_id == 3) { // solo existencia
				var url = '/reportes/traslados_por_fechas_existencias_pdf/'+ ceco_id +'/'+ ceco_id_hijo +'/'+ tibi_id +'/'+ fecha_desde +'/'+ fecha_hasta;
			} else if (tibi_id == 1) { // solo activos fijos
				var url = '/reportes/traslados_por_fechas_activos_fijos_pdf/'+ ceco_id +'/'+ ceco_id_hijo +'/'+ tibi_id +'/'+ fecha_desde +'/'+ fecha_hasta;
			}

		} else {
			
			if (tibi_id == 3) { // solo existencia
				var url = '/reportes/traslados_por_fechas_existencias_excel/'+ ceco_id +'/'+ ceco_id_hijo +'/'+ tibi_id +'/'+ fecha_desde +'/'+ fecha_hasta;
			} else if (tibi_id == 1) { // solo activos fijos
				var url = '/reportes/traslados_por_fechas_activos_fijos_excel/'+ ceco_id +'/'+ ceco_id_hijo +'/'+ tibi_id +'/'+ fecha_desde +'/'+ fecha_hasta;
			}
				
		}
		
		$("#ReporteCecoDestino").val("");
		$("#ReporteFechaHasta").val("");
		$("#ReporteFechaDesde").val("");	
			
		location.href = url;
		return false;
	});
});
