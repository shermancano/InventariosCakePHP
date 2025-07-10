// JavaScript Document
function validchars(evt,allowedchars) {

	kcode=(evt.keyCode>0)?evt.keyCode:evt.charCode;
	if(allowedchars.indexOf(String.fromCharCode(kcode))==-1&&kcode!=8&&kcode!=9&&kcode!=13)
		return false;
	else 
		return true;
}

var num  = "1234567890";

var datepicker = function() {
	$(".fecha_servicio").datepicker({
		changeMonth: true,
		changeYear: true,
		dateFormat: 'dd-mm-yy'
	});  
}

var update_valor_mantencion = function() {
	var total_mantencion = 0;
		
	$('#MantencionActivoFijo').find('.valor').each(function(index, element) {
		var valor = $(this).val();
		if (valor == '') {
			valor = 0;
		}
		total_mantencion = parseInt(valor) + parseInt(total_mantencion);
	});
	
	$('#valor_total').html('$ ' + total_mantencion);
	$('#ActivoFijoMantencionAfmaValorTotal').val(total_mantencion);
}

var suma_mantencion = function() {
	$('.valor').keyup(function() {
		var total_mantencion = 0;
		
		$('#MantencionActivoFijo').find('.valor').each(function(index, element) {
            var valor = $(this).val();
			if (valor == '') {
				valor = 0;
			}
			total_mantencion = parseInt(valor) + parseInt(total_mantencion);
        });
		
		$('#valor_total').html('$ ' + total_mantencion);
		$('#ActivoFijoMantencionAfmaValorTotal').val(total_mantencion);
	});
}

$(document).ready(function(e) {
	suma_mantencion();
	
	var ceco_id = $("#ActivoFijoMantencionCecoId").val();
	$(".codigo").autocomplete({		
		source: "/ubicaciones_activos_fijos/searchChildrenCc/"+ ceco_id,
		select: function (event, ui) {								
			$("#MantencionActivoFijoUbafCodigo").val(ui.item.ubaf_codigo);
		}
	});
	
	var year = $('#ActivoFijoMantencionAfmaYear').val();
	$('#ActivoFijoMantencionAfmaAnoYear').val(year);
	
	$('#MantencionActivoFijoTrafCodigo').bind("paste",function(e) {
	   //e.preventDefault();
	});
	
	$("#ActivoFijoMantencionAfmaFechaFactura").datepicker({
		changeMonth: true,
		changeYear: true,
		dateFormat: 'dd-mm-yy'
	}); 
	
	$(".fecha_servicio").datepicker({
		changeMonth: true,
		changeYear: true,
		dateFormat: 'dd-mm-yy'
	});  
	
	$('#add_mantencion').click(function() {
		var count_fila = 0;
		
		$('#MantencionActivoFijo').find('.tr_mantencion').each(function(index, element) {
            count_fila++;
        });
		
		var contenido = '';
		contenido += '<tr class="tr_mantencion">';
		contenido += '	<td>';
		contenido += '		<div class="input text">';
		contenido += '			<input id="DetalleMantencion'+ count_fila +'DemaFechaServicio" class="fecha_servicio" type="text" style="width:100%" name="data[DetalleMantencion]['+ count_fila +'][dema_fecha_servicio]">';
		contenido += '		</div>';
		contenido += '	</td>';
		contenido += '	<td>';
		contenido += '		<div class="input text">';
		contenido += '			<input id="DetalleMantencion'+ count_fila +'DemaKilometraje" type="text" style="width:100%" name="data[DetalleMantencion]['+ count_fila +'][dema_kilometraje]" onkeypress="return validchars(event, num)">';
		contenido += '		</div>';
		contenido += '	</td>';
		contenido += '	<td>';
		contenido += '		<div class="input textarea">';
		contenido += '			<textarea id="DetalleMantencion'+ count_fila +'DemaDescripcion" cols="30" style="width:100%" rows="2" name="data[DetalleMantencion]['+ count_fila +'][dema_descripcion]" class="descripcion"></textarea>';
		contenido += '		</div>';
		contenido += '	</td>';
		contenido += '	<td>';
		contenido += '		<div class="input textarea">';
		contenido += '			<textarea id="DetalleMantencion'+ count_fila +'DemaNombreOperador" cols="30" style="width:100%" rows="2" name="data[DetalleMantencion]['+ count_fila +'][dema_nombre_operador]" class="operador"></textarea>';
		contenido += '		</div>';
		contenido += '	</td>';
		contenido += '	<td>';
		contenido += '		<div class="input text">';
		contenido += '			<input id="DetalleMantencion'+ count_fila +'DemaValor" type="text" style="width:100%" name="data[DetalleMantencion]['+ count_fila +'][dema_valor]" onkeypress="return validchars(event, num)" class="valor">';
		contenido += '		</div>';
		contenido += '	</td>';
		contenido += '	<td>';
		contenido += '		<div class="input text">';
		contenido += '			<textarea id="DetalleMantencion'+ count_fila +'DemaObservacion" cols="30" style="width:100%" rows="2" name="data[DetalleMantencion]['+ count_fila +'][dema_observacion]"></textarea>';
		contenido += '		</div>';
		contenido += '	</td>';
		contenido += '</tr>';
		$('#tabla_mantencion tbody').append(contenido);
		datepicker();
		suma_mantencion();
	});
	
	$('#del_mantencion').click(function() {
		var count_fila = 0;
		
		$('#MantencionActivoFijo').find('.tr_mantencion').each(function(index, element) {
            count_fila++;
        });
	
		if (count_fila > 1) {
			if (confirm('Esta seguro de eliminar la última fila') == 1) {
				var tr = $('#tabla_mantencion tr:last');
				tr.remove();
				update_valor_mantencion();
			} else {
				return false;
			}								
		}
	});
	
	$('#MantencionActivoFijo').submit(function() {
		var validate = true;
		var ubaf_codigo = $('#MantencionActivoFijoUbafCodigo').val();
		var numero_factura = $('#ActivoFijoMantencionAfmaNumeroFactura').val();
		var fecha_factura = $('#ActivoFijoMantencionAfmaFechaFactura').val();
		var proveedor = $('#ActivoFijoMantencionProvId').val();
		
		if (ubaf_codigo == '') {
			alert('Debe ingresar el nombre del bien.');
			return false;
		}
		
		if (numero_factura == '') {
			alert('Debe ingresar el numero de la factura.');
			return false;
		}
		
		if (fecha_factura == '') {
			alert('Debe ingresar la fecha de la factura.');
			return false;
		}
		
		if (proveedor == '') {
			alert('Debe ingresar el nombre del proveedor.');
			return false;
		}
		
		$('#MantencionActivoFijo').find('#tabla_mantencion tbody tr').each(function(index, element) {
           	var fecha_servicio = $(this).find('.fecha_servicio').val();
			var descripcion = $(this).find('.descripcion').val();
			var operador = $(this).find('.operador').val();
			
			if (fecha_servicio == '') {
				alert('Debe seleccionar la fecha de servicio.');
				validate = false;
				return false;
			}
			
			if (descripcion == '') {
				alert('Debe ingresar el trabajo y/o servicio.');
				validate = false;
				return false;
			}
			
			if (operador == '') {
				alert('Debe ingresar el nombre del operador.');
				validate = false;
				return false;
			}	 
        });
		
		if (validate == false) {
			return false;
		} else {
			return true;
		}
	});
});