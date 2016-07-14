var u = {
	transform: function (elem, value) {
		$(elem).css({
			"transform": value,
			"-o-transform": value,
			"-moz-transform": value,
			"-ms-transform": value,
			"-webkit-transform": value
		});

		return;
	},

	/* set sc-hide class and something else */
	hide: function (elem, delay) {
		$(elem).addClass("sc-prehide");
		
		setTimeout(function () {
			$(elem).addClass("sc-hide");
		}, delay || 600);

		return;
	},

	show: function (elem) {
		$(elem).removeClass("sc-hide");

		setTimeout(function () {
			$(elem).removeClass("sc-prehide");
		}, 50);

		return;
	}
};
