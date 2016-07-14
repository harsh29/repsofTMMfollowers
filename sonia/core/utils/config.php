<?php

import("core/utils/utils.php");
import("core/constants.php");

function getConfig($field, $connect = null)
{
	$local_connect = false;
  if (!$connect) {
    $connect = mysql_connect(MYSQL_DATABASE_ADDRESS, MYSQL_USER, MYSQL_PASSWD);
    $local_connect = true;
  }

  $get = "SELECT * FROM `" . MYSQL_CONFIG_TABLE . "`";
  $result = mysql_db_query(MYSQL_DATABASE_NAME, $get, $connect);
  $row = mysql_fetch_assoc($result);
  $ret = $row[$field];

  mysql_free_result($result);
  if ($local_connect)
    mysql_close($connect);

  return $ret;
}

function setConfig($field, $value, $connect = null)
{
  $local_connect = false;
  if (!$connect) {
    $connect = mysql_connect(MYSQL_DATABASE_ADDRESS, MYSQL_USER, MYSQL_PASSWD);
    $local_connect = true;
  }

  $update = "UPDATE `" . MYSQL_CONFIG_TABLE . "` SET $field = '$value'";

  $result = mysql_db_query(MYSQL_DATABASE_NAME, $update, $connect);

  if (!$result)
  	mysql_free_result($result);
  if ($local_connect)
    mysql_close($connect);
}

?>