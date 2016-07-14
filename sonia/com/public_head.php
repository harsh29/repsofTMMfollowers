<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
<meta http-equiv="Pragma" content="no-cache">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<?php

import("core/constants.php");
import("core/utils/utils.php");

if (!IS_WINDOWS_LOCAL) {
	if (!file_exists(ACCESS_LOG))
		$handle = fopen(ACCESS_LOG,"w");
	else
		$handle = fopen(ACCESS_LOG,"a");

	date_default_timezone_set('PRC');
	fwrite($handle, getIP() . " to '" . $_SERVER['PHP_SELF'] . "?" . $_SERVER['QUERY_STRING'] . "' at " . date("h:i a d/m/y") . ", with " . getBrowser() . " in " . getOS() . " in " . getDevice() . "\n");
	fclose($handle);
}

if (getBrowser() == "Safari") {
	echo '<style>body{letter-spacing:-1px}</style>';
}


?>
