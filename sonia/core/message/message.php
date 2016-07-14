<?php

import("core/utils/database.php");
import("core/constants.php");

class Message {
	var $text;
	var $timestamp;
	var $sender;
	var $has_shown = false;
	var $delete_token = false;

	function Message($text, $sender = "System")
	{
		$this->text = $text;
		$this->timestamp = time();
		$this->sender = $sender;
	}
}

function getMessageByField($where_field, $where_value, $connect = null)
{
	$ret = getField($where_field, $where_value, "message", $connect);
	return ($ret ? unserialize($ret) : array());
}

function setMessageByField($where_field, $where_value, $msg_list, $connect = null)
{
	return setField($where_field, $where_value, "message", serialize($msg_list), $connect);
}

function addMessageByField($where_field, $where_value, $message, $sender = MESSAGE_SYSTEM_SENDER, $connect = null)
{
	$msg_list = getMessageByField($where_field, $where_value, $connect);
	array_push($msg_list, new Message($message, $sender));
	setMessageByField($where_field, $where_value, $msg_list);
}

function delMessageByField($where_field, $where_value, $msg_index, $connect = null)
{
	$msg_list = getMessageByField($where_field, $where_value, $connect);
	array_splice($msg_list, $msg_index, 1);
	setMessageByField($where_field, $where_value, $msg_list);
}

function getBulletinByField($where_field, $where_value, &$timestamp, &$sender, $connect = null)
{
	$msg_list = getMessageByField($where_field, $where_value, $connect);
	$last = end($msg_list);
	$ret = "";
	$timestamp = -1;
	$sender = "";

	if ($last->timestamp >= (time() - BULLETIN_TIME_OUT) || !$last->has_shown) {
		$last->has_shown = true;
		$ret = $last->text;
		$timestamp = $last->timestamp;
		$sender = $last->sender;
		setMessageByField($where_field, $where_value, $msg_list, $connect);
	}

	return $ret;
}

?>