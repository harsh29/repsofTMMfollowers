var initAudio = (function ($) {
	return function (config) {
		var config = $.extend({
			
		}, config);

		/*
			config {
				audio
			}
		 */
		
		var audio = config.audio;
		
		return {
			play: function () {
				audio.play();
				return;
			},

			pause: function () {
				audio.pause();
				return;
			},

			on: function (event, func) {
				if (event instanceof Array) {
					for (var e in event) {
						audio.addEventListener(e, func);
					}
				} else {
					audio.addEventListener(event, func);
				}
				return;
			}
		}
	};
})(jQuery);
