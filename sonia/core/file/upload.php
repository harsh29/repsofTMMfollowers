<?php
//error_reporting(0);

import("core/constants.php");
import("core/file/io.php");

if (!$_FILES["upfile"]["error"]) {
  $md5 = md5_file($_FILES["upfile"]["tmp_name"]);
  if (checkExist($md5)) {
    echo "0";//echo "Same file has already uploaded";
  } else {
    storeUploadedFile($_FILES["upfile"]["tmp_name"], $md5);
    //echo "Successfully uploaded";
    echo $md5;
  }
} else {
  echo $_FILES["upfile"]["error"];
}

?>
