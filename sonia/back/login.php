<?php
error_reporting(0);

import("core/utils/utils.php");
import("core/utils/user.php");
import("core/constants.php");


$user_name = $_GET['un'];
$password = getMD532U($_GET['pw']);

if (checkUser($user_name, $password)) {
  //trace("Success");
  //trace("User name: " . $user_name);
  //echo updateSessionID($user_name);
  $session_id = updateSessionID($user_name);
  echo $session_id;
  //setcookie("session_id", "", time() - USER_LOGIN_TLL, "/");
  //setcookie("session_id", $session_id, time() + USER_LOGIN_TLL, "/");
  //echo "messageBox('Success Session ID: " . $_COOKIE['session_id'] . " Returning to ' + getPara('cb'), 'uk-alert-success');";//doJump(getPara('cb') + '?' + getPara('cb_q'));";
  //trace("New session id: " . $session_id);
  //echo "Time Stamp: "; checkSessionID($session_id);
  //trace("Group ID: " . getGroupIDBySessionID($session_id));
}
else {
  //trace("Wrong user name or password, please try again.");
  echo "0";
  //echo "messageBox('Wrong user name or password, please try again', 'uk-alert-danger')";
}

?>
