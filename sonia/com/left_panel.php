<?php
    import("core/utils/user.php");
    import("core/file/io.php");

    $icon = "";
    $session_id = isset($_COOKIE["session_id"]) ? $_COOKIE['session_id'] : "";
    if ($session_id && checkSessionID($session_id)) {
      $md5 = getFieldBySessionID($session_id, "icon");
      $icon = checkExist($md5) ? getFile($md5) : DEFAULT_ICON;
    }
?>

<link rel="stylesheet" href="/css/left_panel.css" />
<div class="left-panel">
  <img class="icon-common left-panel-avatar" src="<?php echo $icon; ?>" <?php if (defined("IS_PROFILE")) echo 'data-uk-modal="{target:\'#choose-icon\'}"'; ?>/>
  <div class="left-panel-icon-set">
    <?php
      import("core/utils/utils.php");
      $tooltip = (($device = getDevice()) == 'iPhone' || $device == 'iPad') ? "" : 'data-uk-tooltip="{pos: \'right\', animation: true, offset: 10}"';
    ?>
    <div id="contribution" class="left-panel-icon" onclick="itemOnClick(this.id);" <?php echo $tooltip; ?> title="Contribution">
      <i class="fa fa-graduation-cap"></i>
    </div>
    <div id="favorite" class="left-panel-icon" onclick="itemOnClick(this.id);" <?php echo $tooltip; ?> title="Favorite">
      <i class="fa fa-heart"></i>
    </div>
    <div id="settings" class="left-panel-icon" onclick="itemOnClick(this.id);" <?php echo $tooltip; ?> title="Settings">
      <i class="fa fa-cog"></i>
    </div>
    <div id="inbox" class="left-panel-icon" onclick="itemOnClick(this.id);" <?php echo $tooltip; ?> title="Inbox">
      <i class="fa fa-inbox"></i>
    </div>
    <div id="new" class="left-panel-icon" onclick="itemOnClick(this.id);" <?php echo $tooltip; ?> title="New">
      <i class="fa fa-plus"></i>
    </div>

    <div class="left-panel-icon icon-bottom" <?php echo $tooltip; ?> title="Sign out" <?php if (defined("IS_PROFILE")) echo 'onclick="delCookie(\'session_id\'); doJump(\'/\');"'; ?>>
      <i class="fa fa-sign-out"></i>
    </div>
  </div>
</div>