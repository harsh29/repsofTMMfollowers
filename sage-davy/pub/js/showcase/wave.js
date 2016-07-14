var initWave = (function ($) {
	return function (config) {
		var config = $.extend({
			opacity: 1
		}, config);

		/* config {
			container, speed, noise, phase
		} */

		var K = 2;
		var F = 6;
		var speed = config.speed || 0.1;
		var bspeed = speed;
		var noise = config.noise || 0;
		var phase = config.phase || 0;
		var container = $(config.container);

		config.width = container.width();
		config.height = container.height();

		var devicePixelRatio = window.devicePixelRatio || 1;

		var width = devicePixelRatio * (config.width || 320);
		var height = devicePixelRatio * (config.height || 100);

		var MAX = (height / 2) - 4;

		var canvas = document.createElement('canvas');
		
		canvas.width = width;
		canvas.height = height;
		canvas.style.width = (width / devicePixelRatio) + 'px';
		canvas.style.height = (height / devicePixelRatio) + 'px';

		container.append(canvas);

		var ctx = canvas.getContext('2d');

		var run = false;


		function onResize () {
			config.width = container.width();
			config.height = container.height();
			
			width = devicePixelRatio * (config.width || 320);
			height = devicePixelRatio * (config.height || 100);

			var noise = getNoise();
			MAX = (height / 2) - 4;
			setNoise(noise);

			canvas.width = width;
			canvas.height = height;
			canvas.style.width = (width / devicePixelRatio) + 'px';
			canvas.style.height = (height / devicePixelRatio) + 'px';

			return;
		}

		$(window).resize(onResize);

		function globalAttenuationFn(x) {
			return Math.pow(K * 4 / (K * 4 + Math.pow(x, 4)), K * 2);
		}

		function drawLine(attenuation, color, w) {
			ctx.moveTo(0, 0);
			ctx.beginPath();
			ctx.strokeStyle = color;
			ctx.lineWidth = w || 1;

			var x, y;
			for (var i = -K; i <= K; i += 0.01) {
				x = width * ((i + K) / (K * 2));
				y = height / 2 + noise * globalAttenuationFn(i) * (1 / attenuation) * Math.sin(F * i - phase);

				ctx.lineTo(x, y);
			}
			
			ctx.stroke();
		
			return;
		}

		function clear() {
			ctx.globalCompositeOperation = 'destination-out';
			ctx.fillRect(0, 0, width, height);
			ctx.globalCompositeOperation = 'source-over';

			return;
		}

		var alphas = [ 0.1, 0.2, 0.4, 0.6, 0.8 ];

		for (var i = 0; i < alphas.length; i++) {
			alphas[i] *= config.opacity;
		}

		function draw() {
			if (!run) return;

			phase = (phase + speed) % (Math.PI * 64);
			clear();
			drawLine(-2, 'rgba(255, 255, 255, ' + alphas[0] + ')');
			drawLine(-6, 'rgba(255, 255, 255, ' + alphas[1] + ')');
			drawLine(4, 'rgba(255, 255, 255, ' + alphas[2] + ')');
			drawLine(2, 'rgba(255, 255, 255, ' + alphas[3] + ')');
			drawLine(1, 'rgba(255, 255, 255, ' + alphas[4] + ')', 1.5);

			requestAnimationFrame(draw, 1000);

			return;
		}

		function start () {
			if (!run) {
				onResize();
				phase = 0;
				run = true;
				draw();
			}

			return;
		}

		function stop () {
			run = false;
			clear();
			return;
		}

		function setNoise(v) {
			v = Math.min(v, 1);
			noise = v * MAX;
			// speed = bspeed + v * 0.3;
			return;
		}

		function getNoise() {
			return noise / MAX;
		}

		var am_end = true;

		return {
			start: start,
			stop: stop,
			setNoise: setNoise,

			setSpeed: function (v) {
				speed = v;
				return;
			},

			set: function (noise, speed) {
				this.setNoise(noise);
				this.setSpeed(speed);

				return;
			},

			getNoise: getNoise,

			animateNoise: function (to) {
				if (!am_end) return;
				am_end = false;

				var dest = to;
				var start = this.getNoise();
				var step = 0.01;
				var sign = dest > start ? 1 : -1;
				var time = 0;
				var i;

				for (i = 0;
					 Math.abs(start - dest) > 0.03;
					 start += sign * Math.min(Math.abs(dest - start) / 2, step),
					 i++,
					 time += 16) {
					setTimeout((function (n) {
						return function () {
							wave1.setNoise(n);
						}
					})(start), time);
				}

				setTimeout(function () {
					am_end = true;
				}, time);
			},

			isRunning: function () {
				return run;
			}
		}
	}
})(jQuery);
