<?php

import("core/constants.php");
import("core/utils/user.php");

if (isset($_GET['list'])) {
	$list = explode("|", $_GET['list']);
	$session_id = isset($_COOKIE['session_id']) ? $_COOKIE['session_id'] : "";
	$action = isset($_GET['act']) ? $_GET['act'] : "like"; /* like or withdraw */

	if ($session_id && checkSessionID($session_id)) {
		foreach ($list as $id) {
				if ($action == "like")
					addLikeBySessionID($session_id, $id);
				else if ($action == "withdraw")
					delLikeBySessionID($session_id, $id);
				else
					echo "unknown action";
		}
	}
}

?>