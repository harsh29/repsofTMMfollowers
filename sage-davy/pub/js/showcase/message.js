var initMessageBox = (function ($) {
	return function (config) {
		var config = $.extend({
			show_class: "sc-msg-box-show"
		}, config);

	/*
		config {
			id, text_id
		}
	 */

		var id = config.id;
		var text_id = config.text_id;
		var show_cls = config.show_class;

		var hide_proc = null;

		function hide() {
			clearTimeout(hide_proc);

			$("#" + id)
			.removeClass(config.show_class);

			u.hide("#" + id);

			return;
		}

		return {
			show: function (text, config) {
				var config = $.extend({
					font: "font-alegreya",
					text_css: {},
					box_css: {},
					timeout: 0
				}, config);

				$("#" + text_id)
				.html(text)
				.addClass(config.font)
				.css(config.text_css);

				$("#" + id)
				.css(config.box_css)
				.addClass(show_cls)
				.click(hide);

				u.show("#" + id);

				if (config.timeout > 0) {
					hide_proc = setTimeout(hide, config.timeout + 300);
				}

				return;
			},
			
			hide: hide
		};
	}
})(jQuery);
