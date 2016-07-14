<?php
if (!empty($GLOBALS['_SHOW_CARD_PHP_'])) return;
$GLOBALS['_SHOW_CARD_PHP_'] = true;

import("core/do_search.php");
import("core/constants.php");
import("core/utils/user.php");
import("core/utils/entry.php");

function showCard($id, $board = "contribution")
{
  $is_favorite = false;
  $session_id = isset($_COOKIE['session_id']) ? $_COOKIE['session_id'] : "";
  if ($session_id && checkSessionID($session_id)) {
    $is_favorite = in_array($id, getLikeBySessionID($session_id));
  }
  $basic_info = getEntryBasicInfo($id);
  $md5 = getFieldByUserName($basic_info[2], "icon");
  $icon = checkExist($md5) ? getFile($md5) : DEFAULT_ICON;
  $show_selector = defined("IS_PROFILE");
  $click_action = "doJump('entry.php?id=" . $id . "', true);";
  echo
      '<div class="card">';
  if ($show_selector) {
    echo '<div id="' . $board . '-selector' . $id . '" class="selector" style="position: absolute; top: -5px; left: -5px; z-index: 986;" onclick="classie.toggle(this, \'selector-checked\'); selectorToggle(' . $id . ', \'' . $board . '\');">
            <i class="fa fa-check selector-check"></i>
          </div>';
    $click_action = "classie.toggle(document.getElementById('" . $board . "-selector" . $id . "'), 'selector-checked'); selectorToggle('" . $id . "', '" . $board . "');";
  }
  echo '<div id="' . $board . '-container' . $id . '" class="card_container card_container-closed" style="padding: 6px; padding-bottom: 27px; background: #fff;">
          <div onclick="' . $click_action . '" style="height: 100%; overflow: hidden; cursor: pointer; border-radius: 2px;">
            <img src="' . $basic_info[6] . '" style="min-width: 100%; min-height: 100%;"></img>
          </div>
          <!--h2 class="card_title" style="font-size: 1em;">' . $basic_info[0] . '</h2-->
          <div class="card_toolbar">
            <i id="' . $board . '-fav' . $id . '" class="fa ' . ($is_favorite ? "fa-heart card_toolbar-item_hover" : "fa-heart-o card_toolbar-item") . '" onclick="' . ($is_favorite ? "delFavorite('" . $board . "', " . $id . ")" : "addFavorite('" . $board .  "', " . $id . ")") . '"></i>
            <span id="' . $board . '-fav-count' . $id . '" class="fav-count" style="' . ($is_favorite ? "" : "display: none;") . '">' . count(getEntryLike($id)) . '</span>
            <!--i class="fa fa-commenting-o card_toolbar-item card_toolbar-item-blue" style="display: inline-block; margin: 0 0.5em 10px;"></i-->
            <div style="float: right; height: 27px;">
              <span class="card_toolbar-item-icon card_toolbar-item-author">' . getFieldByUserName($basic_info[2], "nick_name") . '</span>
              <img src="' . $icon . '" class="card_toolbar-item card_toolbar-item-icon"/>
            </div>
          </div>';
  echo
      '</div></div>';
  // require show_card.js
}
?>
