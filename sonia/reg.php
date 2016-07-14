<!DOCTYPE html>
<html>

<head>

  <?php define("IS_REG", true); ?>
  <title>Register</title>
  <?php import("com/public_head.php"); ?>
  <link rel="stylesheet" href="/css/uikit.min.css" />
  <link rel="stylesheet" href="/css/com/notify.almost-flat.min.css" />
  <link rel="stylesheet" href="/css/com/form-file.almost-flat.min.css" />
  <link rel="stylesheet" type="text/css" href="/css/global.css" />
  <link rel="stylesheet" type="text/css" href="/fonts/font-awesome-4.4.0/css/font-awesome.min.css" />
  <link rel="stylesheet" type="text/css" href="/css/main.css" />

  <script src="/js/jquery.js"></script>
  <script src="/js/uikit.min.js"></script>
  <script src="/js/com/notify.min.js"></script>

  <link rel="stylesheet" href="/css/com/tooltip.almost-flat.min.css" />
  <script src="/js/com/tooltip.js"></script>

</head>

<body>

  <script src="/js/utils.js"></script>
  <script src="/js/ajex.js"></script>
  <script src="/js/reg.js"></script>

  <?php import("com/top_panel.php"); ?>

  <div class="main_wapper profile-board" style="margin-top: 4em;">
    <legend class="headtext uk-text-center" style="width: 100%; margin-top: 4em;">
      <a class="go_button">
        <span style="font-size: 8em;">Welcome</span>
      </a>
      <div id="messageBox_field" class="uk-form-row" style="font-size: 1.5em;"></div>
    </legend>
    <div id="top" class="tm-main" style="margin-top: 3em;">
      <center>
        <form id="main_form" action="/back/reg.php?<?php echo urlencode($_SERVER['QUERY_STRING']); ?>" class="uk-form uk-form-horizontal form-reg" method="post" enctype="multipart/form-data">
          <legend class="form-legend">Basic</legend>
          <div class="uk-form-row uk-text-left">
            <label class="uk-form-label">User Name</label>
            <div class="uk-form-controls">
              <input id="user_name" name="user_name" type="text" placeholder="Required when logging in">
            </div>
          </div>
          <div class="uk-form-row uk-text-left">
            <label class="uk-form-label">Nick Name</label>
            <div class="uk-form-controls">
              <input id="nick_name" name="nick_name" type="text" placeholder="Display name">
            </div>
          </div>
          <div class="uk-form-row uk-text-left">
            <span class="uk-form-label">Avatar</span>
            <div class="uk-form-controls uk-form-controls-text">
              <div class="uk-form-file">
                <!--button class="uk-button" type="button" onclick="document.getElementById('form-file').click();">Select</button-->
                <input name="icon" id="form-file" type="file" onchange="document.getElementById('preview').src = window.URL.createObjectURL(this.files.item(0));" />
                <img id="preview" src="/img/default.jpg" style="width: 7em; height: 7em; border: 0; border-radius: 50%; margin-bottom: 0.7em; cursor: pointer;" onclick="document.getElementById('form-file').click();" />
              </div>
            </div>
          </div>
          <div class="uk-text-left" style="margin-top: 1em;">
            <label class="uk-form-label uk-text-left">Self Intro</label>
            <div class="uk-form-row uk-text-left">
              <textarea id="self_intro" name="self_intro" placeholder="Less than 164 characters(or 82 Chinese characters)" style="width: 100%; height: 10em; margin-top: 0.7em;"></textarea>
            </div>
          </div>

          <legend class="form-legend">Secure &amp; Privilege</legend>
          <div class="uk-form-row uk-text-left">
            <label class="uk-form-label">Password</label>
            <div class="uk-form-controls">
              <input id="password" name="password" type="password" placeholder="Password">
            </div>
          </div>
          <div class="uk-form-row uk-text-left">
            <label class="uk-form-label">Group</label>
            <div class="uk-form-controls">
              <select id="group" name="group">
                <option value="0">Administrator</option>
                <option value="1">Editor</option>
                <option value="2">Norm</option>
              </select>
            </div>
          </div>
          <div class="uk-form-row uk-text-center" style="margin-top: 4em;">
            <button class="uk-button uk-button-success" onclick="checkAll(); doJump('#top');" href="javascript:checkAll(); doJump('#top');" type="button">Register</button>
          </div>
        </form>
      </center>
    </div>
  </div>

  <?php include "com/foot.php"?>

</body>

</html>