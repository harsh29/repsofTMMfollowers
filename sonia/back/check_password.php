<?php
error_reporting(0);

import("core/utils/utils.php");
import("core/utils/user.php");
import("core/constants.php");

$password = getMD532U($_GET['pw']);

if (checkUserBySessionID($_COOKIE['session_id'], $password)) {
  echo "1";
}
else {
  echo "0";
}

?>
