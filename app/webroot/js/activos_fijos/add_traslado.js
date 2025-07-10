$(document).ready(function() {
	var ceco_id = $("#TrasladoActivoFijoCecoId").val();
	
	$("#codigo").autocomplete({
		source: "/ubicaciones_activos_fijos/searchByCc/"+ ceco_id,
		select: function (event, ui) {
			$("#ubaf_codigo").val(ui.item.ubaf_codigo);
		}
	});
	
	var row_count = 0;
	$("#nuevo_detalle").click(function() {
		var ubaf_codigo = $("#ubaf_codigo").val();
		
		if (ubaf_codigo != "") {
			
			if (!checkRepetido(ubaf_codigo)) {
				$.ajax({
					type: 'POST',
					cache: false,
					url: '/ubicaciones_activos_fijos/searchByCodigo/'+ ubaf_codigo,
					dataType: 'json',
					async: false,
					beforeSend: function() {
						$("#ajax_loader").show();
					},
					success: function(data) {
						var html_str  = "<tr>";
							html_str += "   <td>"+ data.UbicacionActivoFijo.ubaf_codigo +"</td>";
							html_str += "   <td>"+ data.Producto.prod_nombre +"</td>";
							html_str += "   <td>"+ data.Propiedad.prop_nombre +"</td>";
							html_str += "   <td>"+ data.Situacion.situ_nombre +"</td>";
							
							if (data.Marca.marc_nombre == null) {
								html_str += "   <td></td>";	
							} else {
								html_str += "   <td>"+ data.Marca.marc_nombre +"</td>";
							}
							
							if (data.Color.colo_nombre == null) {
								html_str += "   <td>&nbsp;</td>";
							} else {
								html_str += "   <td>"+ data.Color.colo_nombre +"</td>";
							}							
							
							if (data.Modelo.mode_nombre == null) {
								html_str += "   <td></td>";
							} else {
								html_str += "   <td>"+ data.Modelo.mode_nombre +"</td>";
							}
							
							if (data.UbicacionActivoFijo.ubaf_serie == null) {
								html_str += "   <td></td>";
							} else {
								html_str += "   <td>"+ data.UbicacionActivoFijo.ubaf_serie +"</td>";
							}						
								
							if (data.UbicacionActivoFijo.ubaf_depreciable == 1) {
								html_str += "   <td>Si</td>";
							} else {
								html_str += "   <td>No</td>";
							}
							
							if (data.UbicacionActivoFijo.ubaf_vida_util == null) {
								html_str += "   <td>&nbsp;</td>";
							} else {
								html_str += "   <td>"+ data.UbicacionActivoFijo.ubaf_vida_util +"</td>";
							}
							
							html_str += "   <td>";
							html_str += "      <a href=\"javascript:;\" class=\"del_row\"><img src=\"/img/delete.png\" title=\"Eliminar\" alt=\"0\"></a>";
							html_str += "      <input name=\"data[DetalleActivoFijo]["+ row_count +"][deaf_codigo]\" class=\"ubaf_codigo\" value=\""+ ubaf_codigo +"\" type=\"hidden\" />";
							html_str += "      <input name=\"data[DetalleActivoFijo]["+ row_count +"][prod_id]\" class=\"prod_id\" value=\""+ data.Producto.prod_id +"\" type=\"hidden\" />";
							html_str += "      <input name=\"data[DetalleActivoFijo]["+ row_count +"][marc_id]\" class=\"marc_id\" value=\""+ data.Marca.marc_id +"\" type=\"hidden\" />";
							html_str += "      <input name=\"data[DetalleActivoFijo]["+ row_count +"][prop_id]\" class=\"prop_id\" value=\""+ data.Propiedad.prop_id +"\" type=\"hidden\" />";
							html_str += "      <input name=\"data[DetalleActivoFijo]["+ row_count +"][colo_id]\" class=\"colo_id\" value=\""+ data.Color.colo_id +"\" type=\"hidden\" />";
							html_str += "      <input name=\"data[DetalleActivoFijo]["+ row_count +"][mode_id]\" class=\"mode_id\" value=\""+ data.Modelo.mode_id +"\" type=\"hidden\" />";
							html_str += "      <input name=\"data[DetalleActivoFijo]["+ row_count +"][ubaf_serie]\" class=\"ubaf_serie\" value=\""+ data.UbicacionActivoFijo.ubaf_serie +"\" type=\"hidden\" />";
							html_str += "      <input name=\"data[DetalleActivoFijo]["+ row_count +"][situ_id]\" class=\"situ_id\" value=\""+ data.Situacion.situ_id +"\" type=\"hidden\" />";
							html_str += "      <input name=\"data[DetalleActivoFijo]["+ row_count +"][deaf_precio]\" class=\"ubaf_precio\" value=\""+ data.UbicacionActivoFijo.ubaf_precio +"\" type=\"hidden\" />";
							html_str += "      <input name=\"data[DetalleActivoFijo]["+ row_count +"][deaf_depreciable]\" class=\"ubaf_depreciable\" value=\""+ data.UbicacionActivoFijo.ubaf_depreciable +"\" type=\"hidden\" />";
							html_str += "      <input name=\"data[DetalleActivoFijo]["+ row_count +"][deaf_vida_util]\" class=\"ubaf_vida_util\" value=\""+ data.UbicacionActivoFijo.ubaf_vida_util +"\" type=\"hidden\" />";
							html_str += "      <input name=\"data[DetalleActivoFijo]["+ row_count +"][deaf_fecha_adquisicion]\" class=\"deaf_fecha_adquisicion\" value=\""+ data.UbicacionActivoFijo.ubaf_fecha_adquisicion +"\" type=\"hidden\" />";
							html_str += "   </td>";
							html_str += "</tr>";
							
						$("#table_detalle").append(html_str);
						row_count++;
					},
					complete: function() {
						del_row();
						$("#ajax_loader").hide();
					}
				});
				
				$("#table_detalle").show();
				$("#codigo").val("");
				$("#ubaf_codigo").val("");
			}
		}
	});
	
	checkRepetido = function(ubaf_codigo) {
		var ret = false;
	
		$("#table_detalle tr").each(function() {
			var ubaf_codigo_ = $(this).find(".ubaf_codigo").val();
			if (ubaf_codigo_ == ubaf_codigo) {
				ret = true;
			}
		});
		
		return ret;
	}
	
	del_row = function() {
		$(".del_row").each(function() {
			$(this).click(function() {
				$(this).parent().parent().remove();
			});
		});
	}
	
	$("#form_submit").click(function() {
		$("#FormTraslado").submit();
	});
});