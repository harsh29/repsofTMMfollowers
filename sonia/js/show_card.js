var is_handled = true;
var XMLObject = null;
var selected = { };

function doEdit(board)
{
  doJump("edit.php?id=" + selected[board][0], true);
}

function doView(board)
{
  doJump("entry.php?id=" + selected[board][0], true);
}

function serializeIDList(list)
{
  var i, ret = list.length > 0 ? list[0] : "";
  for (i = 1; i < list.length; i++) {
    ret += "|" + list[i];
  }
  return ret;
}

function deleteEntry_proc()
{
  doJump("/profile.php");
}

function deleteConfirm_proc()
{
  if (XMLHttpReq.readyState == 4) {
    if (XMLHttpReq.status == 200) {
      if (XMLHttpReq.responseText == "1") {
        UIkit.modal.confirm("This action CANNOT be withdrawed, are you sure to delete " + (selected["contribution"].length > 1 ? "these " + selected["contribution"].length + " entries" : "this entry") + "?",
          function (val) {
            sendAjaxRequest("/back/delete_entry_list.php?list=" + encodeURIComponent(serializeIDList(selected["contribution"])), deleteEntry_proc);
          }
        );
      } else UIkit.modal.alert("Wrong password");
      //alert(sessionID);
      //messageBox(sessionID, "uk-alert-danger");
    }
  }
}

function doDelete()
{
  passwordConfirm(deleteConfirm_proc);
}


function deleteFavorite_proc()
{
  doJump("/profile.php");
}

function deleteFavoriteConfirm_proc()
{
  if (XMLHttpReq.readyState == 4) {
    if (XMLHttpReq.status == 200) {
      if (XMLHttpReq.responseText == "1") {
        UIkit.modal.confirm("This action CANNOT be withdrawed, are you sure to dislike " + (selected["favorite"].length > 1 ? "these " + selected["favorite"].length + " entries" : "this entry") + "?",
          function (val) {
            sendAjaxRequest("/back/like_entry_list.php?act=withdraw&list=" + encodeURIComponent(serializeIDList(selected["favorite"])), deleteFavorite_proc);
          }
        );
      } else UIkit.modal.alert("Wrong password");
      //alert(sessionID);
      //messageBox(sessionID, "uk-alert-danger");
    }
  }
}

function doDeleteFavorite()
{
  passwordConfirm(deleteFavoriteConfirm_proc);
}

Array.prototype.remove = function(dx) 
{ 
  if (isNaN(dx) || dx > this.length) return false;
  for (var i = 0, n = 0; i < this.length; i++)
  {
    if (this[i] != this[dx])
    {
      this[n++] = this[i];
    }
  }
  this.length -= 1;
}

function showEditPanel(board)
{
  var panel = document.getElementById(board + "-edit-panel");
  panel.style.display = "";
  $("#" + board + "-edit-panel").animate({top: "0"}, 300, "swing");
  $("#" + board + "_board").animate({paddingTop: "5em"}, 300, "swing");
}

function hideEditPanel(board)
{
  var panel = document.getElementById(board + "-edit-panel");
  $("#" + board + "-edit-panel").animate({top: "-5em"}, 300, "swing");
  $("#" + board + "_board").animate({paddingTop: "0"}, 300, "swing");
}

function selectorToggle(id, board)
{
  if (!selected[board]) selected[board] = new Array();
  if (selected[board].indexOf(id) != -1) { // exists
    selected[board].remove(selected[board].indexOf(id));
  } else selected[board].push(id);

  if (selected[board].length != 0) {
    showEditPanel(board);
  } else hideEditPanel(board);

  classie.toggle(document.getElementById(board + "-container" + id), "card-container-selected");
  document.getElementById(board + "-selected-count").innerHTML = selected[board].length + " entr" + (selected[board].length > 1 ? "ies" : "y") + " selected";
}

function addFavorite(board, id)
{
  if (!is_handled) return;
  var icon = document.getElementById(board + "-fav" + id);
  var fav_count = document.getElementById(board + "-fav-count" + id);
  var req_url = "/back/like_entry.php?id=" + id;
  is_handled = false;

  if ((XMLObject = createXMLHttpRequest()) != null) {
    sendRequestByObject(XMLObject, req_url, function () {
      icon.onclick = function () {
        delFavorite(board, id);
      }
      is_handled = true;
    });
  } else {
    document.getElementById("like_back").src = req_url;
    icon.onclick = function () {
      delFavorite(board, id);
    }
  }

  fav_count.style.display = "";
  fav_count.innerHTML = parseInt(fav_count.innerHTML) + 1;
  classie.add(icon, "fa-heart");
  classie.add(icon, "card_toolbar-item_hover");
  classie.remove(icon, "fa-heart-o");
  classie.remove(icon, "card_toolbar-item");
}

function delFavorite(board, id)
{
  if (!is_handled) return;
  var icon = document.getElementById(board + "-fav" + id);
  var fav_count = document.getElementById(board + "-fav-count" + id);
  var req_url = "/back/like_entry.php?id=" + id + "&act=withdraw";
  is_handled = false;

  if ((XMLObject = createXMLHttpRequest()) != null) {
    sendRequestByObject(XMLObject, req_url, function () {
      icon.onclick = function () {
        addFavorite(board, id);
      }
      is_handled = true;
    });
  } else {
    document.getElementById("like_back").src = req_url;
    icon.onclick = function () {
      addFavorite(board, id);
    }
  }

  fav_count.style.display = "none";
  fav_count.innerHTML = parseInt(fav_count.innerHTML) - 1;
  classie.remove(icon, "fa-heart");
  classie.remove(icon, "card_toolbar-item_hover");
  classie.add(icon, "fa-heart-o");
  classie.add(icon, "card_toolbar-item");
}