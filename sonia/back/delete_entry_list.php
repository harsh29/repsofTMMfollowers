<?php

import("core/do_search.php");
import("core/utils/utils.php");
import("core/utils/entry.php");
import("core/utils/user.php");

if (isset($_GET['list'])) {
	$list = explode("|", $_GET['list']);

	foreach ($list as $id) {
		if (isEntryExist($id)) {
			$info = getEntryBasicInfo($id, true);
			if (getUserNameBySessionID($session_id) != $info[2]) {
				echo "Invalid delete";
				continue;
			}
			delContributionByUserName($info[2], $id);
			deleteEntry($id);
		}
	}
}

?>
