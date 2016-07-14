<?php
error_reporting(0);

import("core/utils/user.php");

if (isUserExist($_GET['un'])) {
  echo "1";
} else {
  echo "0";
}

?>
