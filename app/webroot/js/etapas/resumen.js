// JavaScript Document
$(document).ready(function() {
	$("#EtapaResumenContNombre").autocomplete({
	     source: "/etapas/searchContratos",
		 select: function (event, ui) {
		 	$("#EtapaResumenContId").val(ui.item.cont_id)
		 }
	});
	
	$("#buscarResumen").click(function() {
		var cont_id = $("#EtapaResumenContId").val();
		var found = true;
		
		if (cont_id != "") {
			$.ajax({
				type: "POST",
				url: "/etapas/getResumen/"+cont_id,
				cache: false,
				dataType: "json",
				beforeSend: function() {
					$("#ajax_loader").show();
					$("#info_resumen").slideUp("slow");
				},
				success: function(data) {
					if (data.info_res == "" || data.info_res == null) {
						alert('No existen etapas/actividades para el contrato seleccionado');
						found = false;
						return;
					}
					
					$("#prov_razon_social").html(data.info_cont.prov_razon_social);
					$("#cont_nombre").html(data.info_cont.cont_nombre);
					
					var total_dias_final = 0;
					$("#resumen_table").html("");
					var html_str  = "<tr>";
						html_str += "   <th width=\"8%\">&nbsp;</th>";
						html_str += "   <th width=\"25%\">&nbsp;</th>";
						html_str += "   <th>Fecha Presu.</th>";
						html_str += "   <th>Fecha Real</th>";
						html_str += "   <th>Diferencia de D&iacute;as</th>";
						html_str += "   <th>Cumple</th>";
						html_str += "</tr>";
					
					$.each(data.info_res, function(i, obj) {
						html_str += "<tr>";
						html_str += "   <th colspan=\"6\">"+obj.etap_nombre+"</th>";
						html_str += "</tr>";
						var total_dias = 0;
						var count = 1;
						
						$.each(obj.info_act, function(i, obj) {
							html_str += "<tr>";
							html_str += "   <td>"+count+"</td>";
							html_str += "   <td>"+obj.acti_nombre+"</td>";
							html_str += "   <td>"+obj.acti_fecha_presupuestada+"</td>";
							html_str += "   <td>"+obj.acti_fecha_real+"</td>";
							html_str += "   <td>"+obj.diferencia+"</td>";
							html_str += "   <td>"+obj.cumple+"</td>";
							html_str += "</tr>";
							total_dias += parseInt(obj.diferencia);
							count += 1;
						});
						
						html_str += "<tr>";
						html_str += "   <td>&nbsp;</td>";
						html_str += "   <td><strong>Total d&iacute;as</strong></td>";
						html_str += "   <td>&nbsp;</td>";
						html_str += "   <td>&nbsp;</td>";
						html_str += "   <td><strong>"+total_dias+"</strong></td>";
						html_str += "   <td>&nbsp;</td>";
						html_str += "</tr>";
						total_dias_final += total_dias;
						
					});
					
					html_str += "<tr>";
					html_str += "   <th colspan=\"6\">&nbsp;</th>";
					html_str += "</tr>";
					html_str += "<tr>";
					html_str += "   <td>&nbsp;</td>";
					html_str += "   <td><strong>Total d&iacute;as final</strong></td>";
					html_str += "   <td>&nbsp;</td>";
					html_str += "   <td>&nbsp;</td>";
					html_str += "   <td><strong>"+total_dias_final+"</strong></td>";
					html_str += "   <td>&nbsp;</td>";
					html_str += "</tr>";
					
					$("#resumen_table").append(html_str);
				},
				complete: function() {
					if (found) {
						$("#info_resumen").slideDown("slow");
					}
					$("#ajax_loader").hide();
				}
			});
		}
	});
	
	$(".excel_export").click(function() {
		var cont_id = $("#EtapaResumenContId").val();
		location.href = "/etapas/excel/"+cont_id;
	});
	
	$(".pdf_export").click(function() {
		var cont_id = $("#EtapaResumenContId").val();
		location.href = "/etapas/pdf/"+cont_id;
	});
});