<?php

include "inf/get_view.php";

$show_days = 7;

if (isset($_GET["show_days"]))
	$show_days = $_GET["show_days"];

?>

<!DOCTYPE html>
<html>
	<head>
		<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
		<meta http-equiv="Pragma" content="no-cache">
		<meta name="viewport" content="width=device-width, initial-scale=1.0">

		<title>星元 | Backend</title>

		<link rel="stylesheet" href="pub/fonts/font-awesome/css/font-awesome.min.css" />
		<link rel="stylesheet" href="pub/css/new/panel.css" />
		<link rel="stylesheet" href="pub/css/new/general.css" />
		<link rel="stylesheet" href="pub/css/new/chart.css" />

		<script src="pub/js/echarts.simple.min.js"></script>
		<script src="pub/js/jquery/jquery.js"></script>
	</head>

	<body class="ct-background">
		<div id="sc-panel" class="sc-panel ct-panel" style="z-index: 100">
			<span id="sc-link-1" class="sc-panel-title font-alegreya">
				<span class="font-extra-bold">Star</span> Dollar
				<span class="fa fa-bug" style="font-size: 0.7em;"></span>
			</span>

			<nav id="sc-link-set" class="sc-panel-link-left">
				<a id="sc-link-2" class="sc-panel-link font-alegreya">STATISTICS</a>
				<a id="sc-panel-line" class="sc-panel-line"></a>
			</nav>

			<div class="sc-panel-link-right">
				<!--a class="sc-panel-link font-alegreya" href="blog">BLOG</a-->
				<a class="sc-panel-link" style="margin: 0 0.3em 0;">
					<i class="fa fa-question-circle-o" style="font-size: 1.2em" onclick="triggerAbout()"></i>
				</a>
			</div>
		</div>
		<div class="ct-scroll">
			<div class="ct-headline">
				<span class="font-expletus">Visits</span>
			</div>
			<div id="ct-container" class="ct-container"></div>
		</div>

		<script>
			var pages = {
				gotoPage: function () {},
				unlock: function () {},
				lock: function () {}
			}
		</script>
		<script src="pub/js/showcase/about.js"></script>
		<script>
			function refreshLinePos(page) {
				setTimeout(function () {
					var cur_link = $("#sc-link-" + (page + 1));
					var line = $("#sc-panel-line");
					var left = cur_link.position().left + (cur_link.outerWidth(true) - line.width()) / 2;

					line.css("transform", "translateX(" + left + "px)");
				}, 300);

				return;
			}

			refreshLinePos(1);

			$("#sc-link-set").resize(function () {
				refreshLinePos(1);
			});

			$(window).resize(function () {
				refreshLinePos(1);
			});
		</script>
		<script>
			function getTodayDate()
			{
				var date = new Date();

				return date.getFullYear() + "-" + (date.getMonth() + 1) + "-" + date.getDate();
			}
		</script>
		<script>
			var chart = echarts.init(document.getElementById("ct-container"));

			var option = {
				title: {
					text: '星元 访问量统计'
				},
				tooltip : {
					trigger: 'axis'
				},
				legend: {
					data: [ '访问量' ]
				},
				grid: {
					containLabel: true
				},
				xAxis: {
					boundaryGap : false,
					data: JSON.parse(<?php echo "'" . getDisplayDate(date("Y-m-d"), $show_days) . "'"; ?>).reverse()
				},
				yAxis: {
					type : 'value'
				},
				series: [{
					name: '访问量',
					type: 'line',
					areaStyle: {normal: {}},
					label: {
						normal: {
							show: true,
							position: 'top'
						}
					},
					smooth: true,
					data: JSON.parse(<?php echo "'" . getDaysViewCountTo(date("Y-m-d"), $show_days) . "'"; ?>).reverse()
				}]
			};

			chart.setOption(option);
			$(window).resize(function () {
				chart = echarts.init(document.getElementById("ct-container"));
				chart.setOption(option);
			});
		</script>
	</body>
</html>
