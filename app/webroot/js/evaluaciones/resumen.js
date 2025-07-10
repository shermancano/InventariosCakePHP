// JavaScript Document
$(document).ready(function() {
	$("#EvaluacionResumenContNombre").autocomplete({
	     source: "/evaluaciones/searchEvaluaciones",
		 select: function (event, ui) {
		 	$("#EvaluacionResumenEvalId").val(ui.item.eval_id)
		 }
	});
	
	$("#buscarResumen").click(function() {
		var eval_id = $("#EvaluacionResumenEvalId").val();
		
		if (eval_id != "") {
			$.ajax({
				type: "GET",
				url: "/evaluaciones/getResumen/"+eval_id,
				cache: false,
				dataType: "json",
				beforeSend: function() {
					$("#ajax_loader").show();
					$("#resumen_eval").slideUp("slow");
				},
				success: function(data) {
					$("#prov_nombre").html(data.info_cont.prov_nombre);
					$("#cont_nombre").html(data.info_cont.cont_nombre);
					
					var html_str  = "<table>";
						html_str += "   <tr>";
						html_str += "      <th width=\"50%\" colspan=\"2\"></th>";
						html_str += "      <th width=\"12%\">NOTA</th>";
						//html_str += "      <th width=\"12%\">NO APLICA</th>";
						html_str += "      <th width=\"12%\">PONDERACION</th>"; 
						html_str += "   </tr>";
					
					$.each(data.info_res, function(i, obj) {
						if (parseInt(obj.deev_ponderacion) != 0) {
							html_str += "<tr>";
							//html_str += "   <th colspan=\"4\">"+obj.tiit_descripcion+"</th>";
							html_str += "   <th colspan=\"3\">"+obj.tiit_descripcion+"</th>";
							html_str += "   <th colspan=\"1\">"+obj.deev_ponderacion+"%</th>";
							html_str += "</tr>";
							
							$.each(obj.Nota, function(i, item) {
								html_str += "<tr>";
								html_str += "   <td>"+(i+1)+"</td>";
								html_str += "   <td>"+item.item_descripcion+"</td>";
								
								if (item.nota_nota == null || item.nota_nota == "") {
									//html_str += "   <td>&nbsp;</td>";
									//html_str += "   <td>X</td>";
								} else {
									html_str += "   <td>"+item.nota_nota+"</td>";
									html_str += "   <td>&nbsp;</td>";
								}
								
								//html_str += "   <td>&nbsp;</td>";
								html_str += "</tr>";
							});
							
							html_str += "<tr>";
							html_str += "   <th colspan=\"2\">Total</th>";
							html_str += "   <th colspan=\"1\">"+(obj.promedio)+"</th>";
							//html_str += "   <th colspan=\"1\">&nbsp;</th>";
							html_str += "   <th colspan=\"1\">"+(obj.pond)+"</th>";
							html_str += "</tr>";
						}
					});
					
					html_str += "<tr>";
					html_str += "   <th colspan=\"4\"><br />Observaciones</th>";
					html_str += "</tr>";
					html_str += "<tr>";
					html_str += "   <td colspan=\"4\">"+data.info_cont.eval_observaciones+"</td>";
					html_str += "</tr>";
					
					
					html_str += "<tr>";
					//html_str += "   <th colspan=\"4\"><br />Nota Final</th>";
					html_str += "   <th colspan=\"3\"><br />Nota Final</th>";
					html_str += "   <th colspan=\"1\"><br />"+data.total_pond+"</th>";
					html_str += "</tr>";
					
					html_str += "</table>";
					
					$("#resumen_eval_table").html(html_str);
				},
				complete: function() {
					$("#resumen_eval").slideDown('slow');
					$("#ajax_loader").hide();
				}
			});
			
		}
	});
	
	$(".excel_export").click(function() {
		var eval_id = $("#EvaluacionResumenEvalId").val();
		
		if (eval_id != "") {
			location.href = "/evaluaciones/excel/"+eval_id;
		}
	});
	
	$(".pdf_export").click(function() {
		var eval_id = $("#EvaluacionResumenEvalId").val();
		
		if (eval_id != "") {
			location.href = "/evaluaciones/pdf/"+eval_id;
		}
	});
});