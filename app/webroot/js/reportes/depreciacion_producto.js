// JavaScript Document
function validchars(evt,allowedchars) {

	kcode=(evt.keyCode>0)?evt.keyCode:evt.charCode;
	if(allowedchars.indexOf(String.fromCharCode(kcode))==-1&&kcode!=8&&kcode!=9&&kcode!=13)
		return false;
	else 
		return true;
}

var num  = "1234567890";

$(document).ready(function(e) {
	var ceco_id = $("#ReporteCecoId").val();
	$(".codigo").autocomplete({		
		source: "/ubicaciones_activos_fijos/searchChildrenCc/"+ ceco_id,
		select: function (event, ui) {								
			$("#ReporteUbafCodigo").val(ui.item.ubaf_codigo);
		}
	});
	
	$('#ReporteTrafCodigo').bind("paste",function(e) {
	   //e.preventDefault();
	});
});