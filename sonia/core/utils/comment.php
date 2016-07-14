<?php

import("core/constants.php");
import("core/utils/utils.php");
import("core/utils/user.php");
import("core/utils/database.php");
import("core/utils/entry.php");
import("core/third_party/Parsedown.php");

class Comment {
	var $user_name;
	var $time;
	var $content;
	var $parent;
	var $reply;
	var $id;

	function Comment($_user_name, $_content, $_parent = null, $_id = "") {
		$this->user_name = $_user_name;
		$this->time = date("h:i a, d/m/y");
		$this->content = $_content;
		$this->reply = array();
		$this->parent = $_parent;
		$this->id = $_id;
	}

	function generateComment() {
		$verb = "said";
		if ($this->parent) {
			$verb = "reply " . $this->parent->user_name;
		}
		$display_name = getFieldByUserName($this->user_name, "nick_name");
		
		$ret = '<div id="comments" class="entry_comment">';

		$ret .= '<button class="common-botton delete-botton small-icon no-border no-display" type="submit" style="position: relative; margin-top: -5px; margin-right: 5px; float: right;" onclick="deleteComment(\'' . $this->id . '\');">
		         	<i class="fa fa-trash"></i>
		         </button>';
		$ret .= '<button class="common-botton normal-botton small-icon no-border no-display" type="submit" style="position: relative; margin-top: -5px; margin-right: 5px; float: right;" onclick="setReply(\'' . $this->id . '\', \'' . $display_name . '\');">
		         	<i class="fa fa-quote-left" style="text-align: center;"></i>
		         </button>';

		$icon = getIconByUserName($this->user_name);

		$ret .= '<img class="meta_avatar icon-common" style="width: 1.5em; height: 1.5em;" src="' . $icon . '" />';
		$ret .= '<span style="font-weight: bold; padding: 0 5px; cursor: pointer; font-size: 0.9em;">' . $display_name . '</span>';
		$ret .= '<span style="font-size: 0.8em;">at ' . $this->time . '<br></span>';

		$ret .= '<div style="margin: 0.5em auto auto 1.7em">';
		$ret .= Parsedown::instance()->parse($this->content);
		$ret .= '</div>';

		foreach ($this->reply as $single_reply) {
			$ret .= $single_reply->generateComment();
		}
		$ret .= "</div>";
		return $ret;
	}

	function addReply($_user_name, $_content) {
		$new_comment = new Comment($_user_name, $_content, $this, $this->id . count($this->reply));
		array_push($this->reply, $new_comment);
		return $new_comment;
	}
}

function getComment($entry_id, $connect = null)
{
	createEntryLog($entry_id, $connect);
	$val = getField("id", $entry_id, "comments", $connect, "entry");
	$ret = ($val ? unserialize($val) : array());
	return ($ret ? $ret : array());
}

function addCommentByID($entry_id, $comment, $connect = null)
{
	$list = getComment($entry_id, $connect);
	$comment->id = count($list);
	array_push($list, $comment);
	setField("id", $entry_id, "comments", serialize($list), $connect, "entry");
}

function setCommentByID($entry_id, $comment_tree, $connect = null)
{
	setField("id", $entry_id, "comments", serialize($comment_tree), $connect, "entry");
}

function delCommentByID($entry_id, $comment, $connect = null)
{
	$list = getComment($entry_id, $connect);
	$i = 0;
	foreach ($list as $name) {
		if ($name == $comment) {
		  array_splice($list, $i, 1);
		  break;
		}
		$i++;
	}
	rearrangeComment($list);
	setField("id", $entry_id, "comments", serialize($list), $connect, "entry");
}

function rearrangeComment($comment_array, $id = "")
{
	$i = 0;
	foreach ($comment_array as $comment) {
		$comment->id = $id . (id ? "|" : "") . $i;
		rearrangeComment($comment->reply, $comment->id);
		$i++;
	}
}

?>