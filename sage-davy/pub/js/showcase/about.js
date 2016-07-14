var triggerAbout = null;

(function ($) {
	var handler = null;

	function show() {
		$("#sc-about").removeClass("sc-about-hide");

		setTimeout(function () {
			$("#sc-panel").addClass("sc-panel-shaded");
			$("#sc-about-box").removeClass("sc-about-shake");
			$("#sc-about-content").html($("#const-html-1").html());
			$("#sc-about").addClass("sc-about-show");
			
			pages.lock();

			handler = setTimeout(function () {
				$("#sc-about-content").html($("#const-html-2").html());
				handler = setTimeout(function () {
					$(".sc-about-box").addClass("sc-about-shake");
					$("#sc-about-content").html($("#const-html-3").html());
					handler = setTimeout(hide, 5000);
				}, 4700);
			}, 3600);

			triggerAbout = hide;
		}, 600);

		return;
	}

	function hide() {
		if (handler)
			clearTimeout(handler);
		$("#sc-about").removeClass("sc-about-show");
		$("#sc-panel").removeClass("sc-panel-shaded");

		setTimeout(function () {
			$("#sc-about").addClass("sc-about-hide");
		}, 600);

		pages.unlock();

		triggerAbout = show;

		return;
	}

	triggerAbout = function () {};

	hide();
})(jQuery);
