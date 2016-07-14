<!DOCTYPE html>
<html>
	<head>
		<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
		<meta http-equiv="Pragma" content="no-cache">
		<meta name="viewport" content="width=device-width, initial-scale=1.0">

		<title>星元 | The Star Dollar</title>

		<link rel="stylesheet" href="pub/fonts/font-awesome/css/font-awesome.min.css" />
		<link rel="stylesheet" href="pub/css/new/pages.css" />
		<link rel="stylesheet" href="pub/css/new/panel.css" />
		<link rel="stylesheet" href="pub/css/new/timeline.css" />
		<link rel="stylesheet" href="pub/css/new/animation.css" />
		<link rel="stylesheet" href="pub/css/new/com.css" />
		<link rel="stylesheet" href="pub/css/new/res.css" />
		<link rel="stylesheet" href="pub/css/new/loader.css" />
		<link rel="stylesheet" href="pub/css/new/about.css" />
		<link rel="stylesheet" href="pub/css/new/card.css" />
		<link rel="stylesheet" href="pub/css/new/wave.css" />
		<link rel="stylesheet" href="pub/css/new/vote.css" />
		<link rel="stylesheet" href="pub/css/new/general.css" />

		<script src="pub/js/jquery/jquery.js"></script>
		<script src="pub/js/jquery/jquery.mousewheel.min.js"></script>
	</head>

	<body>
		<div id="sc-panel" class="sc-panel sc-panel-hide" style="z-index: 1">
			<span id="sc-link-1" class="sc-panel-title font-alegreya" onclick="pages.gotoPage(0)">
				<span class="font-extra-bold">Star</span> Dollar
			</span>

			<nav id="sc-link-set" class="sc-panel-link-left">
				<a id="sc-link-2" class="sc-panel-link font-alegreya" onclick="pages.gotoPage(1)">WHAT IS IT</a>
				<a id="sc-link-3" class="sc-panel-link font-alegreya" onclick="pages.gotoPage(2)">HOW IT WORKS</a>
				<a id="sc-link-4" class="sc-panel-link font-alegreya" onclick="pages.gotoPage(3)">TIMELINE</a>
				<a id="sc-link-5" class="sc-panel-link font-alegreya" onclick="pages.gotoPage(4)">ACTIVITIES</a>
				<a id="sc-link-6" class="sc-panel-link font-alegreya" onclick="pages.gotoPage(5)">JOIN US</a>
				<a id="sc-panel-line" class="sc-panel-line"></a>
			</nav>

			<div class="sc-panel-link-right">
				<!--a class="sc-panel-link font-alegreya" href="blog">BLOG</a-->
				<a class="sc-panel-link" style="margin: 0 0.3em 0;">
					<i class="fa fa-question-circle-o" style="font-size: 1.2em" onclick="triggerAbout()"></i>
				</a>
			</div>
		</div>

		<div id="sc-msg-box" class="sc-msg-box sc-hide" style="z-index: 2">
			<span id="sc-msg-box-text" class="sc-msg-box-text font-expletus"></span>
		</div>
		
		<div id="sc-start"
			 class="fa fa-angle-double-down sc-cover-start-btn sc-hide"
			 onclick="pages.nextPage()" style="z-index: 1001"></div>
		<div id="sc-scroll" class="sc-scroll">
			<div id="sc-page-1" class="sc-page" style="z-index: 999">
				<div id="sc-bg-1" class="sc-canvas sc-bg-1" style="z-index: 999"></div>
				<div class="sc-full-screen sc-vertical-mid-container" style="z-index: 999">
					<span class="sc-headline font-tekai">
						<span id="sc-refresh-1" class="font-tekai sc-slide-down">星元</span><br>
						<span id="sc-refresh-2" class="font-expletus sc-slide-down">The Star Dollar</span>
					</span>
				</div>
			</div>
			<div id="sc-page-2" class="sc-page">
				<div id="sc-page-2-caption-1" class="sc-vertical-mid-container sc-slide-down"
					 style="z-index: 2; padding-top: 2em;">
					<span class="sc-headline font-tekai sc-caption sc-caption-show">
						什么是 <span style="color: #3A54AD">星元</span>？
					</span>
					<span class="sc-headline font-tekai sc-caption"
						  style="font-size: 5em">
						这是一款为<br>中学生设计的货币
					</span>
					<span class="sc-headline font-tekai sc-caption"
						  style="font-size: 4.8em">
						她是星元团队经过半年时间<br>精心设计的产物
					</span>
					<span class="sc-headline font-tekai sc-caption"
						  style="font-size: 4.8em">
						星元不是大家平时参加的那些<br>虚拟商赛里用的虚拟货币
					</span>
					<span class="sc-headline font-tekai sc-caption"
						  style="font-size: 4.6em">
						而是一种真正有 <span class="font-extra-bold" style="color: #6D0300">购买力</span> 的货币
					</span>
					<span class="sc-headline font-tekai sc-caption"
						  style="font-size: 4.8em">
						星元不依托于互联网生存
					</span>
					<span class="sc-headline font-tekai sc-caption"
						  style="font-size: 4.8em">
						她没有纵横交错的专业术语
					</span>
					<span class="sc-headline font-tekai sc-caption"
						  style="font-size: 4.8em">
						也没有扑朔迷离的虚假信息
					</span>
					<span class="sc-headline font-tekai sc-caption"
						  style="font-size: 5em">
						星元市场只有一种货品——
					</span>
					<span class="sc-headline font-tekai sc-caption"
						  style="font-size: 6.2em; color: #6D0300;">
						书
					</span>
				</div>
				<span id="sc-page-2-skip-btn"
					  class="sc-skip-btn fa fa-rocket sc-hide"
					  style="z-index: 99"
					  onclick="caption1.stop()">
				</span>
				<div id="sc-page-2-article" class="sc-article-container sc-rc-bg3 sc-img-full-page sc-hide"
					 style="z-index: 1000">
					<div class="sc-grid-right-1-2">
						<div id="sc-page-2-article-am-container" class="sc-vertical-mid">
							<span class="sc-article-title font-tekai sc-slide-left-down-strict">
								什么是 <span style="color: #3A54AD">星元</span>
							</span>
							<p class="sc-article font-mini-jiankai sc-slide-left-up-strict">
								星元是一款为中学生设计的虚拟货币，这是星元团队经过半年时间精心设计的产物。她不是大家平时参加的那些虚拟商赛里面用的那些虚拟货币，而是一种真正有购买力的货币，星元不是像比特币那样依托于互联网而得以生存的电子货币，而是存在于现实之中。我们设计了一个市场，一个全世界最小的市场，虽然小，但是意义非凡，没有纵横交错的专业术语，没有扑朔迷离的虚假信息，星元市场只有一种货品——图书。
							</p>
						</div>
					</div>
				</div>
			</div>
			<div id="sc-page-3" class="sc-page">
				<div id="sc-page-3-caption-1" class="sc-full-screen sc-vertical-mid-container sc-slide-down"
					 style="z-index: 2;  padding-top: 2em;">
					<span class="sc-headline font-tekai sc-caption"
						  style="font-size: 4.7em">
						你的家中有看完的 <span style="color: #3A54AD">旧书</span> 吗？
					</span>
					<span class="sc-headline font-tekai sc-caption"
						  style="font-size: 4.7em">
						没错，那就是星元系统的<br>动力来源！
					</span>
					<span class="sc-headline font-tekai sc-caption"
						  style="font-size: 4.7em">
						每当你贡献出一本图书时
					</span>
					<span class="sc-headline font-tekai sc-caption"
						  style="font-size: 4.5em">
						我们会综合各种因素，对书评出一个分数<br>
						<span style="position: relative; color: #D1AF26; margin-top: 1em;">
							<i class="fa fa-star sc-caption-am-1"></i>
							<i class="fa fa-star sc-caption-am-2"></i>
							<i class="fa fa-star sc-caption-am-3"></i>
							<i class="fa fa-star sc-caption-am-4"></i>
							<i class="fa fa-star-half-o sc-caption-am-5"></i>
						</span>
					</span>
					<span class="sc-headline font-tekai sc-caption"
						  style="font-size: 4.6em">
						并给予你不同数额的<span style="color: #3A54AD">星元</span><br>
						<i class="fa fa-money sc-caption-am-shake"
						   style="color: #2A742E; font-size: 1.6em; line-height: 1.5em;"></i>
					</span>
					<span class="sc-headline font-tekai sc-caption"
						  style="padding-top: 1em; font-size: 4.5em">
						而你就可以用手中的星元<br>购买其他人贡献的书了！<br>
						<span style="position: relative; font-size: 1.6em; line-height: 1.5em;">
							<i class="fa fa-usd sc-caption-am-1"
							   style="color: #2A742E;"></i>
							<i class="fa fa-arrow-right sc-caption-am-2"
							   style="color: #333333; font-size: 0.7em;"></i>
							<i class="fa fa-book sc-caption-am-3"
							   style="color: #BFAC31; font-size: 1.1em;"></i>
						</span>
					</span>
				</div>
				<span id="sc-page-3-skip-btn"
					  class="sc-skip-btn fa fa-rocket sc-hide"
					  style="z-index: 99"
					  onclick="caption2.stop()">
				</span>
				<div id="sc-page-3-article" class="sc-article-container sc-hide">
					<div class="sc-grid-right-1-2">
					</div>
					<div class="sc-grid-left-1-2" style="z-index: 1000">
						<div id="sc-page-3-article-am-container" class="sc-vertical-mid">
							<span class="sc-article-title font-tekai sc-slide-right-down-strict">
								<span style="color: #3A54AD">星元</span> 如何运转
							</span>
							<p class="sc-article font-mini-jiankai sc-slide-right-up-strict">
								如果你的家里有看不完的旧书，并且是值得推荐的好书的话，你可以把它们拿出来，卖给我们，我们会参考你自己对本书的打分和一些其他因素，给这本书一个综合评分，从一到五，也就是10-15星元，作为你卖掉这本书得到的收入，然后你就可以用手上的星元来购买这个系统里其他的书了！
							</p>
						</div>
					</div>
				</div>
			</div>
			<div id="sc-page-4" class="sc-page" style="overflow-y: auto;">
				<div style="margin-top: 5em;">
					<div id="sc-page-4-content" class="tl-container">
						<div class="tl-center-line sc-am-1"></div>
						<!--div class="tl-center-line-head"></div-->
						<span class="tl-box-container sc-am-1">
							<div class="tl-board-left tl-color-orange">
								<div class="tl-content-box tl-content-box-left">
									<span class="font-tekai" style="font-size: 1.5em">星元系统正式上线</span>
								</div>
								<div class="tl-arrow-right sc-am-2"></div>
							</div>
							<div class="tl-center-dot sc-am-2"></div>
							<div class="tl-date tl-date-right">
								<span class="font-alegreya sc-am-3">April 4th</span>
							</div>
						</span>
						<span class="tl-box-container sc-am-2">
							<div class="tl-board-right">
								<div class="tl-content-box tl-content-box-right font-tekai">
									<span style="font-size: 1.5em; line-height: 2em;">第一波书单来袭</span><br>
									<span class="font-milan">包括精彩小说和经典名著共35本</span>
								</div>
								<div class="tl-arrow-left sc-am-3"></div>
							</div>
							<div class="tl-center-dot sc-am-3"></div>
							<div class="tl-date tl-date-left sc-am-3">
								<span class="font-alegreya">April 13th</span>
							</div>
						</span>
						<span class="tl-box-container sc-am-3">
							<div class="tl-board-right tl-color-green">
								<div class="tl-content-box tl-content-box-right font-tekai">
									<span class="font-tekai" style="font-size: 1.5em; line-height: 2em;">原版书推荐第一期&nbsp;&nbsp;陆纬老师</span><br>
									<span class="font-expletus" style="font-size: 1.5em;">Twilight</span><br>
									<span class="font-alegreya" style="font-size: 1.3em;">Stephanie Meyer</span>
								</div>
								<div class="tl-arrow-left sc-am-4"></div>
							</div>
							<div class="tl-center-dot sc-am-4"></div>
							<div class="tl-date tl-date-left sc-am-4">
								<span class="font-alegreya">May 13th</span>
							</div>
						</span>
						<span class="tl-box-container sc-am-4">
							<div class="tl-board-left tl-color-purple">
								<div class="tl-content-box tl-content-box-left font-tekai">
									<span class="font-tekai" style="font-size: 1.5em; line-height: 2em;">原版书推荐第二期&nbsp;&nbsp;陈易卓</span><br>
									<span class="font-expletus" style="font-size: 1.5em;">What Money Can't Buy</span><br>
									<span class="font-alegreya" style="font-size: 1.3em;">Michael J. Sandel</span>
								</div>
								<div class="tl-arrow-right sc-am-5"></div>
							</div>
							<div class="tl-center-dot sc-am-5"></div>
							<div class="tl-date tl-date-right">
								<span class="font-alegreya sc-am-5">May 21th</span>
							</div>
						</span>
						<span class="tl-box-container sc-am-5" style="padding-bottom: 4em;">
							<div class="tl-board-right">
								<div class="tl-content-box tl-content-box-right font-tekai">
									<span class="font-tekai" style="font-size: 1.5em; line-height: 2em;">原版书推荐第三期&nbsp;&nbsp;杨若宇</span><br>
									<span class="font-expletus" style="font-size: 1.5em;">The Great Gatsby</span><br>
									<span class="font-alegreya" style="font-size: 1.3em;">F. Scott Fitzgerald</span>
								</div>
								<div class="tl-arrow-left sc-am-5"></div>
							</div>
							<div class="tl-center-dot sc-am-5"></div>
							<div class="tl-date tl-date-left sc-am-5">
								<span class="font-alegreya">May 27th</span>
							</div>
						</span>
					</div>
				</div>
			</div>
			<div id="sc-page-5" class="sc-page" style="background: #1B1A1C; overflow-y: auto;">
				<div id="sc-page-5-content" style="margin-top: 5em">
					<div id="sc-page-5-head-article">
						<span class="sc-headline sc-caption font-bright font-tekai sc-slide-down"
							  style="font-size: 4em; line-height: 1.4em; margin-top: 0.5em;">
							原版书推荐<br>
						</span>
						<span class="sc-headline font-mini-jiankai font-bright sc-slide-up"
							  style="font-size: 1.5em; margin-top: 1em;">
							从5月13号开始，星元团队每周都会推荐一本英文原版小说，<br>并邀请一位口语大神为大家朗读六到十分钟的一个片段<br>
							大家可以在了解好书的同时，练习并提高口语
						</span>
					</div>
					<div id="sc-page-5-card-list" class="sc-card-list">
						<div class="sc-card sc-card-1 sc-am-1">
							<span class="sc-card-headline font-expletus">Twilight</span>
							<!--span class="sc-card-headline font-milan">陆纬</span-->
							<!--div class="sc-img-full-page sc-rc-img14 sc-card-image"></div-->
							<span id="sc-card-1-audio-btn"
								  class="sc-card-audio-btn fa fa-ellipsis-h"
								  onclick="viewWechatMsg()"></span>
						</div>
						<div class="sc-card sc-card-2 sc-am-2">
							<span class="sc-card-headline font-expletus">The Great Gatsby</span>
							<audio id="sc-card-2-audio" class="sc-card-audio" controls>
								<source src="pub/audio/Davy.m4a" />
								<source src="pub/audio/Davy.acc" />
								<source src="pub/audio/Davy.ogg" />
								<source src="pub/audio/Davy.mp3" />
							</audio>
							<span id="sc-card-2-audio-btn"
								  class="sc-card-audio-btn fa fa-play"
								  onclick="audio1.play()">
							</span>
						</div>
						<div class="sc-card sc-card-3 sc-am-3">
							<span class="sc-card-headline font-expletus">What Money Can't Buy</span>
							<span id="sc-card-3-audio-btn"
								  class="sc-card-audio-btn fa fa-ellipsis-h"
								  onclick="viewWechatMsg()"></span>
						</div>
					</div>
				</div>
			</div>
			<div id="sc-page-6" class="sc-page">
				<div id="sc-page-6-caption-1" class="sc-vertical-mid-container">
					<span class="sc-headline sc-caption font-expletus font-extra-bold sc-hit"
						  style="font-size: 7em">
						<span class="font-tekai">加入我们！</span><br>
					</span>
					<span class="sc-headline sc-caption font-expletus"
						  style="padding-top: 1em; font-size: 4.7em;">
						<div style="width: 100%; text-align: center;">
							<div class="sc-img-full-page sc-rc-img12 sc-qr-code-image"></div>
						</div>
						<span class="font-tekai" style="font-size: 0.4em;">
							只需扫描二维码添加微信公众号<br>
							手机浏览可搜索公众号 <span class="font-expletus">STARSDOLLAR</span>
						</span>
					</span>
				</div>
			</div>
		</div>
		<div id="sc-wave-container" class="sc-wave-container sc-hide" onclick="audio1.pause()">
			<div id="sc-audio-loader" class="sc-loader font-expletus" style="z-index: 1002">
				<i class="fa fa-child fa-spin sc-loader-icon"></i><br>
			</div>
			<div id="sc-wave" class="sc-wave"></div>
		</div>
		<div id="sc-loader" class="sc-loader font-expletus" style="z-index: 1002">
			<i class="fa fa-cog fa-spin sc-loader-icon"></i><br>
			<!--span id="sc-loader-text" class="sc-loader-text">Just a sec :-)</span-->
		</div>
		<div id="sc-about" class="sc-about-layer sc-about-hide" style="z-index: 0" onclick="triggerAbout()">
			<div id="sc-about-box" class="sc-about-box">
				<span id="sc-about-content" class="font-expletus" style="font-size: 1.1em;">
					
					<!--
					Contact Us: WeChat: STARSDOLLAR<br><br><br>
					Thanks to the projects below:<br><br>
					Three.js (threejs.org)<br>
					JQuery MouseWheel (github.com/jquery/jquery-mousewheel/)<br><br>
					Thanks to the designers of all the fonts used:<br><br>
					<span class="font-expletus" style="font-size: 1em;">Expletus</span><br>
					<span class="font-alegreya" style="font-size: 1.1em;">Alegreya</span><br>
					<span class="font-ubuntu" style="font-size: 1em; line-height: 1.5em;">Ubuntu</span><br>
					<span class="font-tekai" style="font-size: 1.2em; line-height: 1.5em;">叶根友唐楷</span><br>
					<span class="font-milan" style="font-size: 1.2em; line-height: 1.5em;">小米兰亭</span><br>
					<span class="font-mini-jiankai" style="font-size: 1.2em; line-height: 1.5em;">迷你简硬笔楷书</span><br>
					<span class="fa fa-flag" style="font-size: 1.2em; line-height: 1.5em;"> <span class="font-ubuntu">Font Awesome</span></span>
					-->
				</span>
			</div>
		</div>

		<div id="vote-board" class="vote-board sc-hide" onclick="closeVoteBoard()">
			<div class="sc-full-screen sc-vertical-mid-container">
				<span class="sc-headline font-expletus vote-slogan" style="font-size: 5em">
					<span class="sc-slide-down" style="display: inline-block;">Love Our Project?</span><br>
					<span class="sc-slide-up-2 font-extra-bold" style="display: inline-block;">VOTE FOR US!</span><br>
					<a target="_blank" href="http://mp.weixin.qq.com/s?__biz=MzI5MDEzODk4MQ==&mid=2651775237&idx=1&sn=68f458c4aaa78f54ccb68864397eda32&scene=23&srcid=0602X8DRncgK9f9SoJPpvUu0#rd" class="fa fa-heart-o vote-heart" style="font-size: 2.6em;" onclick="gotoVote();"></a>
				</span>
			</div>
		</div>
		
		<div id="const-html-1" style="display: none">
			Made with <span class="fa fa-heart" style="color: #2980B9;"></span> by Mr. Yeti
		</div>

		<div id="const-html-2" style="display: none">
			Project leader and promoter: <span class="font-extra-bold">Davy Louis</span>
		</div>

		<div id="const-html-3" style="display: none">
			<span class='font-extra-bold' style='font-size: 1.5em'>
				<i class='fa fa-quote-left' style="transform: translateY(-4px)"></i>
				&nbsp;&nbsp;Hail Star Dollar&nbsp;<i class='fa fa-exclamation'></i>&nbsp;&nbsp;
				<i class='fa fa-quote-right' style="transform: translateY(4px)"></i>
			</span>
		</div>

		<!-- three.js & extensions -->
		<script src="pub/js/three/three.min.js"></script>
		<script src="pub/js/three/projector.js"></script>
		<script src="pub/js/three/canvas-renderer.js"></script>

		<script src="pub/js/utils.js"></script>

		<!-- showcase modules -->
		<script src="pub/js/showcase/atlas.js"></script>
		<script src="pub/js/showcase/chcolor.js"></script>
		<script src="pub/js/showcase/page.js"></script>
		<script src="pub/js/showcase/message.js"></script>
		<script src="pub/js/showcase/caption.js"></script>
		<script src="pub/js/showcase/wave.js"></script>
		<script src="pub/js/showcase/audio.js"></script>

		<script>
			u.hide(".sc-hide");
		</script>

		<!-- delarations -->
		<script>

			/*setInterval(function(){
				wave1.setNoise(10);
			}, 10);*/

			var atlas1 = initAtlas("sc-bg-1",
								   { color: 0xffffff,
									 radius: 400,
									 count: 300,
									 line_mod: 300,
									 x: 800, y: 800});

			var wave1 = initWave({
				container: "#sc-wave",
				opacity: 0.5
			});

			wave1.setSpeed(0.35);
			wave1.setNoise(0.1);

			function waveStart(wave) {
				if (!wave.isRunning()) {
					wave.start();
					var handler = setInterval(function () {
						if (!wave.isRunning()) {
							clearInterval(handler);
							return;
						}

						setTimeout(function () {
							var n = Math.random();
							if (n > 0.65)
								wave.animateNoise(n * 1.2);
							if (n < 0.3)
								wave.animateNoise(n * 0.8);
						}, Math.random() * 2000);
					}, 10);

					return handler;
				}

				return null;
			}

			function waveStop(wave) {
				wave.stop();
				return;
			}

			var atlas2 = initAtlas("sc-page-3-article",
								   { color: 0x0,
									 radius: 800,
									 count: 100,
									 line_mod: 1,
									 x: 200, y: 200});

			var pages = initPage({ scroll_id: "sc-scroll", count: 6, panel: "sc-panel" });

			var caption1 = initCaption({ id: "sc-page-2-caption-1", intervals: [3, 3, 3, 3, 3, 3, 3, 3, 3, 4] });
			var caption2 = initCaption({ id: "sc-page-3-caption-1", intervals: [3, 3, 2, 3, 3, 3] });
			var caption3 = initCaption({ id: "sc-page-6-caption-1", intervals: [3, 0], if_end: false });

			var chcolor1 = initChangeColor("sc-page-4", 0.4);
			// var chcolor2 = initChangeColor("sc-page-5", 0.8);

			var msg = initMessageBox({ id: "sc-msg-box", text_id: "sc-msg-box-text" });

			var audio1 = initAudio({ audio: document.getElementById("sc-card-2-audio") });

			var has_end_view = false;

			var openVoteBoard = null;
			var closeVoteBoard = null;
			var gotoVote = null;

			var dev_mode = false;
		</script>

		<script src="pub/js/showcase/about.js"></script>

		<!-- settings -->
		<script>
			function viewWechatMsg() {
				msg.show("Add Wechat account for more", { timeout: 3500 });
				return;
			}

			audio1.on("play", function () {
				var btn = $("#sc-card-2-audio-btn");

				pages.lock();
				u.show("#sc-wave-container");

				btn.addClass("sc-card-audio-btn-played");

				btn.removeClass("fa-play");
				btn.addClass("fa-pause");

				msg.show("Click anywhere to pause", { timeout: 0 });

				return;
			});

			audio1.on("pause", function () {
				var btn = $("#sc-card-2-audio-btn");

				u.hide("#sc-wave-container");
				
				btn.removeClass("sc-card-audio-btn-played");
				btn.addClass("fa-play");
				btn.removeClass("fa-pause");

				pages.unlock();
				msg.hide();

				return;
			});

			audio1.on("waiting", function () {
				u.show("#sc-audio-loader");
				waveStop(wave1);
			});

			audio1.on("emptied", function () {
				u.show("#sc-audio-loader");
				waveStop(wave1);
			});

			audio1.on("playing", function () {
				u.hide("#sc-audio-loader");
				waveStart(wave1);
			});

			(function ($) {
				/***************** init event *****************/
				pages.addInitEvent(1, function () { caption1.start(); });
				pages.addInitEvent(2, function () { caption2.start(); });
				pages.addInitEvent(5, function () { setTimeout(caption3.start, 1000); });

				/***************** enter event *****************/
				pages.addEnterEvent(0, function () {
					atlas1.start();
					$("#sc-panel").addClass("sc-panel-hide");
					pages.init();

					if (dev_mode) {
						u.show("#sc-start");
					}

					setTimeout(function () {
						setTimeout(function () {
							if (pages.curPage() == 0) {
								atlas1.addListener();
								if (!dev_mode)
									u.show("#sc-start");
							}
						}, 2000);
					}, 600);
				});

				pages.addEnterEvent(2, function () {
					atlas2.start();
					atlas2.addListener();
				});

				pages.addEnterEvent(3, function () {
					chcolor1.start();
				});

				pages.addEnterEvent(4, function () {
					// chcolor2.start();
				});

				/***************** leave event *****************/
				pages.addLeaveEvent(0, function () {
					u.hide("#sc-start");
					$("#sc-panel").removeClass("sc-panel-hide");

					atlas1.removeListener();
					setTimeout(function () {
						atlas1.stop();
					}, 600);
				});
				pages.addLeaveEvent(2, function () {
					atlas2.stop();
					atlas2.removeListener();
				});
				pages.addLeaveEvent(3, function () {
					$("#sc-page-4").animate({scrollTop: 0}, 'fast');
					chcolor1.stop();
				});
				pages.addLeaveEvent(4, function () {
					$("#sc-page-5").animate({scrollTop: 0}, 'fast');
					// chcolor2.stop();
				});

				/***************** common enter event *****************/
				pages.setCommonEnterEvent(function (page) {
					if (page != 5)
						$("#sc-page-" + (page + 1)).addClass("sc-show");
					else
						setTimeout(function () {
							$("#sc-page-" + (page + 1)).addClass("sc-show");
						}, 1000);

					if (page != 0) {
						$("#sc-link-" + (page + 1)).addClass("sc-panel-link-select");
					}
				});

				/***************** common leave event *****************/
				pages.setCommonLeaveEvent(function (page) {
					if (page != 0) {
						$("#sc-link-" + (page + 1)).removeClass("sc-panel-link-select");
					}
				});


				/***************** all init event *****************/
				pages.setAllInitEvent(function () {
					$(".sc-show").removeClass("sc-show"); // remove all sc-show classes

					u.hide("#sc-page-2-article");
					u.hide("#sc-page-3-article");

					if (document.getElementById("sc-card-2-audio").currentTime)
						document.getElementById("sc-card-2-audio").currentTime = 0;

					caption3.reset();
				});

				function refreshLinePos(page) {
					if (page != 0) {
						setTimeout(function () {
							var cur_link = $("#sc-link-" + (page + 1));
							var line = $("#sc-panel-line");
							var left = cur_link.position().left + (cur_link.outerWidth(true) - line.width()) / 2;

							line.css("transform", "translateX(" + left + "px)");
						}, 300);
					}

					return;
				}

				function refreshCard() {
					var content = $("#sc-page-5-content");
					var card_list = $("#sc-page-5-card-list");
					var panel_height = content.outerHeight(true) -  content.innerHeight();
					var article_height = $("#sc-page-5-head-article").outerHeight(true);
					var margin_height = card_list.outerHeight(true) - card_list.innerHeight();
					var card_height = $("#sc-page-5").height() - panel_height - article_height - margin_height;

					$(".sc-card").css("height", card_height + "px");

					var headline_height = $(".sc-card-headline").height();
					var btn_height = $(".sc-card-audio-btn").height();

					$(".sc-card-audio-btn").css("margin-top", (card_height - headline_height - btn_height) / 2 + "px");

					return;
				}

				$("#sc-link-set").resize(function () {
					refreshLinePos(pages.curPage());
				});

				$(window).resize(function () {
					refreshLinePos(pages.curPage());
					// setTimeout(refreshCard, 600);
				});

				// $(window).ready(refreshCard);

				/***************** on change event *****************/
				pages.setOnChangeEvent(function (page, cur) {
					if (dev_mode) {
						setTimeout(function () {
							$("#sc-panel").removeClass("sc-panel-on-page-" + (cur + 1));
							$("#sc-panel").addClass("sc-panel-on-page-" + (page + 1));
						}, 600);
					}

					if (dev_mode) {
						refreshLinePos(page);
						return true;
					}
					var ret = caption1.hasStopped() && caption2.hasStopped() && caption3.hasStopped();
					if (!ret)
						msg.show("We appreciate your patience ^.^", { timeout: 2000 });
					else {
						setTimeout(function () {
							$("#sc-panel").removeClass("sc-panel-on-page-" + (cur + 1));
							$("#sc-panel").addClass("sc-panel-on-page-" + (page + 1));
						}, 600);
						refreshLinePos(page);
					}

					return ret;
				});

				/***************** caption on page 2 *****************/
				caption1.setStartEvent(function () {
					if (dev_mode) {
						u.show("#sc-page-2-skip-btn");
						return;
					}

					setTimeout(function () {
						u.show("#sc-page-2-skip-btn");
					}, 3000);
					
					return;
				});
				caption1.setEndEvent(function () {
					u.show("#sc-page-2-article");
					$("#sc-page-2-article").addClass("sc-show");
					u.hide("#sc-page-2-skip-btn");
					$("#sc-page-2-article-am-container").addClass("sc-show");
					return;
				});

				/***************** caption on page 3 *****************/
				caption2.setStartEvent(function () {
					if (dev_mode) {
						u.show("#sc-page-3-skip-btn");
						return;
					}

					setTimeout(function () {
						u.show("#sc-page-3-skip-btn");
					}, 3000);
					return;
				});
				caption2.setEndEvent(function () {
					atlas2.start();
					atlas2.addListener();
					u.show("#sc-page-3-article");
					$("#sc-page-3-article").addClass("sc-show");
					u.hide("#sc-page-3-skip-btn");
					$("#sc-page-3-article-am-container").addClass("sc-show");
					return;
				});

				/***************** caption on page 5 *****************/
				caption3.setEndEvent(function () {
					if (!has_end_view) {
						has_end_view = true;
						setTimeout(function () {
							openVoteBoard();
						}, 2000);
					}
					return;
				});

				openVoteBoard = function () {
					u.show("#vote-board");
					$("#vote-board").addClass("sc-show");

					setTimeout(function () {
						$(".vote-slogan").addClass("vote-slogan-move-up");
						setTimeout(function () {
							$(".vote-heart").addClass("vote-heart-show");
						}, 600);
					}, 2800);

					return;
				}

				closeVoteBoard = function () {
					u.hide("#vote-board");
					$("#vote-board").removeClass("sc-show");
					return;
				}

				gotoVote = function () {
					$(".vote-heart").removeClass("fa-heart-o");
					$(".vote-heart").addClass("fa-heart");
					setTimeout(closeVoteBoard, 1000);
					return;
				}

			})(jQuery);
		</script>

		<!-- events -->
		<script>
			(function ($) {
				function delayBind(timeout) {
					$(window).unbind('mousewheel', onMouseWheel);
					// document.removeEventListener('touchstart', onTouchStart);
					// document.removeEventListener('touchend', onTouchEnd);
					//  document.removeEventListener('touchmove', onTouchMove);

					if (timeout >= 0) {
						setTimeout(function () {
							$(window).bind('mousewheel', onMouseWheel);
							// document.addEventListener('touchstart', onTouchStart);
							// document.addEventListener('touchend', onTouchEnd);
							// document.addEventListener('touchmove', onTouchMove);
						}, timeout);
					}
				}

				function onMouseWheel(event, delta) {
					if (pages.curPage() == 3
						|| pages.curPage() == 4) {
						var p = pages.curPage();
						var content = $("#sc-page-" + (p + 1) + "-content");
						var page = $("#sc-page-" + (p + 1));

						delayBind(2000);
						if (delta < 0
							&& page.scrollTop() >= content.outerHeight(true) - page.height()) {
							pages.nextPage();
						} else if (delta > 0
								   && page.scrollTop() <= 0) {
							pages.prevPage();
						}
					} else {
						delayBind(1000);
						if (delta < 0) {
							pages.nextPage();
						} else if (delta > 0) {
							pages.prevPage();
						}
					}

					return true;
				}

				/*for (var i = 2; i <= 5; i++) {
					$("#sc-page-" + i).bind("swipeup", onSwipeUp);
					$("#sc-page-" + i).bind("swipedown", onSwipeDown);
				}*/

				var start_y, end_y, trigger_length = window.screen.height * 0.2;

				function onTouchStart(event) {
					start_y = event.pageY;

					return;
				}

				function onTouchMove(move) {
					if (Math.abs(event.pageY - start_y) >= trigger_length) {
						event.preventDefault();
					}
					return;
				}

				function onTouchEnd(event) {
					end_y = event.pageY;
					
					if (Math.abs(end_y - start_y) >= trigger_length / 2) {
						event.preventDefault();
					}

					if (end_y - start_y >= trigger_length) {
						onMouseWheel(event, 1);
					} else if (end_y - start_y <= -trigger_length) {
						onMouseWheel(event, -1);
					}

					return;
				}

				var has_loaded = false;

				/*
				$(document).on('touchstart', function() {
					if (!has_loaded) {
						alert("hey!");
						document.getElementById("sc-card-2-audio").load();
						document.getElementById("sc-card-2-audio").play();
						has_loaded = true;
					}
					return;
				});*/

				/*setInterval(function () {
					var audio = document.getElementById("sc-card-2-audio");
					var range = audio.buffered;
					alert(audio.networkState + ", " + audio.duration + ", " + (range.end(range.length - 1) / audio.duration));
					var range = audio.played;
					alert("played: " + range.end(range.length - 1));
				}, 5000);*/

				window.onload = function () {
					/*jQuery(window).bind('swipeleft', function(event_, data_){
						alert("swipe left!");
					});

					jQuery(window).bind('swiperight', function(event_, data_){
						alert("swipe right!");
					});*/

					if (!dev_mode)
						delayBind(3000);
					else
						delayBind(0);

					setTimeout(function () {
						u.hide("#sc-loader");
						pages.gotoPage(0);
						// $("#vote-board").addClass("sc-show");
					}, 200);
				};
			})(jQuery);
		</script>
	</body>
</html>
