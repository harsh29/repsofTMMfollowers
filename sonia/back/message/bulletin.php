<?php
error_reporting(0);

import("core/utils/user.php");
import("core/message/message.php");

$session_id = isset($_COOKIE['session_id']) ? $_COOKIE['session_id'] : "";

if ($session_id && checkSessionID($session_id)) {
	if (($bulletin = getBulletinByField("session_id", $session_id, $timestamp, $sender)))
		echo $timestamp . ":" . getFieldByUserName($sender, "nick_name") . ": " . $bulletin;
}

?>