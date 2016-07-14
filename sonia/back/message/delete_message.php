<?php
import("core/message/message.php");
import("core/utils/user.php");

$index = isset($_GET["index"]) ? $_GET["index"] : "";
$session_id = isset($_COOKIE["session_id"]) ? $_COOKIE["session_id"] : "";

if ($index != "" && $session_id && checkSessionID($session_id)) {
	delMessageByField("session_id", $session_id, $index);
}

?>