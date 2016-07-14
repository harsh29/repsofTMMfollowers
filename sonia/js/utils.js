function doSearch(text) {
	if (text)
		window.location.href = "search.php?kw=" + encodeURIComponent(text);
	else
		window.location.href = "index.php";
}

function doJump(url, is_new_window) {
	if (url)
    if (is_new_window || false) window.open(decodeURIComponent(url));
		else  window.location.href = decodeURIComponent(url);
}

function getPara(name) {
	var reg = new RegExp("(^|&)"+ name +"=([^&]*)(&|$)");
	var r = window.location.search.substr(1).match(reg);
  if(r!=null)return  unescape(r[2]); return "";
}

function setCookie(name, value)
{
  var Days = 30;
  var exp = new Date();
  exp.setTime(exp.getTime() + Days * 24 * 60 * 60 * 1000);
  document.cookie = name + "=" + escape(value) + ";expires=" + exp.toUTCString();
}

function getCookie(name)
{
  var arr, reg = new RegExp("(^| )" + name + "=([^;]*)(;|$)");

  if (arr = document.cookie.match(reg))
    return unescape(arr[2]);
  else
    return null;
}

function delCookie(name)
{
  var exp = new Date();
  exp.setTime(exp.getTime() - 1);
  var cval = getCookie(name);
  if (cval != null)
    document.cookie = name + "=" + cval + ";expires=" + exp.toUTCString();
}

function getFileName(o) {
  var pos = o.lastIndexOf("\\");
  return o.substring(pos + 1);  
}