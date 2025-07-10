// JavaScript Document
var valida_archivo = function() {
	// Variables para la validación del archivo upload
	var archivo = $("#ProductoImagenProdContenido");
	var file = archivo[0].files[0];
	var ctype = (file.type == "") ? 'n/a': file.type;
	var bytes = file.size;
	var extensiones_permitidas = new Array(".jpg", ".jpeg", ".png", ".gif");
	var extension = (ctype.substring(ctype.lastIndexOf("/"))).toLowerCase();
	extension = extension.replace("/",".");
	
	// Compruebamos si la extensión está entre las permitidas
	var permitida = false;
	for (var i = 0; i < extensiones_permitidas.length; i++) {
		if (extensiones_permitidas[i] == extension) {
			permitida = true;
			break;
		}
	} 
	
	if (!permitida) {
		 alert("Compruebe la extensión de los archivos a subir. \nSólo se pueden subir archivos con extensiones: " + extensiones_permitidas.join());
		 $("#ProductoImagenProdContenido").val("");
		 return false;
	}
	
	if (bytes >= (1024*1024*2)) {
		alert('Compruebe el tamaño del archivo a subir. No puede superar los 2MB, debe disminuir la resolución de la fotografía.');
		$("#ProductoImagenProdContenido").val("");
		return false;
	}
}

$(document).ready(function() {
	$("#ProductoImagenProdContenido").change(function() {
		valida_archivo();
	});
	
	$("#ProductoFamiId").change(function() {
		var fami_id = $(this).val();
		var childSelect = $("#ProductoGrupId");
		
		if (fami_id != "") {
			$.ajax({
				type: 'GET',
				url: '/grupos/findGrupos/'+fami_id,
				cache: false,
				dataType: 'json',
				beforeSend: function() {
					childSelect.html('');
					childSelect.html('<option value="">-- Seleccione opci\u00f3n --</option>');
					$("#fami_loader").show();
				},
				success: function(data) {	
					$.each(data, function(i, obj){
						childSelect.append('<option value="'+ obj.Grupo.grup_id +'">'+ obj.Grupo.grup_nombre +'</option>');
					});
				},
				complete: function() {
					$("#fami_loader").hide();
				}
			});
		}
	});
	
	$("#ProductoTifaId").change(function() {
		var tifa_id = $(this).val();
		var childSelect = $("#ProductoFamiId");
		
		if (tifa_id != "") {
			$.ajax({
				type: 'GET',
				url: '/familias/findFamilias/'+tifa_id,
				cache: false,
				dataType: 'json',
				beforeSend: function() {
					childSelect.html('');
					childSelect.html('<option value="">-- Seleccione opci\u00f3n --</option>');
					$("#tifa_loader").show();
				},
				success: function(data) {
					$.each(data, function(i, obj){
						childSelect.append('<option value="'+ obj.Familia.fami_id +'">'+ obj.Familia.fami_nombre +'</option>');
					});
				},
				complete: function() {
					$("#tifa_loader").hide();
				}
			});
		}
	});
});