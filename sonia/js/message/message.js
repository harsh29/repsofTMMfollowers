var request_obj = null;
var shown_msg = new Array();

function initMessage()
{
	request_obj = createXMLHttpRequest();
}

function messageBox(msg, status)
{
  UIkit.notify(msg, {status: status, timeout: 7000});
}

function getMessage_recv_proc()
{
	if (request_obj.readyState == 4 && request_obj.status == 200
			&& request_obj.responseText) {
		params = request_obj.responseText.split(":");
		if (shown_msg.indexOf(params[0]) == -1 && params[1]) {
			shown_msg.push(params[0]);
			messageBox("<i class='fa fa-quote-left'></i> " + params[1] + ":" + params[2], "info");
		}
	}
}

function getMessage()
{
	sendRequestByObject(request_obj, "back/message/bulletin.php", getMessage_recv_proc);
}

initMessage();
setInterval(getMessage, 5000);