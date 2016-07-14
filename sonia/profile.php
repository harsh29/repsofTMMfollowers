<!DOCTYPE html>
<html>

<head>

  <?php define("IS_PROFILE", true); ?>
  <title>Profile</title>
  <?php import("com/public_head.php"); ?>
  <link rel="stylesheet" href="/css/codemirror.css" />
  <link rel="stylesheet" href="/css/uikit.min.css" />
  <link rel="stylesheet" href="/css/com/notify.almost-flat.min.css" />
  <link rel="stylesheet" href="/css/com/form-file.almost-flat.min.css" />
  <link rel="stylesheet" href="/css/com/htmleditor.almost-flat.min.css" />
  <link rel="stylesheet" href="/css/com/tooltip.almost-flat.min.css" />
  <link rel="stylesheet" type="text/css" href="/css/global.css" />
  <link rel="stylesheet" type="text/css" href="/fonts/font-awesome-4.4.0/css/font-awesome.min.css" />
  <link rel="stylesheet" type="text/css" href="/css/main.css" />
  <link rel="stylesheet" type="text/css" href="/css/card.css" />
  <link rel="stylesheet" type="text/css" href="/css/profile.css" />
  <link rel="stylesheet" type="text/css" href="/css/edit.css" />
  <link rel="stylesheet" type="text/css" href="/css/inbox.css" />

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
  <script src="/js/com/tooltip.js"></script>

</head>

<body>

  <div id="background" style="position: fixed; z-index: 0; background: white; height: 100%; width: 100%; display: none;"></div>

  <script src="js/utils.js"></script>
  <script src="js/classie.js"></script>
  <script src="js/ajex.js"></script>
  <script src="js/md5.js"></script>
  <script src="js/profile.js"></script>
  <script src="js/inbox.js"></script>

  <?php import("com/left_panel.php"); ?>

  <?php require("profile/com_choose_icon.php"); ?>

  <div class="main_wapper" style="padding: 0 0 1em; margin-left: 3.6em;">
    <?php
      import("core/utils/utils.php");
      import("core/utils/user.php");
      import("core/file/io.php");

      $url = "login.php?cb=" . urlencode($_SERVER['PHP_SELF']) . "&cb_q=" . urlencode($_SERVER['QUERY_STRING']);
      $session_id = $_COOKIE['session_id'];
      $has_login = false;

      //echo $session_id;
      if (!($has_login = checkSessionID($session_id))) {
        echo "<script>";
        echo "doJump('" . $url . "');";
        echo "</script>";
      }

      $md5 = getFieldBySessionID($session_id, "icon");
      $nick_name = getFieldBySessionID($session_id, "nick_name");
      $user_name = getFieldBySessionID($session_id, "user_name");
      $group_id = getFieldBySessionID($session_id, "group_id");
      $self_intro = getFieldBySessionID($session_id, "self_intro");
      $icon = checkExist($md5) ? getFile($md5) : DEFAULT_ICON;
      /*
      echo '<div class="uk-text-center" style="padding-top: 2em;">';
      echo '<img class="icon-common" src="' . $icon . '" style="width: 10em; height: 10em; margin: 1em; margin-top: 0.7em; cursor: pointer;" data-uk-modal="{target:\'#choose-icon\'}" />';
      echo '</br><span class="headtext" style="font-size: 2em; font-weight: bold; cursor: default;">' . $nick_name . '</span>';
      echo '<i class="fa fa-sign-out logout_btn" onclick="delCookie(\'session_id\'); doJump(\'/\');" style="margin-left: 0.3em" data-uk-tooltip="{pos: \'right\', animation: true, delay: 500}" title="Sign out"></i>';
      echo '<br><span class="headtext" style="font-size: 1em; font-weight: 500; cursor: default;">' . formatContent($self_intro) . '</span><br>';
      echo '</div>';*/
    ?>

    <?php require("profile/com_contribution_board.php"); ?>
    <?php require("profile/com_favorite_board.php"); ?>
    <?php require("profile/com_new_board.php"); ?>
    <?php require("profile/com_settings_board.php"); ?>
    <?php require("profile/com_inbox_board.php"); ?>
    <?php require("profile/com_dashboard_board.php"); ?>

  </div>
  <script>
    var anchor = window.location.hash ? window.location.hash.substr(1) : "";

    if (anchor && document.getElementById(anchor)) {
      document.getElementById(anchor).click();
      itemOnClick(anchor);
    }
    else {
      document.getElementById("contribution").click();
      itemOnClick("contribution");
    }

    window.onhashchange = function () {
      var anchor = window.location.hash ? window.location.hash.substr(1) : "";
      document.getElementById(anchor).click();
      itemOnClick(anchor);
    }
  </script>

</body>

</html>
