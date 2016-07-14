<?php
error_reporting(0);

import("core/utils/user.php");

$session_id = $_COOKIE['session_id'];
$allowed_list = array("nick_name" => PV_CHANGE_NICK_NAME,
					  "self_intro" => PV_CHANGE_SELF_INTRO,
					  "group_id" => PV_CHANGE_GROUP_ID,
					  "password" => PV_CHANGE_PASSWORD);
$ret = "";
$group_id = getFieldBySessionID($session_id, "group_id");

if (checkSessionID($session_id)) {
	$msg = "messageBox('Successfully updated', 'success');";
	$is_jump = true;
	foreach ($_REQUEST as $key => $value) {
		if (in_array($key, $allowed_list)) {
			if ($group_id <= $allowed_list[$key] || getFieldBySessionID($session_id, $key) == $value)
				setFieldBySessionID($session_id, $key, $value);
			else {
				$msg = "messageBox('Sorry, you don not have the privilege to do this', 'danger');";
				$is_jump = false;
			}
		}
	}
	$ret .= $msg . ($is_jump ? "doJump(window.location.href);" : "");
} else {
	$ret .= "doJump(window.location.href);";
}

echo $ret;

?>