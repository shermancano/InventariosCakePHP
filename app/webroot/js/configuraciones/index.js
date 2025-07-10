// JavaScript Document
function validchars(evt,allowedchars) {

	kcode=(evt.keyCode>0)?evt.keyCode:evt.charCode;
	if(allowedchars.indexOf(String.fromCharCode(kcode))==-1&&kcode!=8&&kcode!=9&&kcode!=13)
		return false;
	else 
		return true;
}

var num = "1234567890.";

$(document).ready(function() {
	var titulo = $("#ConfiguracionBarcodeTitulo").val();
	
	if (titulo == 0) {			
		$('#titulo').hide();
		$('#ConfiguracionBarcodeTituloNombre').val(0);
	} else {			
		$('#titulo').show();
		//$('#ConfiguracionBarcodeTituloNombre').val('');
	}
	
	$("#ConfiguracionBarcodeTitulo").change(function() {
		var checked = $(this).val();
		
		if (checked == 0) {			
			$('#titulo').hide();
			$('#ConfiguracionBarcodeTituloNombre').val(0);
		} else {			
			$('#titulo').show();
			$('#ConfiguracionBarcodeTituloNombre').val('');
		}
	});	

	$("#ConfiguracionUseSmtp").click(function() {
		var checked = $(this).attr("checked") ? 1:0;
		
		if (parseInt(checked) == 0) {
			$(this).val("0");
			$("#ConfiguracionSmtpHost").val("");
			$("#ConfiguracionSmtpHost").attr("readonly", "readonly");
			$("#ConfiguracionSmtpPort").val("");
			$("#ConfiguracionSmtpPort").attr("readonly", "readonly");
			$("#ConfiguracionSmtpTimeout").val("");
			$("#ConfiguracionSmtpTimeout").attr("readonly", "readonly");
		} else {
			$(this).val("1");
			$("#ConfiguracionSmtpHost").removeAttr("readonly");
			$("#ConfiguracionSmtpPort").removeAttr("readonly");
			$("#ConfiguracionSmtpTimeout").removeAttr("readonly");
		}
	});
	
	$("#ConfiguracionSmtpAuth").click(function() {
		var checked = $(this).attr("checked") ? 1:0;
		
		if (parseInt(checked) == 0) {
			$(this).val("0");
			$("#ConfiguracionSmtpUser").val("");
			$("#ConfiguracionSmtpUser").attr("readonly", "readonly");
			$("#ConfiguracionSmtpPass").val("");
			$("#ConfiguracionSmtpPass").attr("readonly", "readonly");
		} else {
			$(this).val("1");
			$("#ConfiguracionSmtpUser").removeAttr("readonly");
			$("#ConfiguracionSmtpPass").removeAttr("readonly");
		}
	});
	
	$("#logo").click(function() {
		$("#logo_dialog").dialog("open");
	});
	
	$("#logo_dialog").dialog({
		autoOpen: false,
		modal: true,
		draggable: true,
		resizable: true
	});
	
	$("#cod_barra_dialog").dialog({
		autoOpen: false,
		modal: true,
		draggable: true,
		resizable: true,
		width: 400
	});
	
	$("#ver_cod_barra_demo").click(function() {
		var barcode_type = $("#ConfiguracionBarcodeType").val();
		var barcode_logo = $("#ConfiguracionBarcodeLogo").val();
		var barcode_prod = $("#ConfiguracionBarcodeProd").val();
		var barcode_date = $("#ConfiguracionBarcodeDate").val();
		var barcode_cc = $("#ConfiguracionBarcodeCc").val();
		var barcode_height = $("#ConfiguracionBarcodeHeight").val();
		var barcode_width = $("#ConfiguracionBarcodeWidth").val();
		var barcode_serie = $("#ConfiguracionBarcodeSerie").val();
	
		var img = '<img src="/activos_fijos/genera_codigo_barra_demo/'+ barcode_type +'/'+ barcode_logo +'/'+ barcode_prod +'/'+ barcode_date +'/'+ barcode_cc +'/'+ barcode_height +'/'+ barcode_width +'/'+ barcode_serie +'" title="Cod Barra" alt="0" />';
		$("#cod_barra_dialog").html(img);
		$("#cod_barra_dialog").dialog("open");
	});
	
	$("#ConfiguracionDeprDateIni, #ConfiguracionDeprDateEnd").datepicker({dateFormat: 'dd-mm'})
	
});
