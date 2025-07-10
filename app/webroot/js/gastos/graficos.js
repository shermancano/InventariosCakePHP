// JavaScript Document
google.load("visualization", "1", {packages:["corechart"]});
$(document).ready(function() {
	$("#GastoGraficoContNombre").autocomplete({
	     source: "/gastos/searchContratos",
		 select: function (event, ui) {
		 	$("#GastoGraficoContId").val(ui.item.cont_id)
		 }
	});
	
	$("#buscarGrafico").click(function() {
		var cont_id = $("#GastoGraficoContId").val();
		var data_table = new google.visualization.DataTable();
		var chart = new google.visualization.LineChart(document.getElementById('chart_div'));
		var chart_title;
		var no_data = false;
		
		$.ajax({
			url: "/gastos/getResumen/"+cont_id,
			cache: false,
			dataType: "json",
			beforeSend: function() {
				$("#ajax_loader").show();
			},
			success: function(data) {
				if (data == "" || data == null) { 
					alert('No hay gastos asociados para este contrato');
					no_data = true;
					return;
				}
			
				data_table.addColumn('string', 'Mes/Año');
				data_table.addColumn('number', 'Gastos Fijos');
				data_table.addColumn('number', 'Gastos Variables');
				data_table.addColumn('number', 'Gastos Presupuestados');
				data_table.addRows(data.info_res.length);
				chart_title = data.info_cont.cont_nombre;
				
				$.each(data.info_res, function(i, obj) {
					data_table.setValue(i, 0, obj.mes_anyo);
					data_table.setValue(i, 1, parseInt(obj.total_gasto_fijo));
					data_table.setValue(i, 2, parseInt(obj.total_gasto_variable));
					data_table.setValue(i, 3, parseInt(obj.total_gasto_presupuestado));
				});
			},
			complete: function() {
				$("#ajax_loader").hide();
				if (!no_data) {
					$("#chart_dialog").dialog({
						modal: true,
						title: "Grafico de Gastos - " + chart_title,
						width: 1100,
						open: function() {
							chart.draw(data_table, {height: 450, title: "Grafico de Gastos - " + chart_title});
						}
					});
				}
			}
		});
	});
});