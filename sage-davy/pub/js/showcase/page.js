var initPage = (function ($) {
	return function (config) {
		var config = $.extend({
			start: -1,
			height: 100,
			am_duration: 600
		}, config);
		var i;

	/*
		config {
			start, scroll_id, count, height, am_duration, max_page
		}
	 */

		var id = function () { return config.scroll_id };
		var cur_page = config.start;

		var init_event = null;

		var page_init = new Array(config.count);
		var page_init_event = new Array(config.count);
		var page_enter_event = new Array(config.count);
		var page_leave_event = new Array(config.count);

		var com_enter_event = null;
		var com_leave_event = null;

		var on_change_page_event = null;

		var locked = false;

		for (i = 0; i < config.count; i++) {
			page_init[i] = false;
		}

		/* h unit: vh */
		function scrollDown(h) {
			u.transform($("#" + id()), "translateY(-" + h + "vh)");
			return;
		}

		return {
			init: function () {
				var i;
				
				page_init = new Array(config.count);
				for (i = 0; i < config.count; i++) {
					page_init[i] = false;
				}

				if (init_event)
					init_event();
				
				return;
			},

			setAllInitEvent: function (event) {
				init_event = event;
				return;
			},

			/* on change page event can determine whether to change page */
			setOnChangeEvent: function (event) {
				on_change_page_event = event;
				return;
			},

			lock: function () {
				locked = true;
				return;
			},

			unlock: function () {
				locked = false;
				return;
			},

			/* page: 0-n */
			gotoPage: function (page) {
				if (!(page >= 0 && page < config.count)) {
					console.assert(0, "unexpected page id " + page);
					return;
				}

				if (locked) return;

				if (on_change_page_event
					&& !on_change_page_event(page, cur_page))
					return;

				if (page == cur_page) return;

				if (page_leave_event[cur_page])
					page_leave_event[cur_page]();

				if (com_leave_event)
					com_leave_event(cur_page);

				cur_page = page;
				scrollDown(cur_page * config.height);

				if (!page_init[page]
					&& page_init_event[page]) {
					page_init[page] = true;
					page_init_event[page]();
				}

				if (page_enter_event[page]) {
					page_enter_event[page]();
				}

				if (com_enter_event)
					com_enter_event(page);

				return;
			},

			nextPage: function () {
				this.gotoPage(cur_page + 1);
				return;
			},

			prevPage: function () {
				if (cur_page)
					this.gotoPage(cur_page - 1);
				return;
			},

			addInitEvent: function (at, handler) {
				page_init_event[at] = handler;
				return;
			},

			addEnterEvent: function (at, handler) {
				page_enter_event[at] = handler;
				return;
			},

			addLeaveEvent: function (at, handler) {
				page_leave_event[at] = handler;
				return;
			},

			setCommonEnterEvent: function (handler) {
				com_enter_event = handler;
			},

			setCommonLeaveEvent: function (handler) {
				com_leave_event = handler;
			},

			curPage: function () { return cur_page; }
		};
	}
})(jQuery);