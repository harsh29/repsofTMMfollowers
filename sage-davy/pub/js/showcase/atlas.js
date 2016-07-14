var initAtlas = (function ($) {
	return function (id, config) {
		var config = $.extend({
			color: 0xffffff,
			radius: 400,
			scalel: 4, /* lower scale */
			scaleu: 7, /* upper scale */
			opacity: function () { return randomAt(0.3, 0.7); },
			line_opacity: 0.09
		}, config);

		function randomAt(l, u) {
			return (Math.random() * (u - l)) + l;
		}

		var container = $("#" + id);
		var mouse_x = origin_x = config.x
			mouse_y = origin_y = config.y;

		var half_x = container.width() / 2;
		var half_y = container.height() / 2;

		var camera, scene, renderer;

		var PI2 = Math.PI * 2;

		var core = null;

		function init() {
			var color = config.color,
				particles, particle;
			var radius = config.radius, step = config;

			camera = new THREE.PerspectiveCamera(75, window.innerWidth / window.innerHeight, 1, 10000);
			camera.position.z = radius / 2;

			scene = new THREE.Scene();

			renderer = new THREE.CanvasRenderer({ alpha: true });
			renderer.setPixelRatio(window.devicePixelRatio);
			renderer.setClearColor(0x000000, 0);
			renderer.setSize(window.innerWidth, window.innerHeight);
			container.append(renderer.domElement);

			function material() {
				return new THREE.SpriteCanvasMaterial({
					color: color,
					opacity: config.opacity(),
					program: function (context) {
						if (core) core(context);
					}
				});
			}

			var geometry = new THREE.Geometry();

			function addPoint(x, y, z, scale) {
				particle = new THREE.Sprite(material());

				particle.scale.x = particle.scale.y = scale;
				/*
				x *= (Math.round(Math.random() * 10) + 10) / 10;
				y *= (Math.round(Math.random() * 10) + 10) / 10;
				z *= (Math.round(Math.random() * 10) + 10) / 10;
				*/

				var ratio = radius / Math.sqrt(Math.pow(x, 2) + Math.pow(y, 2) + Math.pow(z, 2));

				particle.position.x = x * ratio;
				particle.position.y = y * ratio;
				particle.position.z = z * ratio;

				scene.add(particle);

				return particle.position;
			}
		
			var pos = null;
		
			for (var i = 0; i < config.count; i++) {
				pos = addPoint(Math.random() * 2 - 1,
							   Math.random() * 2 - 1,
							   Math.random() * 2 - 1, randomAt(config.scalel, config.scaleu));
				if (i > config.line_mod && !(i % config.line_mod))
					geometry.vertices.push(pos);
			}

			var line = new THREE.Line(geometry, new THREE.LineBasicMaterial({ color: color, opacity: config.line_opacity }));
			scene.add(line);

			window.addEventListener('resize', onWindowResize, false);
			
			return function () {
				document.addEventListener('mousemove', onDocumentMouseMove, false);
				document.addEventListener('click', onDocumentMouseClick, false);
				document.addEventListener('touchstart', onDocumentTouchStart, false);
				document.addEventListener('touchmove', onDocumentTouchMove, false);
			};
		}

		function onWindowResize() {
			half_x = window.innerWidth / 2;
			half_y = window.innerHeight / 2;

			camera.aspect = window.innerWidth / window.innerHeight;
			camera.updateProjectionMatrix();

			renderer.setSize(window.innerWidth, window.innerHeight);

			return;
		}

		function onDocumentMouseMove(event) {
			mouse_x = (event.clientX - half_x) * 1;
			mouse_y = (event.clientY - half_y) * -1;

			return;
		}

		function onDocumentMouseClick(event) {
			mouse_x = origin_x;
			mouse_y = origin_y;

			return;
		}

		function onDocumentTouchStart(event) {
			if (event.touches.length > 1) {
				event.preventDefault();

				mouse_x = (event.touches[0].pageX - half_x) * 0.05;
				mouse_y = (event.touches[0].pageY - half_y) * 2;
			}

			return;
		}

		function onDocumentTouchMove(event) {
			if (event.touches.length == 1) {
				event.preventDefault();

				mouse_x = event.touches[0].pageX - half_x;
				mouse_y = event.touches[0].pageY - half_y;
			}

			return;
		}

		function animate() {
			requestAnimationFrame(animate);
			if (render) render();
			else return;

			return;
		}

		var render = function () {
			camera.position.x += (mouse_x - camera.position.x) * 0.1;
			camera.position.y += (-mouse_y + 200 - camera.position.y) * 0.05;
			camera.lookAt(scene.position);
			renderer.render(scene, camera);

			return;
		}

		var add_listener = init();
		var tmp1 = function (context) {
			context.beginPath();
			context.arc(0, 0, 0.5, 0, PI2, true);
			context.fill();

			return;
		};
		var tmp2 = render;
		render = null;
		animate();

		return {
			stop: function () {
				core = null;
				render = null;
				return;
			},
			start: function () {
				core = tmp1;
				render = tmp2;
				return;
			},
			addListener: add_listener,
			removeListener: function () {
				document.removeEventListener('mousemove', onDocumentMouseMove);
				document.removeEventListener('click', onDocumentMouseClick);
				document.removeEventListener('touchstart', onDocumentTouchStart);
				document.removeEventListener('touchmove', onDocumentTouchMove);

				return;
			}
		};
	}
})(jQuery);