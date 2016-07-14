<?php

import("core/constants.php");
import("core/utils/utils.php");
import("core/utils/user.php");
import("core/utils/database.php");
import("core/utils/comment.php");

function deleteEntry($id) {
  $list = getEntryLike($id);

 	foreach ($list as $user) {
 		delLikeByUserName($user, $id);
    delEntryLikeByID($id, $user);
 	}
  setCommentByID($id, array());

  return delDir(getEntrybyID($id));
}

function createEntryLog($entry_id, $connect = null)
{
	$local_connect = false;
  if (!$connect) {
    $connect = mysql_connect(MYSQL_DATABASE_ADDRESS, MYSQL_USER, MYSQL_PASSWD);
    $local_connect = true;
  }

  $ret = false;
  $check = "SELECT * FROM  `entry` WHERE  `id` =  '" . $entry_id . "'";
  $result = mysql_db_query(MYSQL_DATABASE_NAME, $check, $connect);

  if (mysql_num_rows($result) != 0) {
    //trace("Entry $entry_id has already existed.");
  } else {
    mysql_free_result($result);

    $create = "INSERT INTO `entry` (id) VALUES ('$entry_id')";
    $result = mysql_db_query(MYSQL_DATABASE_NAME, $create, $connect);

    $ret = true;
  }

  mysql_free_result($result);
  if ($local_connect)
    mysql_close($connect);

  return $ret;
}

function getEntryLike($entry_id, $connect = null)
{
	createEntryLog($entry_id, $connect);
	$val = getField("id", $entry_id, "like", $connect, "entry");
	return ($val ? unserialize($val) : array());
}

function addEntryLikeByID($entry_id, $user_name, $connect = null)
{
	$list = getEntryLike($entry_id, $connect);
	if (!in_array($user_name, $list)) {
		array_push($list, $user_name);
		setField("id", $entry_id, "like", serialize($list), $connect, "entry");
	}
}

function delEntryLikeByID($entry_id, $user_name, $connect = null)
{
	$list = getEntryLike($entry_id, $connect);
  $i = 0;
  foreach ($list as $name) {
    if ($name == $user_name) {
      array_splice($list, $i, 1);
      break;
    }
    $i++;
  }
  setField("id", $entry_id, "like", serialize($list), $connect, "entry");
}

?>