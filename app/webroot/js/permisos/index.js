// JavaScript Document
$(document).ready(function() {
	$("legend").each(function() {
		$(this).click(function() {
			var table = $(this).parent().find("table");
			var style = $(table).attr("style");
			
			if (style == "display: none;") {
				$(table).show();
			} else {
				$(table).hide();
			}
		});
	});
	
	$(".controller_name").each(function() {
		$(this).click(function() {
			var checkboxes = $(this).parent().parent().find("input[type=checkbox]");
			
			$(checkboxes).each(function() {
				var checked = $(this).attr("checked")? 1:0;
			
				if (checked == 1) {
					$(this).removeAttr("checked");
				} else {
					$(this).attr("checked", "checked");
				}
			});
		});
	});
});