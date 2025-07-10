// JavaScript Document
function validchars(evt,allowedchars) {

	kcode=(evt.keyCode>0)?evt.keyCode:evt.charCode;
	if(allowedchars.indexOf(String.fromCharCode(kcode))==-1&&kcode!=8&&kcode!=9&&kcode!=13)
		return false;
	else 
		return true;
}

var num  = "1234567890";

var autocomplete = function() {
	var ceco_id = $("#ReporteCecoId").val();
	$(".codigo").autocomplete({		
		source: "/ubicaciones_activos_fijos/searchTrazabilidad/"+ ceco_id,
		select: function (event, ui) {			
			var rel = $(this).attr('rel');			
			$("#Reporte"+ rel +"TrafCodigo").val(ui.item.traf_codigo);
		}
	});
}

var delete_codigo_barra = function() {
	$('.del_codigo_barra').click(function() {
		$('.tr_codigo').last().remove();
		
		// Contamos numero de filas
		var count_fila = 0;
		$('#FormTrazabilidad').find('.tr_codigo').each(function(index, element) {
            count_fila++;
        });
		
		if (count_fila > 1) {
			// Agregamos botón eliminar a ultimo campo
			var content = '';
			content += '		<a class="del_codigo_barra">';
			content += '			<img alt="" title="Presione aquí para eliminar" src="/img/delete.png" />';
			content += '		</a>';
			$('.td_btn').last().append(content);
			delete_codigo_barra();
		}	
	});	
}

$(document).ready(function(e) {
	autocomplete();
	delete_codigo_barra();
	
	$('#add_codigo_barra').click(function() {
		// Contamos campos que contienen boton eliminar
		var btn_del = 0;
		$('#FormTrazabilidad').find('.del_codigo_barra').each(function(index, element) {
            btn_del++;
        });
		
		// Contamos numero de filas
		var indice = 0;
		$('#FormTrazabilidad').find('.tr_codigo').each(function(index, element) {
            indice++;
        });
		
		// Eliminamos botón eliminar del ultimo campo
		if (btn_del > 0) {
			$('.del_codigo_barra').last().remove();
		}
		
		var content = '';
		content += '<tr class="tr_codigo">';
		content += '	<td style="vertical-align:bottom; border-bottom: medium none; background: none;">';
		content += '		<input type="text" class="codigo" rel="'+ indice +'" />';
		content += '		<input id="Reporte'+ indice +'TrafCodigo" type="hidden" name="data[Reporte]['+ indice +'][traf_codigo]" />';
		content += '	</td>';
		content += '	<td class="td_btn" style="vertical-align:bottom; padding: 9px 0px 5px; border-bottom: medium none; background: none;">';
		content += '		<a class="del_codigo_barra">';
		content += '			<img alt="" title="Presione aquí para eliminar" src="/img/delete.png" />';
		content += '		</a>';
		content += '	</td>';		
		content += '</tr>';
		$('#tabla_trazabilidad tbody').append(content);
		autocomplete();
		delete_codigo_barra();
	});
	
	$('#ReporteTrafCodigo').bind("paste",function(e) {
	   //e.preventDefault();
	});
	
  //  $("#FormTrazabilidad").submit(function() {			
//		var traf_codigo = $("#ReporteTrafCodigo").val();
//		$.trim(traf_codigo);
//		
//		if (traf_codigo == "") {
//			alert('Debe ingresar el código de barra del bien.');
//			return false;
//		}
//		
//		var url = '/reportes/excel_trazabilidad/'+ traf_codigo;
//		location.href = url;
//		return false;	
//	});
});