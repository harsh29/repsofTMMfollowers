var inbox_request_obj = null;
var message_selected = new Array();

function deleteMessage_proc(index) {
	document.getElementById("inbox-item" + index).style.display = "none";
	inbox_message_count--;

	var i, j, if_stop = false;
	for (i = 0; i < inbox_message_block.length && !if_stop; i++) {
		for (j = 0; j < inbox_message_block[i].length && !if_stop; j++) {
			if (inbox_message_block[i][j] == index) {
				inbox_message_block[i].remove(j);
				if_stop = true;
			}
		}
	}

	refreshMessage();
}

function deleteMessage(index) {
	inbox_request_obj = createXMLHttpRequest();
	sendRequestByObject(inbox_request_obj, "/back/message/delete_message.php?index=" + index,
						function () {
							if (inbox_request_obj.readyState == 4 && inbox_request_obj.status == 200) {
								deleteMessage_proc(index);
							}
						});
}

function refreshMessage() {
	if (inbox_message_count <= 0) {
		document.getElementById("no-message").style.display = "";
	}

	var i;
	for (i = 0; i < inbox_message_block.length; i++) {
		if (inbox_message_block[i].length <= 0) {
			document.getElementById("inbox-block" + i).style.display = "none";
		}
	}
}

function showInboxEditPanel()
{
  var panel = document.getElementById("inbox-edit-panel");
  panel.style.display = "";
  $("#inbox-edit-panel").animate({top: "0"}, 300, "swing");
  $("#inbox_board").animate({paddingTop: "5em"}, 300, "swing");
}

function hideInboxEditPanel()
{
  var panel = document.getElementById("inbox-edit-panel");
  $("#inbox-edit-panel").animate({top: "-5em"}, 300, "swing");
  $("#inbox_board").animate({paddingTop: "0"}, 300, "swing");
}

function messageToggle(id)
{
  if (message_selected.indexOf(id) != -1) { // exists
    message_selected.remove(message_selected.indexOf(id));
  } else message_selected.push(id);

  if (message_selected.length != 0) {
    showInboxEditPanel();
  } else hideInboxEditPanel();

  classie.toggle(document.getElementById("inbox-item" + id), "inbox-item-selected");
  document.getElementById("inbox-selected-count").innerHTML = message_selected.length + " message" + (message_selected.length > 1 ? "s" : "") + " selected";
}

function deleteMessage_proc()
{
	if (XMLHttpReq.readyState == 4 && XMLHttpReq.status == 200) {
		var i, j;
		for (i = 0; i < message_selected.length; i++) {
			document.getElementById("inbox-item" + message_selected[i]).style.display = "none";
		}
		inbox_message_count -= message_selected.length;
		for (i = 0; i < inbox_message_block.length; i++) {
			for (j = inbox_message_block[i].length - 1; j >= 0; j--) {
				if (message_selected.indexOf(inbox_message_block[i][j]) != -1) {
					inbox_message_block[i].remove(j);
				}
			}
		}

		message_selected = new Array();
		hideInboxEditPanel();
		refreshMessage();
	}
}

function doDeleteMessage()
{
  UIkit.modal.confirm("This action CANNOT be withdrawed, are you sure to delete " + (message_selected.length > 1 ? "these " + message_selected.length + " messages" : "this message") + "?",
					  function (val) {
					    sendAjaxRequest("/back/message/delete_message_list.php?index_list=" + encodeURIComponent(serializeIDList(message_selected)), deleteMessage_proc);
					  });
}