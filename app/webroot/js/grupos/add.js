$(document).ready(function() {
	$("#GrupoTifaId").change(function() {
		var tifa_id = $(this).val();
		var childSelect = $("#GrupoFamiId");
		
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