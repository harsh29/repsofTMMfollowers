<a name="top" id="top" style="height: 0; position: absolute; top: -3em;"></a>
<?php

import("core/utils/user.php");
import("core/utils/utils.php");

$hasLogin = false;
if (isset($_COOKIE["session_id"]) && checkSessionID($_COOKIE["session_id"])) $hasLogin = true;
$showIcon = !defined("IS_LOGIN") && !defined("IS_REG") && !defined("IS_PROFILE");
$showBanner = defined("IS_LOGIN") || defined("IS_REG") || defined("IS_EDIT");
$tooltip = (($device = getDevice()) == 'iPhone' || $device == 'iPad') ? "" : 'data-uk-tooltip="{pos: \'bottom\', animation: true, offset: 10}"';

?>
<div id="top_panel" class="top_panel_div <?php if ($showBanner) echo "top_panel_div_banner"; ?>">
  <div class="top_panel_icon_set" <?php if ($showIcon && $hasLogin) echo "style='margin-top: 1em;'"; ?>>
    <?php
      if (!defined("IS_INDEX")) {
        echo '<i class="fa fa-home top_panel_icon" onclick="doJump(\'/\');" style="font-size: 18px;" ' . $tooltip . ' title="Home"></i>';
        echo '<i class="fa fa-bug top_panel_icon" ' . $tooltip . ' title="Bug report"></i>';
        //href="#my-id" class="uk-button" data-uk-smooth-scroll
        echo '<a id="goto-top" class="fa fa-rocket top_panel_icon" href="#top" data-uk-smooth-scroll ' . $tooltip . ' title="Top"></a>';
        if (defined("IS_ENTRY")) {
          echo '<a class="fa fa-commenting top_panel_icon" href="#comment-div" style="margin-bottom: 1px;" data-uk-smooth-scroll ' . $tooltip . ' title="Comments"></a>';
        }
      }
    ?>
  </div>
  <?php
    import("core/utils/user.php");
    import("core/file/io.php");

    if ($showIcon) {
      $text = "Login";
      $icon = "";
      $url = "login.php?cb=" . urlencode($_SERVER['PHP_SELF']) . "&cb_q=" . urlencode($_SERVER['QUERY_STRING']);
      $session_id = isset($_COOKIE["session_id"]) ? $_COOKIE['session_id'] : "";
      $target = "_self";
      if ($session_id && checkSessionID($session_id)) {
        $text = getFieldBySessionID($session_id, "nick_name"); // getUserNameBySessionID($session_id);
        $md5 = getFieldBySessionID($session_id, "icon");
        $icon = checkExist($md5) ? getFile($md5) : DEFAULT_ICON;
        $url = "profile.php";
        $target = "_blank";
      }

      //echo '<div class="top_panel_user"';
      echo '<a class="top_panel"' . ($url ? ' href="' . $url . '"' : "") . ' target="' . $target . '">';
      if ($icon)
        echo '<span style="font-size: 12px;">';
      else
        echo '<span style="font-size: 12px; margin-right: 1em;">';

      echo $text;

      echo '</span>';
      if ($icon)
        echo '<img class="icon-common" src="' . $icon . '" align="absmiddle" style="width: 2.5em; height: 2.5em; margin: 0.7em 0.7em 1em 0.5em; cursor: pointer;"/>';
      echo '</a>';
    }
  ?>
</div>
<script src="js/classie.js"></script>
<script>
var current_scroll_top = -1;
var has_triggered = false;

function setGotoTopBtn() {
  if (!document.getElementById("goto-top")) return;
  scroll_top = document.body.scrollTop || document.documentElement.scrollTop;

  if (scroll_top == 0) {
    document.getElementById("goto-top").style.display = "none";
  } else document.getElementById("goto-top").style.display = "";
}

window.onscroll = function () {
  setGotoTopBtn();
  scroll_top = document.body.scrollTop || document.documentElement.scrollTop;

  if (scroll_top == 0) {
    classie.remove(document.getElementById("top_panel"), "top_panel_div_hover");
  } else {
    classie.add(document.getElementById("top_panel"), "top_panel_div_hover");
  }
}

setGotoTopBtn();
</script>