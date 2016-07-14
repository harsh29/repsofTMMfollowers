<?php

import("core/file/io.php");
import("core/utils/utils.php");
import("core/utils/database.php");
import("core/utils/entry.php");
import("core/constants.php");

function isUserExist($user_name, $connect = null)
{
  $local_connect = false;
  if (!$connect) {
    $connect = mysql_connect(MYSQL_DATABASE_ADDRESS, MYSQL_USER, MYSQL_PASSWD);
    $local_connect = true;
  }

  $ret = false;
  $check = "SELECT * FROM  `users` WHERE  `user_name` =  '" . $user_name . "'";
  $result = mysql_db_query(MYSQL_DATABASE_NAME, $check, $connect);

  if (mysql_num_rows($result) != 0) {
    $ret = true;
  }

  mysql_free_result($result);
  if ($local_connect)
    mysql_close($connect);

  return $ret;
}

function getIconBySessionID($session_id, $connect = null)
{
  $icon = getFieldBySessionID($session_id, "icon", $connect);
  if (checkExist($icon))
    $icon = getFile($icon);
  else
    $icon = DEFAULT_ICON;

  return $icon;
}

function getIconByUserName($user_name, $connect = null)
{
  $icon = getFieldByUserName($user_name, "icon", $connect);
  if (checkExist($icon))
    $icon = getFile($icon);
  else
    $icon = DEFAULT_ICON;

  return $icon;
}

function checkField($where_field, $where_value, $field, $value, $connect = null)
{
  $local_connect = false;
  if (!$connect) {
    $connect = mysql_connect(MYSQL_DATABASE_ADDRESS, MYSQL_USER, MYSQL_PASSWD);
    $local_connect = true;
  }

  $check = "SELECT * FROM  `users` WHERE  $where_field =  '$where_value' AND $field = '$value'";
  $result = mysql_db_query(MYSQL_DATABASE_NAME, $check, $connect);

  if (mysql_num_rows($result) != 0)
    $ret = true;
  else
    $ret = false;

  mysql_free_result($result);

  if ($local_connect)
    mysql_close($connect);

  return $ret;
}

function checkUser($user_name, $passwd_md5, $connect = null)
{
  return checkField("user_name", $user_name, "password", $passwd_md5, $connect);
}

function checkUserBySessionID($session_id, $passwd_md5, $connect = null)
{
  if (checkSessionID($session_id, $connect))
    return checkField("session_id", $session_id, "password", $passwd_md5, $connect);
  else return false;
}

function updateTimeStamp($user_name, $connect = null)
{
  $local_connect = false;
  if (!$connect) {
    $connect = mysql_connect(MYSQL_DATABASE_ADDRESS, MYSQL_USER, MYSQL_PASSWD);
    $local_connect = true;
  }

  $ret = time();
  $stamp = "UPDATE `users` SET time_stamp = '" . $ret . "' WHERE user_name = '" . $user_name . "'";
  $result = mysql_db_query(MYSQL_DATABASE_NAME, $stamp, $connect);
  mysql_free_result($result);

  if ($local_connect)
    mysql_close($connect);

  return $ret;
}

function updateSessionID($user_name, $connect = null)
{
  $local_connect = false;
  if (!$connect) {
    $connect = mysql_connect(MYSQL_DATABASE_ADDRESS, MYSQL_USER, MYSQL_PASSWD);
    $local_connect = true;
  }

  $ret = generateGUID();
  $generate = "UPDATE `users` SET session_id = '" . $ret . "' WHERE user_name = '" . $user_name . "'";

  $result = mysql_db_query(MYSQL_DATABASE_NAME, $generate, $connect);
  mysql_free_result($result);
  updateTimeStamp($user_name, $connect);

  if ($local_connect)
    mysql_close($connect);

  return $ret;
}

function setFieldBySessionID($session_id, $field, $value, $connect = null)
{
  setField("session_id", $session_id, $field, $value, $connect);
}

function setFieldByUserName($user_name, $field, $value, $connect = null)
{
  setField("user_name", $user_name, $field, $value, $connect);
}

function checkSessionID($session_id, $connect = null)
{
  $local_connect = false;
  if (!$connect) {
    $connect = mysql_connect(MYSQL_DATABASE_ADDRESS, MYSQL_USER, MYSQL_PASSWD);
    $local_connect = true;
  }

  $ret = false;
  $check = "SELECT * FROM `users` WHERE `session_id` = '" . $session_id . "'";

  $result = mysql_db_query(MYSQL_DATABASE_NAME, $check, $connect);

  if (mysql_num_rows($result) != 0) {
    $row = mysql_fetch_assoc($result);
    if (intval($row['time_stamp']) + intval(USER_LOGIN_TLL) > time()) {
      //trace($row['time_stamp']);
      $ret = true;
    }
  }

  mysql_free_result($result);

  if ($local_connect)
    mysql_close($connect);

  return $ret;
}

function getGroupIDBySessionID($session_id, $connect = null)
{
  $local_connect = false;
  if (!$connect) {
    $connect = mysql_connect(MYSQL_DATABASE_ADDRESS, MYSQL_USER, MYSQL_PASSWD);
    $local_connect = true;
  }

  $get = "SELECT * FROM `users` WHERE `session_id` = '" . $session_id . "'";
  $result = mysql_db_query(MYSQL_DATABASE_NAME, $get, $connect);
  $row = mysql_fetch_assoc($result);
  $ret = $row['group_id'];

  mysql_free_result($result);
  if ($local_connect)
    mysql_close($connect);

  return $ret;
}

function getUserNameBySessionID($session_id, $connect = null)
{
  $local_connect = false;
  if (!$connect) {
    $connect = mysql_connect(MYSQL_DATABASE_ADDRESS, MYSQL_USER, MYSQL_PASSWD);
    $local_connect = true;
  }

  $get = "SELECT * FROM `users` WHERE `session_id` = '" . $session_id . "'";
  $result = mysql_db_query(MYSQL_DATABASE_NAME, $get, $connect);
  $row = mysql_fetch_assoc($result);
  $ret = $row['user_name'];

  mysql_free_result($result);
  if ($local_connect)
    mysql_close($connect);

  return $ret;
}

function getFieldBySessionID($session_id, $field, $connect = null)
{
  $local_connect = false;
  if (!$connect) {
    $connect = mysql_connect(MYSQL_DATABASE_ADDRESS, MYSQL_USER, MYSQL_PASSWD);
    $local_connect = true;
  }

  $get = "SELECT * FROM `users` WHERE `session_id` = '" . $session_id . "'";
  $result = mysql_db_query(MYSQL_DATABASE_NAME, $get, $connect);
  $row = mysql_fetch_assoc($result);
  $ret = $row[$field];

  mysql_free_result($result);
  if ($local_connect)
    mysql_close($connect);

  return $ret;
}

function getFieldByUserName($user_name, $field, $connect = null)
{
  $local_connect = false;
  if (!$connect) {
    $connect = mysql_connect(MYSQL_DATABASE_ADDRESS, MYSQL_USER, MYSQL_PASSWD);
    $local_connect = true;
  }

  $get = "SELECT * FROM `users` WHERE `user_name` = '" . $user_name . "'";
  $result = mysql_db_query(MYSQL_DATABASE_NAME, $get, $connect);
  $row = mysql_fetch_assoc($result);
  $ret = $row[$field];

  mysql_free_result($result);
  if ($local_connect)
    mysql_close($connect);

  return $ret;
}

function createAccount($user_name, $nick_name, $password, $icon, $self_intro = "", $group = DEFAULT_INITIAL_GROUP, $connect = null)
{
  $local_connect = false;
  if (!$connect) {
    $connect = mysql_connect(MYSQL_DATABASE_ADDRESS, MYSQL_USER, MYSQL_PASSWD);
    $local_connect = true;
  }

  $ret = false;
  $check = "SELECT * FROM  `users` WHERE  `user_name` =  '" . $user_name . "'";
  $result = mysql_db_query(MYSQL_DATABASE_NAME, $check, $connect);

  if (mysql_num_rows($result) != 0) {
    trace("User name $user_name has already existed.");
  } else {
    mysql_free_result($result);

    $password = getMD532U($password);
    $create = "INSERT INTO `users` (user_name, nick_name, password, group_id, icon, self_intro) VALUES ('$user_name', '$nick_name', '$password', '$group', '$icon', '$self_intro')";
    $result = mysql_db_query(MYSQL_DATABASE_NAME, $create, $connect);
    trace($create);

    $ret = true;
  }

  mysql_free_result($result);
  if ($local_connect)
    mysql_close($connect);

  return $ret;
}

function disposeAccount($user_name)
{
  $local_connect = false;
  if (!$connect) {
    $connect = mysql_connect(MYSQL_DATABASE_ADDRESS, MYSQL_USER, MYSQL_PASSWD);
    $local_connect = true;
  }

  $dispose = "DELETE FROM `users` WHERE user_name = '$user_name'";

  $result = mysql_db_query(MYSQL_DATABASE_NAME, $dispose, $connect);
  mysql_free_result($result);

  if ($local_connect)
    mysql_close($connect);
}

function getContributionBySessionID($session_id, $connect = null)
{
  $val = getFieldBySessionID($session_id, "contribution", $connect);
  return ($val ? unserialize($val) : array());
}

function addContributionBySessionID($session_id, $entry_id, $connect = null)
{
  $contri_array = getContributionBySessionID($session_id, $connect);
  if (!in_array($entry_id, $contri_array)) {
    array_push($contri_array, $entry_id);
    setFieldBySessionID($session_id, "contribution", serialize($contri_array));
  }
}

function delContributionBySessionID($session_id, $entry_id, $connect = null)
{
  $contri_array = getContributionBySessionID($session_id, $connect);
  $i = 0;
  foreach ($contri_array as $id) {
    if ($id == $entry_id) {
      array_splice($contri_array, $i, 1);
      break;
    }
    $i++;
  }
  setFieldBySessionID($session_id, "contribution", serialize($contri_array));
}


function getContributionByUserName($user_name, $connect = null)
{
  $val = getFieldByUserName($user_name, "contribution", $connect);
  return ($val ? unserialize($val) : array());
}

function addContributionByUserName($user_name, $entry_id, $connect = null)
{
  $contri_array = getContributionByUserName($user_name, $connect);
  if (!in_array($entry_id, $contri_array)) {
    array_push($contri_array, $entry_id);
    setFieldByUserName($user_name, "contribution", serialize($contri_array));
  }
}

function delContributionByUserName($user_name, $entry_id, $connect = null)
{
  $contri_array = getContributionByUserName($user_name, $connect);
  $i = 0;
  foreach ($contri_array as $id) {
    if ($id == $entry_id) {
      array_splice($contri_array, $i, 1);
      break;
    }
    $i++;
  }
  setFieldByUserName($user_name, "contribution", serialize($contri_array));
}

/***************************************************************************/

function getLikeBySessionID($session_id, $connect = null)
{
  $val = getFieldBySessionID($session_id, "like", $connect);
  return ($val ? unserialize($val) : array());
}

function addLikeBySessionID($session_id, $entry_id, $connect = null)
{
  $contri_array = getLikeBySessionID($session_id, $connect);
  if (!in_array($entry_id, $contri_array)) {
    array_push($contri_array, $entry_id);
    setFieldBySessionID($session_id, "like", serialize($contri_array));
  }
  addEntryLikeByID($entry_id, getUserNameBySessionID($session_id, $connect), $connect); // reverse link
}

function delLikeBySessionID($session_id, $entry_id, $connect = null)
{
  $contri_array = getLikeBySessionID($session_id, $connect);
  $i = 0;
  foreach ($contri_array as $id) {
    if ($id == $entry_id) {
      array_splice($contri_array, $i, 1);
      break;
    }
    $i++;
  }
  setFieldBySessionID($session_id, "like", serialize($contri_array));
  delEntryLikeByID($entry_id, getUserNameBySessionID($session_id, $connect), $connect); // delete reverse link
}


function getLikeByUserName($user_name, $connect = null)
{
  $val = getFieldByUserName($user_name, "like", $connect);
  return ($val ? unserialize($val) : array());
}

function addLikeByUserName($user_name, $entry_id, $connect = null)
{
  $contri_array = getLikeByUserName($user_name, $connect);
  if (!in_array($entry_id, $contri_array)) {
    array_push($contri_array, $entry_id);
    setFieldByUserName($user_name, "like", serialize($contri_array));
  }
  addEntryLikeByID($entry_id, $user_name, $connect); // reverse link
}

function delLikeByUserName($user_name, $entry_id, $connect = null)
{
  $contri_array = getLikeByUserName($user_name, $connect);
  $i = 0;
  foreach ($contri_array as $id) {
    if ($id == $entry_id) {
      array_splice($contri_array, $i, 1);
      break;
    }
    $i++;
  }
  setFieldByUserName($user_name, "like", serialize($contri_array));
  delEntryLikeByID($entry_id, $user_name, $connect); // delete reverse link
}

?>