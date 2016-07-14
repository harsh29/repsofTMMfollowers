<?php

import("core/utils/user.php");

if ($_GET['un']) {
  disposeAccount($_GET['un']);
  echo "successfully executed";
} else {
  echo "no user name received";
}

?>
