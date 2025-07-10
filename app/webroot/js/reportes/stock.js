// JavaScript Document
$(document).ready(function() {
	$("#ReporteProdNombre").autocomplete({
		source: function(request, response) {
			var vterm = $("#ReporteProdNombre").val();
			var tibi_id = $("#ReporteTibiId").val();
			var json_data;
			var search_url;
			
			if (tibi_id == 3) {
				search_url = '/productos/searchExistencias';
			} else if (tibi_id == 2) {
				search_url = '/productos/searchFungibles';
			} else if (tibi_id == 1) {
				search_url = '/productos/searchActivosFijos';
			} else {
				search_url = '/productos/searchTodo';
			}
			
			$.ajax({
				type: 'GET',
				url: search_url,
				data: {term: vterm},
				cache: false,
				async: false,
				dataType: 'json',
				success: function(data) {
					json_data = data;
				}
			});
			response(json_data);
		},
		select: function (event, ui) {
			$("#ReporteProdId").val(ui.item.prod_id);
		}
	});
	
	$("#FormReporte").submit(function() {
		
		var ceco_id = $("#ReporteCecoId").val();
		var tibi_id = $("#ReporteTibiId").val();
		var prod_id = $("#ReporteProdId").val();
		var checked = $("#ReportePdf").attr("checked") ? 1:0;
		
		if (prod_id == "") {
			prod_id = 'null';
		}
			
		if (checked == 1) {		
			if (tibi_id == 3) { // solo existencia
				var url = '/reportes/stock_pdf/'+ ceco_id +'/'+ tibi_id +'/'+ prod_id;
			} else if (tibi_id == 1) { // solo activos fijos
				var url = '/reportes/stock_pdf_activos_fijos/'+ ceco_id +'/'+ tibi_id +'/'+ prod_id;
			}
		} else {
	
			if (tibi_id == 3) { // solo existencia
				var url = '/reportes/stock_excel/'+ ceco_id +'/'+ tibi_id +'/'+ prod_id;
			} else if (tibi_id == 1) { // solo activos fijos
				var url = '/reportes/stock_excel_activos_fijos/'+ ceco_id +'/'+ tibi_id +'/'+ prod_id;
			}
		}
				
		$("#ReporteCecoId").val("");
		$("#ReporteTibiId").val("");
		$("#ReporteProdId").val("");
			
		location.href = url;
		return false;		
	});
});
