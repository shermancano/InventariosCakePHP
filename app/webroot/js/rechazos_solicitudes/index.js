// JavaScript Document
$(document).ready(function() {
	var showTipsy = function(id, msg) {
		var options = {
			delayIn: 500,
			trigger: 'manual',
			gravity: 's',
			html: true,
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

	$(".motivo_text").each(function() {
		$(this).mouseenter(function() {
			var text = $(this).find("#motivo_span_text").html();
			showTipsy($(this), text);
		}).mouseleave(function() {
			hideTipsy($(this));
		});
	});
});