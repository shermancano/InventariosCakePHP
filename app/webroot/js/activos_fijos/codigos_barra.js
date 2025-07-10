var serialize = function() {
	$("#BajaActivoFijo").submit(function() {
		var devi_id = $('#BajaDeviId').val();
		var moba_id = $('#BajaMobaId').val();
		var observacion = $('#BajaBaafObservacion').val();
		var num_doc = $('#BajaBaafNumeroDocumento').val();
		var ceco_id = $('#BajaCecoId').val();	
		
		if (ceco_id == '') {
			alert('Debe ingresar el centro de costo de destino.');
			return false;
		}
		
		if (devi_id == '') {
			alert('Debe seleccionar el nombre de la dependencia');
			return false;
		}
		
		if (moba_id == '') {
			alert('Debe seleccionar el nombre del motivo');
			return false;
		}
		
		if (num_doc == '') {
			alert('Debe ingresar el número de resolución');
			return false;
		}
		
		if (confirm('¿Esta seguro de guardar la baja?') == 1) {
			var data = $(this).serialize();
	 
			$.ajax({
				type: "POST",
				url: '/bajas_activos_fijos/codigos_barra_bajas',			
				data: data,
				dataType: "json",		
				success: function(data, textStatus, xhr) {
					$('#BajaActivoFijoCecoId').val(data.Baja.ceco_id);
					$('#BajaActivoFijoDeviId').val(data.Baja.devi_id);
					$('#BajaActivoFijoMobaId').val(data.Baja.moba_id);
					$('#BajaActivoFijoBaafObservacion').val(data.Baja.baaf_observacion);
					$('#BajaActivoFijoBaafNumeroDocumento').val(data.Baja.baaf_numero_documento);
					$('#DetalleBaja').submit();
				},
				error: function(xhr, textStatus, error) {
					alert('error');
				}
			});
		}
		
		return false;
	});
	
	$("#ExcluidoActivoFijo").submit(function() {
		var devi_id = $('#ExclusionDeviId').val();
		var moba_id = $('#ExclusionMobaId').val();
		var observacion = $('#ExclusionExafObservacion').val();
		var num_doc = $('#ExclusionExafNumeroDocumento').val();
		var ceco_id = $('#ExclusionCecoId').val();	
		
		if (ceco_id == '') {
			alert('Debe ingresar el centro de costo de destino.');
			return false;
		}
		
		if (devi_id == '') {
			alert('Debe seleccionar el nombre de la dependencia');
			return false;
		}
		
		if (moba_id == '') {
			alert('Debe seleccionar el nombre del motivo');
			return false;
		}
		
		if (num_doc == '') {
			alert('Debe ingresar el número de resolución');
			return false;
		}
		
		if (confirm('¿Esta seguro de guardar la baja?') == 1) {
			var data = $(this).serialize();
	 
			$.ajax({
				type: "POST",
				url: '/exclusiones_activos_fijos/codigos_barra_exclusion',			
				data: data,
				dataType: "json",		
				success: function(data, textStatus, xhr) {					
					$('#ExclusionActivoFijoCecoId').val(data.Excluido.ceco_id);
					$('#ExclusionActivoFijoDeviId').val(data.Excluido.devi_id);
					$('#ExclusionActivoFijoMobaId').val(data.Excluido.moba_id);
					$('#ExclusionActivoFijoExafObservacion').val(data.Excluido.exaf_observacion);
					$('#ExclusionActivoFijoExafNumeroDocumento').val(data.Excluido.exaf_numero_documento);
					$('#DetalleExcluido').submit();
				},
				error: function(xhr, textStatus, error) {
					alert('error');
				}
			});
		}
		
		return false;
	});
}

$(document).ready(function() {
	var showTipsy = function(id, msg) {
		var options = {
			delayIn: 500,
			trigger: 'manual',
			gravity: 's',
			title: function() {
				return msg;
			}
		};
		$(id).tipsy(options);
		$(id).tipsy("show");
	}
	
	var hideTipsy = function(id) {
		$(id).tipsy("hide");
	}
	
	showTipsy($("#casilla"), 'Debe marcar la casilla');
	serialize();	
	
	$('#bajas').click(function() {
		$("#bajas_dialog").dialog('open');
	});
	
	$('#excluidos').click(function() {
		$("#bajas_dialog").dialog('open');
	});
	
	$('#exportar_codigos').click(function() {
		location.href = '/activos_fijos/genera_codigo_barra_masivo';
	});

	$('#exportar_codigos_103').click(function() {
		location.href = '/activos_fijos/generar_etiquetas_103';
	});
	
	$('#form_submit_103').click(function() {
		var numero = $('#ActivoFijoNumero').val();
		location.href = '/activos_fijos/genera_codigo_barra_masivo_103/' + numero;
	});
	
	$('#guardar_bajas').click(function() {
		$('#BajaActivoFijo').submit();		
	});
	
	$('#guardar_excluidos').click(function() {
		$('#ExcluidoActivoFijo').submit();		
	});
	
	$('#cancelar_bajas').click(function() {
		$("#bajas_dialog").dialog('close');
		return false;
	});
	
	$("#bajas_dialog").dialog({
		modal: true,
		autoOpen: false,
		title: 'Detalle de Bajas',
		resizable: false,
		draggable: true,
		width: 450,
		height: 490
	});
	
	$("#detalles_dialog").dialog({
		modal: true,
		autoOpen: false,
		title: 'Detalle',
		resizable: true,
		draggable: true,
		width: 550
	});
	
	$(".ver_detalle_af").each(function() {
		$(this).click(function(){
			var rel = $(this).attr("rel");
			var p = rel.split("|");
			
			$("#ubaf_codigo").html(p[0]);
			$("#prod_nombre").html(p[1]);
			$("#ceco_nombre").html(p[2]);
			$("#prop_nombre").html(p[3]);
			$("#situ_nombre").html(p[4]);
			$("#marc_nombre").html(p[5]);
			$("#colo_nombre").html(p[6]);
			$("#mode_nombre").html(p[7]);
			$("#ubaf_serie").html(p[8]);
			$("#ubaf_fecha_adquisicion").html(p[9]);
			$("#ubaf_fecha_garantia").html(p[10]);
			$("#ubaf_precio").html(p[11]);
			
			if (parseInt(p[12]) == 1) {
				p[12] = "Si";
			} else {
				p[12] = "No";
			}
			
			$("#ubaf_depreciable").html(p[12]);
			$("#ubaf_vida_util").html(p[13]);			
			$("#detalles_dialog").dialog("open");		
		});
	});
});
