// JavaScript Document
function validchars(evt,allowedchars) {

	kcode=(evt.keyCode>0)?evt.keyCode:evt.charCode;
	if (allowedchars.indexOf(String.fromCharCode(kcode))==-1 && kcode!=8 && kcode!=9 && kcode!=13)
		return false;
	else 
		return true;
}

var num  = "1234567890";

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
	
    $("#prod_nombre").autocomplete({
		source: "/productos/searchTodo",
		select: function (event, ui) {
			$("#prod_id").val(ui.item.prod_id);
		}
	});
	
	$("#SolicitudTisoId").change(function() {
		var tiso_id = $(this).val();
		
		if (tiso_id == 1) {
			$("#SolicitudProvId").attr("disabled", "disabled");
			$("#SolicitudCecoIdHacia").removeAttr("disabled");
		} else if (tiso_id == 2) {
			$("#SolicitudCecoIdHacia").attr("disabled", "disabled");
			$("#SolicitudProvId").removeAttr("disabled");
		}
	});
	
	$("#SolicitudAdd").submit(function() {
	
		return true;
	});
	
	var count = 0;
	$("#nuevo_detalle").click(function() {
		var errors = false;
		var prod_nombre = $("#prod_nombre").val().trim();
		var prod_id = $("#prod_id").val().trim();
		var deso_cantidad = $("#deso_cantidad").val().trim();
		
		if (prod_nombre == "") {
			showTipsy($("#prod_nombre"), 'Debe seleccionar un producto');
			errors = true;
		}
		
		if (prod_id == "") {
			showTipsy($("#prod_nombre"), 'Debe seleccionar un producto');
			errors = true;
		}
		
		if (deso_cantidad == "") {
			showTipsy($("#deso_cantidad"), 'Debe ingresar la cantidad');
			errors = true;
		}
		
		if (errors == false) {
			var html_str  = "<tr>";
				html_str += "    <td>"+prod_nombre+"</td>";
				html_str += "    <td>"+deso_cantidad+"</td>";
				html_str += "    <td>";
				html_str += "        <input type=\"hidden\" name=\"data[DetalleSolicitud]["+count+"][prod_id]\" value=\""+prod_id+"\" />";
				html_str += "        <input type=\"hidden\" name=\"data[DetalleSolicitud]["+count+"][deso_cantidad]\" value=\""+deso_cantidad+"\" />";
				html_str += "        <a href=\"javascript:;\"><img class=\"del_row\" src=\"/img/delete.png\" border=\"0\" title=\"Eliminar\" /></a>";
				html_str += "    </td>";
				html_str += "</tr>";
			
			$("#table_detalle").append(html_str);
			del_row();
			count++;
			$("#table_detalle").show();
			clean_form();
		}
	});
	
	var del_row = function() {
		$(".del_row").each(function() {
			$(this).click(function() {
				$(this).parent().parent().parent().remove();
			});
		});
	}
	
	var clean_form = function() {
		$("#prod_nombre").val("");
		$("#prod_id").val("");
		$("#deso_cantidad").val("");
	}
});
