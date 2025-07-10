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
	
	var count = 0;
	$("#nuevo_detalle").click(function() {
		var errors = false;
		var check = $("#check_busqueda").attr("checked") ? 1:0;
		
		if (check == 1) {
			var prod_nombre = $("#prod_nombre").val().trim();
			var prod_id = $("#prod_id").val().trim();
		}
		
		var unid_id = $("#unid_id").val().trim();
		var deor_cantidad = $("#deor_cantidad").val().trim();
		var deor_especifi_comprador = $("#deor_especifi_comprador").val().trim();
		var deor_especifi_proveedor = $("#deor_especifi_proveedor").val().trim();
		var deor_precio = $("#deor_precio").val().trim();
		var deor_descuento = $("#deor_descuento").val().trim();
		var deor_cargos = $("#deor_cargos").val().trim();
		
		if (check == 0) {
			var deor_nombre = $("#deor_nombre").val().trim();
		}
		
		if (prod_nombre == "" && check == 1) {
			showTipsy($("#prod_nombre"), 'Debe seleccionar un producto');
			errors = true;
		}
		
		if (deor_nombre == "" && check == 0) {
			showTipsy($("#deor_nombre"), 'Debe ingresar el nombre del producto');
			errors = true;
		}
		
		if (prod_nombre == "" && check == 1) {
			showTipsy($("#prod_nombre"), 'Debe seleccionar un producto');
			errors = true;
		}
		
		if (prod_id == "") {
			showTipsy($("#prod_nombre"), 'Debe seleccionar un producto');
			errors = true;
		}
		
		if (deor_cantidad == "") {
			showTipsy($("#deor_cantidad"), 'Debe ingresar la cantidad');
			errors = true;
		}
		
		if (deor_precio == "") {
			showTipsy($("#deor_precio"), 'Debe ingresar el precio');
			errors = true;
		}
		
		if (deor_descuento != "") {
			if (deor_descuento > 100) {
				showTipsy($("#deor_descuento"), 'El porcentaje máximo de descuento es 100%');
				errors = true;
			}
		}
			
		if (errors == false) {
			var html_str  = "<tr>";
			
				if (check == 0) {
					html_str += "    <td>"+deor_nombre+"</td>";
				} else {
					html_str += "    <td>"+prod_nombre+"</td>";
				}
				
				html_str += "    <td>"+deor_cantidad+"</td>";
				html_str += "    <td>"+deor_precio+"</td>";
				
				if (deor_descuento != "") {
					html_str += "    <td>"+deor_descuento+"%</td>";
				} else {
					html_str += "    <td>&nbsp;</td>";
				}
				
				html_str += "    <td>"+deor_cargos+"</td>";
				
				var total = (deor_cantidad*deor_precio);
				
				if (deor_descuento != "") {
					total = parseInt(total) - (parseInt(deor_cantidad)*parseInt(deor_precio)*parseInt(deor_descuento)/100);
				}
				
				if (deor_cargos != "") {
					total = parseInt(total) + parseInt(deor_cargos);
				}
				
				html_str += "    <td>"+ (total) +"</td>";
				html_str += "    <td>";
				
				if (check == 0) {
					html_str += "        <input type=\"hidden\" name=\"data[DetalleOrdenCompra]["+count+"][deor_nombre]\" value=\""+deor_nombre+"\" />";
				} else {
					html_str += "        <input type=\"hidden\" name=\"data[DetalleOrdenCompra]["+count+"][prod_id]\" value=\""+prod_id+"\" />";
				}
				html_str += "        <input type=\"hidden\" name=\"data[DetalleOrdenCompra]["+count+"][deor_cantidad]\" value=\""+deor_cantidad+"\" />";
				html_str += "        <input type=\"hidden\" name=\"data[DetalleOrdenCompra]["+count+"][unid_id]\" value=\""+unid_id+"\" />";
				html_str += "        <input type=\"hidden\" name=\"data[DetalleOrdenCompra]["+count+"][deor_especifi_comprador]\" value=\""+deor_especifi_comprador+"\" />";
				html_str += "        <input type=\"hidden\" name=\"data[DetalleOrdenCompra]["+count+"][deor_especifi_proveedor]\" value=\""+deor_especifi_proveedor+"\" />";
				html_str += "        <input type=\"hidden\" name=\"data[DetalleOrdenCompra]["+count+"][deor_precio]\" value=\""+deor_precio+"\" />";
				html_str += "        <input type=\"hidden\" name=\"data[DetalleOrdenCompra]["+count+"][deor_descuento]\" value=\""+deor_descuento+"\" />";
				html_str += "        <input type=\"hidden\" name=\"data[DetalleOrdenCompra]["+count+"][deor_cargos]\" value=\""+deor_cargos+"\" />";
				html_str += "        <a href=\"javascript:;\"><img class=\"del_row\" src=\"/img/delete.png\" border=\"0\" title=\"Eliminar\" /></a>";
				html_str += "    </td>";
				html_str += "</tr>";
			
			$("#table_detalle").append(html_str);
			count++;
			$("#table_detalle").show();
			del_row();
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
		$("#deor_cantidad").val("");
		$("#deor_especifi_comprador").val("");
		$("#deor_especifi_proveedor").val("");
		$("#deor_precio").val("");
		$("#deor_descuento").val("");
		$("#deor_cargos").val("");
		$("#unid_id").val("");
		$("#deor_nombre").val("");
	}
	
	$("#check_busqueda").click(function() {
		var check = $(this).attr("checked") ? 1:0;
		var str_html = "";
		
		if (check == 0) {
			str_html  = "<label>Nombre del Producto</label>";
			str_html += "<input type=\"text\" id=\"deor_nombre\" style=\"width:250px;\" />";
			str_html += "</span>";
		} else {
			str_html  = "<label>Producto/C&oacute;digo</label>";
			str_html += "<input type=\"text\" id=\"prod_nombre\" class=\"prod_nombre\" style=\"width:250px;\" />";
			str_html += "<input type=\"hidden\" id=\"prod_id\" />";
			str_html += "</span>";
		}
		
		$(".prod_change").html(str_html);
		$(".prod_nombre").autocomplete({
			source: "/productos/searchTodo",
			select: function (event, ui) {
				$("#prod_id").val(ui.item.prod_id);
			}
		});
	});
});
