var initChangeColor = (function ($) {
	return function (id, opacity) {
		var colors = [
			[62, 35, 255],
			[60, 255, 60],
			[255, 35, 98],
			[45, 175, 230],
			[255, 0, 255],
			[255, 128, 0]
		];

		var step = 0;
		var cindices = [0, 1, 2, 3];

		//transition speed
		var speed = 0.002;

		function formatRGBA(r, g, b, a) {
			return "rgba(" + r + ", " + g + ", " + b + ", " + a + ")";
		}

		function updateGradient() {
			if ($ === undefined) return; // require JQuery

			var c0_0 = colors[cindices[0]];
			var c0_1 = colors[cindices[1]];
			var c1_0 = colors[cindices[2]];
			var c1_1 = colors[cindices[3]];

			var istep = 1 - step;
			var r1 = Math.round(istep * c0_0[0] + step * c0_1[0]);
			var g1 = Math.round(istep * c0_0[1] + step * c0_1[1]);
			var b1 = Math.round(istep * c0_0[2] + step * c0_1[2]);

			var color1 = formatRGBA(r1, g1, b1, opacity);

			var r2 = Math.round(istep * c1_0[0] + step * c1_1[0]);
			var g2 = Math.round(istep * c1_0[1] + step * c1_1[1]);
			var b2 = Math.round(istep * c1_0[2] + step * c1_1[2]);

			var color2 = formatRGBA(r2, g2, b2, opacity);

			$('#' + id).css({
				background: "-webkit-gradient(linear, left top, right top, from(" + color1 + "), to(" + color2 + "))"
			}).css({
				background: "-moz-linear-gradient(left, " + color1 + " 0%, " + color2 + " 100%)"
			});
			
			step += speed;
			if (step >= 1) {
				step %= 1;
				cindices[0] = cindices[1];
				cindices[2] = cindices[3];
				
				//pick two new target color indices
				//do not pick the same as the current one
				cindices[1] = (cindices[1] + Math.floor(1 + Math.random() * (colors.length - 1))) % colors.length;
				cindices[3] = (cindices[3] + Math.floor(1 + Math.random() * (colors.length - 1))) % colors.length;
				
			}
		}

		var handler = null;

		updateGradient();
		
		return {
			start: function () {
				if (handler)
					clearInterval(handler);

				handler = setInterval(updateGradient, 10);

				return;
			},
			stop: function () {
				if (handler)
					clearInterval(handler);

				handler = null;

				return;
			}
		};
	}
})(jQuery);