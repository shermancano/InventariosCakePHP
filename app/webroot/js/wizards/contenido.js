// JavaScript Document
function validchars(evt,allowedchars) {

	kcode=(evt.keyCode>0)?evt.keyCode:evt.charCode;
	if(allowedchars.indexOf(String.fromCharCode(kcode))==-1&&kcode!=8&&kcode!=9&&kcode!=13)
		return false;
	else 
		return true;
}

var nums = "1234567890.";

$(document).ready(function() {
	//DIALOG ENTRADAS / TRASLADOS
	$(".wizard_tipsy, .wizard_tipsy2, .tipsy_depreciacion").tipsy({trigger: 'manual', gravity: 's'});
	$("#contenido_wizard").dialog({
		modal: true,
		autoOpen: false,
		title: 'Wizard',
		resizable: false,
		draggable: false,
		width: 600,
		height: 290,
		open: function() {
			$('.wizard_tipsy').tipsy('show');
		},
		close: function() {
			$('.wizard_tipsy').tipsy('hide');
		}
	});	
	
	$("#contenido_wizard2").dialog({
		modal: true,
		autoOpen: false,
		title: 'Wizard',
		resizable: false,
		draggable: false,
		width: 600,
		height: 290,
		open: function() {
			$('.wizard_tipsy2').tipsy('show');
		},
		close: function() {
			$('.wizard_tipsy2').tipsy('hide');
		}
	});
	
	//DIALOG DEPRECIACION
	$("#wizard_depreciacion").dialog({
		modal: true,
		autoOpen: false,
		title: 'Wizard',
		resizable: false,
		draggable: false,
		width: 600,
		heigth: 290,
		open: function() {
			$('.tipsy_depreciacion').tipsy('show');
		},
		close: function() {
			$('.tipsy_depreciacion').tipsy('hide');
		}
	});
	
	$("#contenido").click(function () {
		$("#contenido_wizard").dialog("open");	
	});
	
	$("#depreciacion").click(function () {
		$("#wizard_depreciacion").dialog("open");	
	});
	
	$("#siguiente1").click(function () {
		$("#contenido_wizard").dialog("close");	
		$("#contenido_wizard2").dialog("open");	
	});
	
	$("#siguiente2").click(function () {
		var bien = $('input[name=bien]:checked').val();
		var accion = $('input[name=accion]:checked').val();
		
		if (accion == "ent") {
			if (bien == "1") {
				window.open("/activos_fijos/add_entrada");
			} else if (bien == "2") {
				window.open("/fungibles/add_entrada");
			} else if (bien == "3") {
				window.open("/existencias/add_entrada");
			}
		}
		
		if (accion == "tra") {
			if (bien == "1") {
				window.open("/activos_fijos/add_traslado");
			} else if (bien == "2") {
				window.open("/fungibles/add_traslado");
			} else if (bien == "3") {
				window.open("/existencias/add_traslado");
			}
		}
		$("#contenido_wizard2").dialog("close");	
	});
	
	$("#btn_depreciacion").click(function () {		
		var id_ipc = $("#ipc_activo_fijo").val();
		var id_depreciacion = $("#depreciacion_activo_fijoYear").val();
		
		location.href = '/depreciaciones/add/'+id_ipc+'/'+id_depreciacion;
		$("#wizard_depreciacion").dialog("close");
	});
});