<!DOCTYPE html>
<html>

<head>

  <?php define("IS_LOGIN", true); ?>
  <title>Log-In!</title>
  <?php import("com/public_head.php"); ?>
  <link rel="stylesheet" href="/css/uikit.min.css" />
  <link rel="stylesheet" href="/css/com/notify.almost-flat.min.css" />
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
  <script src="/js/login.js"></script>

  <?php import("com/top_panel.php"); ?>

  <center>
    <div class="main_wapper profile-board login-board" style="margin-top: 4em;">
      <legend class="headtext uk-text-center" style="width: 100%; margin-top: 4em;">
        <a class="go_button">
          <span style="font-size: 7em;">Log In</span>
        </a>
        <div id="messageBox_field" class="uk-form-row" style="font-size: 1.5em; margin-top: 1em;"></div>
      </legend>
        <form class="uk-form uk-text-center form-login2">
          <fieldset>
            <div class="uk-form-row">
              <input id="user_name" class="uk-form-width-medium" type="text" placeholder="User Name" onkeypress="if (event.keyCode == 13) document.getElementById('password').focus();">
            </div>
            <div class="uk-form-row">
              <input id="password" class="uk-form-width-medium" type="password" placeholder="Password" onkeypress="if (event.keyCode == 13) checkAndLogin();">
            </div>
          </fieldset>
        </form>
      <div class="uk-form-row uk-text-center" style="margin-top: 40px;">
          <button class="uk-button" onclick="checkAndLogin();" href="javascript:checkAndLogin();">Login</button>
          or
          <?php
            echo '<a href="/reg.php?cb=';
            echo $_GET['cb'] . '&cb_q=' . $_GET['cb_q'] . '">';
            echo 'Register</a>';
          ?>
      </div>
    </div>
  </center>

  <?php import("com/foot.php")?>

  <script>
    document.onreadystatechange = function () {
      if (document.readyState == "complete") {
        document.getElementById("user_name").focus();
      }
    };
  </script>
</body>

</html>
