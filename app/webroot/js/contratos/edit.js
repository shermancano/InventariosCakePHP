// JavaScript Document
$(document).ready(function() {
	$("#ContratoContMultas").change(function() {
		if ($(this).val() == 1) {
			$("#ContratoContDetalleMultas").removeAttr("disabled");
		} else {
		$("#ContratoContDetalleMultas").val("");
			$("#ContratoContDetalleMultas").attr("disabled", "disabled");
		}
	});	
	
	$("#mas_archivos").click(function() {
		var html_str = "<br /><br /><input type=\"file\" name=\"data[Documento][]\" />";
		$("#mas_archivos_span").append(html_str);
	});
	
	$("#ContratoAplicaBoleta").click(function() {
		var checked = $(this).attr("checked") ? 1:0;
		
		if (checked) {
			$("#ContratoTimoIdGarantia").removeAttr("disabled");
			$("#ContratoContMontoGarantia").removeAttr("disabled");
			$("#ContratoContNroBoleta").removeAttr("disabled");
			$("#ContratoBancId").removeAttr("disabled");
			$("#ContratoContVencimientoGarantiaDay").removeAttr("disabled");
			$("#ContratoContVencimientoGarantiaMonth").removeAttr("disabled");
			$("#ContratoContVencimientoGarantiaYear").removeAttr("disabled");
			$("#ContratoContFechaAvisoDay").removeAttr("disabled");
			$("#ContratoContFechaAvisoMonth").removeAttr("disabled");
			$("#ContratoContFechaAvisoYear").removeAttr("disabled");
		} else {
			$("#ContratoTimoIdGarantia").val("");
			$("#ContratoTimoIdGarantia").attr("disabled", "disabled");
			$("#ContratoContMontoGarantia").val("");
			$("#ContratoContMontoGarantia").attr("disabled", "disabled");
			$("#ContratoContNroBoleta").val("");
			$("#ContratoContNroBoleta").attr("disabled", "disabled");
			$("#ContratoBancId").val("");
			$("#ContratoBancId").attr("disabled", "disabled");
			$("#ContratoContVencimientoGarantiaDay").val("");
			$("#ContratoContVencimientoGarantiaDay").attr("disabled", "disabled");
			$("#ContratoContVencimientoGarantiaMonth").val("");
			$("#ContratoContVencimientoGarantiaMonth").attr("disabled", "disabled");
			$("#ContratoContVencimientoGarantiaYear").val("");
			$("#ContratoContVencimientoGarantiaYear").attr("disabled", "disabled");
			$("#ContratoContFechaAvisoDay").val("");
			$("#ContratoContFechaAvisoDay").attr("disabled", "disabled");
			$("#ContratoContFechaAvisoMonth").val("");
			$("#ContratoContFechaAvisoMonth").attr("disabled", "disabled");
			$("#ContratoContFechaAvisoYear").val("");
			$("#ContratoContFechaAvisoYear").attr("disabled", "disabled");
		}
	});
});