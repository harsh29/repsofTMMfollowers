var XMLHttpReq = null;
var if_continue = false;
var messages = new Array();
var message_index = 0;
var is_success = true;

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

function checkUserName_proc() {
  if (XMLHttpReq.readyState == 4) {
    if (XMLHttpReq.status == 200) {
      //eval(XMLHttpReq.responseText);
      if (XMLHttpReq.responseText == "1") {
        var un_input = document.getElementById("user_name");
        $(un_input).addClass("uk-form-danger");
        messageBox("User name has already existed", "warning");
        is_success = false;
      }
      //alert(sessionID);
      //messageBox(sessionID, "uk-alert-danger");
    }
  }
}

function checkUserName()
{
  var un_input = document.getElementById("user_name");
  if ($(un_input).hasClass("uk-form-danger")) $(un_input).removeClass("uk-form-danger");

  if (!un_input.value) {
    $(un_input).addClass("uk-form-danger");
    un_input.placeholder = "Empty user name";
    is_success = false;
    return;
  }

  sendAjaxRequest("back/check_user.php?un=" + un_input.value, checkUserName_proc);
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
    messageBox("Too short password", "warning");
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
    messageBox("Too long self intro(less or equal than 164 characters)", "warning");
    is_success = false;
  }
}

function checkAll()
{
  is_success = true;
  checkUserName();
  checkPassword();
  checkNickName();
  checkSelfIntro();

  if (is_success) {
    document.getElementById("main_form").submit();
  }
}