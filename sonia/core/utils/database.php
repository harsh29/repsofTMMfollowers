<?php

import("core/utils/utils.php");
import("core/constants.php");

function setField($where_field, $where_value, $field, $value, $connect = null, $table = "users")
{
  $local_connect = false;
  if (!$connect) {
    $connect = mysql_connect(MYSQL_DATABASE_ADDRESS, MYSQL_USER, MYSQL_PASSWD);
    $local_connect = true;
  }

  $generate = "UPDATE `$table` SET `$field` = '" . $value . "' WHERE `$where_field` = '" . $where_value . "'";

  $result = mysql_db_query(MYSQL_DATABASE_NAME, $generate, $connect);
  mysql_free_result($result);

  if ($local_connect)
    mysql_close($connect);
}

function getField($where_field, $where_value, $field, $connect = null, $table = "users")
{
  $local_connect = false;
  if (!$connect) {
    $connect = mysql_connect(MYSQL_DATABASE_ADDRESS, MYSQL_USER, MYSQL_PASSWD);
    $local_connect = true;
  }

  $get = "SELECT * FROM `$table` WHERE `$where_field` = '" . $where_value . "'";
  $result = mysql_db_query(MYSQL_DATABASE_NAME, $get, $connect);
  $row = mysql_fetch_assoc($result);
  $ret = $row[$field];

  mysql_free_result($result);
  if ($local_connect)
    mysql_close($connect);

  return $ret;
}

?>