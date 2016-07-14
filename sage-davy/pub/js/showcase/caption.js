var initCaption = (function ($) {
	return function (config) {
		var config = $.extend({
			speed: 1,
			am_duration: 500,
			show_class: "sc-caption-show",
			if_end: true
		}, config);

	/*
		config {
			id, intervals, speed, am_duration, show_class
		}
	 */

		var id = config.id;
		var itval = config.intervals;
		var spd = config.speed;
		var am_duration = config.am_duration;
		var show_class = config.show_class;

		var procs = [];
		var orig = $("#" + id).html();

		var start_event = null;
		var end_event = null;

		var stopped = true;

		function reset() {
			$("#" + id)
			.html(orig)
			.children().removeClass("sc-caption-show");

			return;
		}

		return {
			setStartEvent: function (event) {
				start_event = event;
				return;
			},

			setEndEvent: function (event) {
				end_event = event;
				return;
			},

			reset: reset,

			start: function () {
				var parent = $("#" + id);
				var set = parent.children();
				var i, time = 0, parent;

				console.assert(set.length <= itval.length,
							   "too few intervals given");

				stopped = false;

				orig = parent.html();

				if (start_event)
					start_event();

				$(set[0]).addClass(show_class);

				for (i = 1; i < set.length; i++) {
					time += itval[i - 1] * 1000 / spd + am_duration * 2;
					procs.push(setTimeout(function (a) {
						return function () {
							$(set[a - 1]).removeClass(show_class);

							procs.push(setTimeout(function () {
								$(set[a - 1]).remove();
								$(set[a]).addClass(show_class);
							}, am_duration));
						};
					}(i), time));
				}

				time += itval[i - 1] * 1000 / spd + am_duration * 2;

				if (config.if_end) {
					procs.push(setTimeout(function () {
						$(set[i - 1]).removeClass(show_class);
						procs.push(setTimeout(function () {
							$(set[i - 1]).remove();

							reset();
							stopped = true;
							if (end_event)
								end_event();
						}, am_duration));
					}, time));
				} else {
					procs.push(setTimeout(function () {
						procs.push(setTimeout(function () {
							stopped = true;
							if (end_event)
								end_event();
						}, am_duration));
					}, time));
				}

				return;
			},

			stop: function () {
				if (stopped) return;

				var i;

				for (i = 0; i < procs.length; i++) {
					clearTimeout(procs[i]);
				}

				reset();

				stopped = true;
				if (end_event)
					end_event();

				return;
			},

			hasStopped: function () {
				return stopped;
			}
		};
	}
})(jQuery);