
<div id="foot" class="foot foot-unfull">
	<span style="font-size: 12px;">
		<?php import("core/utils/config.php"); echo getConfig("copy_right"); ?>
	</span>
</div>

<script>
	var ele = document.getElementById("foot");

	function getTop(ele){ 
		var offset = ele.offsetTop; 
		if (ele.offsetParent != null) offset += getTop(ele.offsetParent); 
		return offset; 
	}

	function setUnfull() {
		ele.style.position = "fixed";
		ele.style.margin = "1em 0 1em 0";
	}

	function unsetUnful() {
		ele.style.position = "relative";
		ele.style.margin = "2em 0 1em 0";
	}

	function setFoot() {
		//alert("");
		if (document.readyState == "complete") {
			//alert(getTop(ele) + ", " + (document.body.clientHeight + window.screen.height * 0.02));
			setUnfull();
			if (getTop(ele) <= (document.body.clientHeight + window.screen.height * 0.12))
				unsetUnful();

		}
	}

	document.onreadystatechange = setFoot;
	window.onresize = setFoot;
</script>