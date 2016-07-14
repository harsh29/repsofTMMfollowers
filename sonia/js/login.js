var XMLHttpReq = null;
var if_continue = false;
var messages = new Array();
var message_index = 0;
var is_success = false;

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

function sendAjaxRequest(url) {
    XMLHttpReq = createXMLHttpRequest();
    XMLHttpReq.open("GET", url, true);
    XMLHttpReq.onreadystatechange = processResponse;
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
      //eval(XMLHttpReq.responseText);
      if (XMLHttpReq.responseText.length > 1) {
        delCookie("session_id");
        setCookie("session_id", XMLHttpReq.responseText);
        if (getPara('cb'))
          doJump(getPara('cb') + '?' + getPara('cb_q'));
        else
          doJump("/");
      } else {
        switch (XMLHttpReq.responseText) {
          case "0":
            messageBox('Wrong user name or password, please try again', 'warning');
            break;
        }
      }
      //alert(sessionID);
      //messageBox(sessionID, "uk-alert-danger");
    }
  }
}

function checkAndLogin()
{
  var un_input = document.getElementById("user_name");
  var pw_input = document.getElementById("password");

  if ($(un_input).hasClass("uk-form-danger")) $(un_input).removeClass("uk-form-danger");
  if ($(pw_input).hasClass("uk-form-danger")) $(pw_input).removeClass("uk-form-danger");

  if (!un_input.value) {
    un_input.setAttribute("placeholder", "Empty User Name")
    $(un_input).addClass("uk-form-danger");
    return;
  }
  if (!pw_input.value) {
    pw_input.setAttribute("placeholder", "Empty Password")
    $(pw_input).addClass("uk-form-danger");
    return;
  }

  var user_name = un_input.value;
  var password = pw_input.value;

  sendAjaxRequest("back/login.php?un=" + user_name + "&pw=" + password);
}