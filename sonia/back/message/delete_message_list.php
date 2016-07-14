<?php
import("core/message/message.php");
import("core/utils/user.php");

$index_list = isset($_GET["index_list"]) ? explode("|", $_GET["index_list"]) : "";
$session_id = isset($_COOKIE["session_id"]) ? $_COOKIE["session_id"] : "";

if ($index_list != "" && $session_id && checkSessionID($session_id)) {
	rsort($index_list);
	foreach ($index_list as $index) {
		delMessageByField("session_id", $session_id, $index);
	}
}

?>