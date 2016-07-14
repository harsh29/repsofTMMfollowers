<html>

<head>
  <link rel="stylesheet" type="text/css" href="/css/global.css" />
</head>

<body>

	<script src="/js/utils.js"></script>
	<?php

	import("core/utils/comment.php");
	import("core/utils/user.php");
	import("core/message/message.php");
	import("core/do_search.php");

	$session_id = isset($_COOKIE['session_id']) ? $_COOKIE['session_id'] : "";
	$entry_id = isset($_POST['id']) ? $_POST['id'] : "";
	$comment_id = isset($_POST['comment_id']) ? $_POST['comment_id'] : "";
	$action = isset($_POST['action']) ? $_POST['action'] : "";
	$content = isset($_POST['content']) ? $_POST['content'] : "";
	$basic_info = getEntryBasicInfo($entry_id);

	if ($session_id && checkSessionID($session_id)) {
		$user_name = getUserNameBySessionID($session_id);
		if ($comment_id == "") {
			$comment = new Comment($user_name, $content);
			addCommentByID($entry_id, $comment);
			addMessageByField("user_name", $basic_info[2], "I replied your entry \"" . $basic_info[0] . "\"!", $user_name);
		}
		else {
			$list = getComment($entry_id);
			$comment = $list[intval($comment_id[0])];
			$upper = ($action == "delete") ? strlen($comment_id) - 1 : strlen($comment_id);
			$id_list = explode("|", $comment_id);
			$i = 0;

			foreach ($id_list as $each_id) {
				if (!$each_id) {
					$i++;
					continue;
				}
				if ($action == "delete" && $i == count($id_list) - 1) break;
				$comment = $comment->reply[intval($each_id)];
				$i++;
			}

			if ($action == "delete") {
				if (count($id_list) - 1 > 1)
					array_splice($comment->reply, intval($id_list[$i]), 1);
				else {
					array_splice($list, intval($comment_id[0]), 1);
				}
				rearrangeComment($list);
			}
			else {
				$comment->addReply($user_name, $content);
				addMessageByField("user_name", $comment->user_name, "I replied your comment in entry \"" . $basic_info[0] . "\"!", $user_name);
			}

			setCommentByID($entry_id, $list);
		}

		echo "<script>";
	  	echo "doJump('/entry.php?id=" . $entry_id . "');";
	  	echo "</script>";
	} else {
		echo "<script>";
	  	echo "doJump('/login.php?cb=/entry.php&cb_q=" . urlencode("id=" . $entry_id) . "');";
	  	echo "</script>";
	}

	?>

</body>

</html>