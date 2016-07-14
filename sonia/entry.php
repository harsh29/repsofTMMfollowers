<!DOCTYPE html>
<html>

<head>

  <?php
    define("IS_ENTRY", true);

    import("core/constants.php");
    import("core/do_search.php");
    import("core/utils/user.php");
    import("core/file/io.php");

    $basic_info = getEntryBasicInfo($_GET['id'], true);
  ?>

  <title><?php echo $basic_info[0] ?></title>
  <?php import("com/public_head.php"); ?>
  <link rel="stylesheet" href="/css/codemirror.css" />
  <link rel="stylesheet" href="/css/uikit.min.css" />
  <link rel="stylesheet" href="/css/com/notify.almost-flat.min.css" />
  <link rel="stylesheet" href="/css/com/htmleditor.almost-flat.min.css" />
  <link rel="stylesheet" type="text/css" href="css/global.css" />
  <link rel="stylesheet" type="text/css" href="fonts/font-awesome-4.4.0/css/font-awesome.min.css" />
  <link rel="stylesheet" type="text/css" href="css/main.css" />
  <link rel="stylesheet" type="text/css" href="css/card.css" />
  <link rel="stylesheet" type="text/css" href="/css/edit.css" />

  <script src="/js/marked.min.js"></script>
  <script src="/js/json2.js"></script>

  <script src="/js/codemirror/lib/codemirror.js"></script>
  <script src="/js/codemirror/mode/markdown/markdown.js"></script>
  <script src="/js/codemirror/addon/mode/overlay.js"></script>
  <script src="/js/codemirror/mode/xml/xml.js"></script>
  <script src="/js/codemirror/mode/gfm/gfm.js"></script>
  <script src="/js/marked.min.js"></script>
  <script src="/js/jquery.js"></script>
  <script src="/js/uikit.min.js"></script>
  <script src="/js/com/notify.min.js"></script>
  <script src="/js/com/htmleditor.js"></script>

  <link rel="stylesheet" href="/css/com/tooltip.almost-flat.min.css" />
  <script src="/js/com/tooltip.js"></script>

  <style>
    html {
      background: #FAFAFA;
    }
  </style>

</head>

<body style="line-height: 1.4em;">

  <script src="js/utils.js"></script>
  <script src='js/ajex.js'></script>
  <script src="js/message/message.js"></script>
  
  <div class="main_wapper">
      <?php import("com/top_panel.php"); ?>
      <div class="card_content" style="margin-top: 2.5em;">
        <div class="card_caption">
          <h2 class="card_title"><?php echo $basic_info[0] ?>
          <?php

          import("core/constants.php");
          import("core/utils/user.php");

          if (isset($_COOKIE['session_id'])
              && checkSessionID($session_id = $_COOKIE['session_id'])) {
            $group_id = getGroupIDBySessionID($session_id);
            $user_name = getUserNameBySessionID($session_id);
            $content_href = '<a class="fa fa-pencil-square-o fa-2 edit_btn" style="margin-left: 0.2em; font-size: 0.5em; cursor: pointer;" href="edit.php?id=' . $_GET['id'] . '"></a>';
            $content_warning = '<a class="fa fa-pencil-square-o fa-2 edit_btn" style="margin-left: 0.2em; font-size: 0.5em; cursor: pointer;" onclick="UIkit.notify(\'Sorry, you can only edit your own entry\', {status: \'warning\', timeout: 3000});"></a>';

            if ($group_id <= PV_EDIT_ALL_ENTRY) { // all privilege
                echo $content_href;
            } else if ($group_id <= PV_EDIT_OWN_WRITTEN_ENTRY) {
                if (getUserNameBySessionID($session_id) == $basic_info[2])
                  echo $content_href;
                else
                  echo $content_warning;
            }
          }

          ?>
          </h2>
          <p class="card_subtitle"><?php echo $basic_info[1] ?></p>
        </div>
      </div>
      <div class="card_copy">
        <div class="meta">
          <img class="meta_avatar icon-common" style="width: 2em; height: 2em;" src="<?php
            $md5 = getFieldByUserName($basic_info[2], "icon");
            if (checkExist($md5))
              echo getFile($md5);
            else
              echo DEFAULT_ICON;
          ?>" />
          <span class="meta_author"><?php echo getFieldByUserName($basic_info[2], "nick_name"); ?></span>
          <span class="meta_date"><?php echo $basic_info[3] ?></span>
        </div>
      </div>
      <div id="content" class="entry_content uk-htmleditor-preview" style="overflow-y: visible; margin-bottom: 3em;">
      </div>

      <script>

        function delParent(ele)
        {
          if (ele.parent && classie.has(ele.parent, "entry_comment-hover")) classie.remove(ele.parent, "entry_comment-hover");
          if (ele.parent) delParent(ele.parent);
        }

        function setReply(comment_id, user_name)
        {
          document.getElementById("reply-tag").style.display = "";
          document.getElementById("reply-text").innerHTML = "Reply to " + user_name + " ";
          document.getElementById("comment-id").value = comment_id;
          document.getElementById("jumper").click();
        }

        function cancelReply()
        {
          document.getElementById("reply-tag").style.display = "none";
          document.getElementById("comment-id").value = "";
        }

        function deleteComment(comment_id)
        {
          document.getElementById("comment-id").value = comment_id;
          document.getElementById("comment-action").value = "delete";
          document.getElementById("comment-form").submit();
        }

      </script>
      <div class="separating-line"></div>
      <div id="comment-div" class="entry_content">
        <i class="fa fa-comment"></i>
        <span class="headtext" style="cursor: default; font-size: 1em;">Comments</span><br>
        <a id="jumper" href="#comment-div" style="display: none;"></a>
        <form id="comment-form" action="/back/update_comment.php?" style="padding: 0px 0 20px;" method="post">
          <input name="id" type="text" style="display: none;" value="<?php echo $_GET['id']; ?>">
          <input id="comment-id" name="comment_id" type="text" style="display: none;" value="">
          <input id="comment-action" name="action" type="text" style="display: none;" value="add">
          <textarea id="comment-content" name="content" placeholder="Content" data-uk-htmleditor="{markdown: true, maxsplitsize: 2048, height: 200}"></textarea>
          <div style="width: 100%; text-align: right;">
            <span id="reply-tag" class="simple-tag" style="display: none; margin-top: 2px;"><span id="reply-text"></span><i class="fa fa-close simple-botton" onclick="cancelReply();"></i></span>
            <button class="common-botton submit-botton" type="submit" style="margin-top: 5px;">
              <i class="fa fa-check"></i>
            </button>
          </div>
        </form>
        <?php
          
          import("core/utils/comment.php");

          // setCommentByID($_GET['id'], null);
          // rearrangeComment($list);
          $list = array_reverse(getComment($_GET['id']));
          foreach ($list as $comment) {
            echo $comment->generateComment();
          }

        ?>
      </div>
      <!--?php

      import("core/utils/comment.php");

      $comment1 = new Comment("lancelot", "New day!");
      
      $reply1 = $comment1->addReply("sonia", "yes!");
      $reply1->addReply("lancelot", "thanks");

      addCommentByID($_GET['id'], $comment1);

      //$list = unserialize(serialize(array($comment1, $comment2)));

      //echo $list[0]->generateComment();
      //echo $list[1]->generateComment();

      ?-->
  </div>

  <?php
    echo "<script>document.getElementById('content').innerHTML = marked(";
    echo json_encode($basic_info[5]);
    echo ");</script>";
  ?>

  <?php include "com/foot.php"?>

</body>

</html>
