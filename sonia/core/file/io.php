<?php

import("core/constants.php");

function getFilePath($file_md5)
{
  return FILE_DIR . "/" . substr($file_md5, 0, 1) . "/" . $file_md5;
}

function getFile($file_md5)
{
  return GET_FILE_DIR . "/" . substr($file_md5, 0, 1) . "/" . $file_md5;
}

function checkExist($file_md5)
{
  if ($file_md5)
    return file_exists(getFilePath($file_md5));
  return false;
}

function storeUploadedFile($tmp_name, $md5 = "")
{
  if (!$md5) {
    $md5 = md5_file($tmp_name);
  }
  move_uploaded_file($tmp_name, $ret = getFilePath($md5));

  return $ret;
}

?>
