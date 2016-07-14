var item_list = new Array("contribution", "favorite", "settings", "new", "inbox");
var board_list = new Array("contribution_board", "favorite_board","settings_board", "new_board", "inbox_board");
var info_list = new Array("nick_name", "self_intro", "group_id");
var has_password = false;
var XMLHttpReq = null;
var if_continue = false;
var messages = new Array();
var message_index = 0;
var is_success = false;

function chooseItem(id)
{
	$(document.getElementById(id)).addClass("profile_verticle_item-chosen");
}

function unchooseItem(id)
{
	$(document.getElementById(id)).removeClass("profile_verticle_item-chosen");
}

function toggleItem(id)
{
  $(document.getElementById(id)).toggleClass("profile_verticle_item-chosen");
}

function hasShown(id)
{
  return document.getElementById(id).style.display != "none";
}

function showBoard(id)
{
	document.getElementById(id).style.display = "";
}

function hideBoard(id)
{
	document.getElementById(id).style.display = "none";
}

function itemOnClick(id)
{
	chooseItem(id);
  showBoard(id + "_board");
  if (id == "settings" || id == "new") document.getElementById("background").style.display = "";
  else document.getElementById("background").style.display = "none";
  //if (id == "new") document.getElementById("title").focus();
	var i;
	for (i = 0; i < item_list.length; i++) {
		if (item_list[i] != id) {
			unchooseItem(item_list[i]);
			hideBoard(board_list[i]);
		}
	}
  window.location.hash = id;
	//setFoot();
}

function hideAll()
{
  var i;
  for (i = 0; i < item_list.length; i++) {
    unchooseItem(item_list[i]);
    hideBoard(board_list[i]);
  }
}

function sendAjaxRequest(url, proc) {
    XMLHttpReq = createXMLHttpRequest();
    XMLHttpReq.open("GET", url, true);
    XMLHttpReq.onreadystatechange = proc;
    XMLHttpReq.send(null);
}

function messageBox(msg, status)
{
  /*var box = document.createElement("div");
  box.id = "msg_" + message_index;
  box.setAttribute("data-uk-alert", "");
  $(box).addClass("uk-alert " + add_class);
  var close = document.createElement("a");
  close.href = "";
  $(close).addClass("uk-alert-close uk-close");
  var message = document.createElement("p");

  message.innerHTML = msg;
  box.appendChild(close);
  box.appendChild(message);
  messages.push(close);
  setTimeout('if (document.getElementById("' + box.id + '")) messages[' + message_index + '].click();', 5000);
  message_index++;
  document.getElementById("messageBox_field").appendChild(box);*/
  UIkit.notify(msg, {status: status, timeout: 3000});
}

function processResponse() {
  if (XMLHttpReq.readyState == 4) {
    if (XMLHttpReq.status == 200) {
      eval(XMLHttpReq.responseText);
      //alert(sessionID);
      //messageBox(sessionID, "uk-alert-danger");
    }
  }
}

function checkPassword()
{
  var pw_input = document.getElementById("password");
  if ($(pw_input).hasClass("uk-form-danger")) $(pw_input).removeClass("uk-form-danger");
  if (!pw_input.value) {
    $(pw_input).addClass("uk-form-danger");
    pw_input.placeholder = "Empty password";
    is_success = false;
  } else if (pw_input.value.length < 6) {
    $(pw_input).addClass("uk-form-danger");
    messageBox("Too short password", "uk-alert-danger");
    is_success = false;
  }
}

function checkNickName()
{
  var nn_input = document.getElementById("nick_name");
  if ($(nn_input).hasClass("uk-form-danger")) $(nn_input).removeClass("uk-form-danger");
  if (!nn_input.value) {
    $(nn_input).addClass("uk-form-danger");
    nn_input.placeholder = "Empty nick name";
    is_success = false;
  }
}

function checkSelfIntro()
{
  var si_input = document.getElementById("self_intro");
  if ($(si_input).hasClass("uk-form-danger")) $(si_input).removeClass("uk-form-danger");
  if (si_input.value.length > 164) {
    $(si_input).addClass("uk-form-danger");
    messageBox("Too long self intro(less or equal than 164 characters)", "uk-alert-danger");
    is_success = false;
  }
}

function encode(str)
{
	var i, ret = "";

	for (i = 0; i < str.length; i++) {
		switch (str.substr(i, 1)) {
			case "'":
				ret += "\\'";
				break;
			case '"':
				ret += '\\"';
				break;
			case "\\":
				ret += "\\\\";
				break;
			default:
				ret += str.substr(i, 1);
		}
	}

	return encodeURIComponent(ret);
}

function getSendRequest()
{
	var request_str = "/back/change_profile.php?";
	var i;
	for (i = 0; i < info_list.length; i++) {
		//alert(encodeURIComponent(document.getElementById(info_list[i]).value));
		request_str += info_list[i] + "="
		if (info_list[i] != "password")
			request_str += encode(document.getElementById(info_list[i]).value);
		else
			request_str += hex_md5(document.getElementById(info_list[i]).value).toUpperCase();

		if ((i + 1) < info_list.length) {
			request_str += "&";
		}
	}
	return request_str;
}

function checkAndUpdate()
{
  is_success = true;
  if (has_password)
    checkPassword();
  checkNickName();
  checkSelfIntro();

  //alert(getSendRequest());
  if (is_success)
    sendAjaxRequest(getSendRequest(), processResponse);
}

function passwordConfirm_proc()
{
	 if (XMLHttpReq.readyState == 4) {
    if (XMLHttpReq.status == 200) {
      if (XMLHttpReq.responseText == "1") {
      	info_list.push("password");
      	document.getElementById("password").style.display = "";
      	document.getElementById("show_password_button").style.display = "none";
        has_password = true;
      } else UIkit.modal.alert("Wrong password");
      //alert(sessionID);
      //messageBox(sessionID, "uk-alert-danger");
    }
  }
}

function passwordConfirm(proc)
{
	UIkit.modal.prompt("Type the original password to confirm:", "",
  function (val) {
    sendAjaxRequest("/back/check_password.php?pw=" + val, proc || passwordConfirm_proc);
  }, "", "password");
}