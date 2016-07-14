<?php

import("core/constants.php");
import("core/utils/user.php");

$id = $_GET['id'];
$action = isset($_GET['act']) ? $_GET['act'] : "like"; /* like or withdraw */
$session_id = isset($_COOKIE['session_id']) ? $_COOKIE['session_id'] : "";

if ($session_id && checkSessionID($session_id)) {
	if ($action == "like") {
		addLikeBySessionID($session_id, $id);
	}
	else if ($action == "withdraw") {
		delLikeBySessionID($session_id, $id);
	}
	else
		echo "unknown action";
}

?>