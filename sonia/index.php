<!DOCTYPE html>
<html>

<head>

  <?php define("IS_INDEX", true); ?>

  <title>Inleak</title>
  <?php import("com/public_head.php"); ?>
  <link rel="stylesheet" href="/css/uikit.min.css" />
  <link rel="stylesheet" href="/css/com/notify.almost-flat.min.css" />
  <link rel="stylesheet" type="text/css" href="css/global.css" />
  <link rel="stylesheet" type="text/css" href="fonts/font-awesome-4.4.0/css/font-awesome.min.css" />
  <link rel="stylesheet" type="text/css" href="css/main.css" />

  <script src="/js/jquery.js"></script>
  <script src="/js/uikit.min.js"></script>
  <script src="/js/com/notify.min.js"></script>

  <link rel="stylesheet" href="/css/com/tooltip.almost-flat.min.css" />
  <script src="/js/com/tooltip.js"></script>

</head>

<body>

  <script src="js/utils.js"></script>
  <script src='js/ajex.js'></script>
  <script src="js/message/message.js"></script>

  <div class="main_wapper">
    <?php
      import("core/constants.php");
      import("core/utils/user.php");
      import("core/utils/config.php");
      import("core/file/init.php");
      import("core/message/message.php");
      //setMessageByField("user_name", "sonia", array());
      //setMessageByField("user_name", "lancelot", array());
      //addMessageByField("user_name", "sonia", "shit");
      //addLikeBySessionID($_COOKIE['session_id'], "1");
      //echo getLikeBySessionID($_COOKIE['session_id'])[0];
      //delLikeBySessionID($_COOKIE['session_id'], "1");
      //updateSessionID("sonia");
      //setFieldByUserName("sonia", "icon", "f3bd2eba213287f9b737cf552fa023c8");
      //init_base(GET_FILE_DIR);

      //setConfig("copy_right", "People in the city");
      //$session_id = $_COOKIE['session_id'];
      
      /*if ($session_id) { //setFieldBySessionID($session_id, "group_id", "0");
        addContributionBySessionID($session_id, 1);
        addContributionBySessionID($session_id, 2);
        addContributionBySessionID($session_id, 3);
        addContributionBySessionID($session_id, 4);
        addContributionBySessionID($session_id, 5);
        addContributionBySessionID($session_id, 6);
        addContributionBySessionID($session_id, 7);
        addContributionBySessionID($session_id, 8);
        addContributionBySessionID($session_id, 9);
        addContributionBySessionID($session_id, 10);

      }*/
      //setFieldBySessionID($session_id, "contribution", serialize(array()));
    ?>

    <?php import("com/top_panel.php"); ?>

    <!--div class="search_box_div" style="margin-top: 5%;">
      <img src="img/logo.png" style="max-width: 20%;"/>
    </div-->

    <div class="search_box_div search-box-index">
      <label class="go_button">
        <span class="logo-index"><span style="color: #828282;">In</span>leak</span>
        <?php
          import("core/constants.php");
          if (DEBUG_MODE) {
            echo '<span class="fa fa-bug" style="font-size: 2em; cursor: pointer;" onclick="UIkit.notify(\'Version: ' . VERSION . '\', {timeout: 3000});"></span>';
          }
        ?>
      </label><br>

      <span class="input input-wave input-index">
        <!--
          <input class="input_field input_field-wave" type="text" id="search_input" onkeypress="if (event.keyCode == 13) doSearch(search_input.value);" placeholder="A SINGLE WORD IS ENOUGH" />
          <img class="graphic graphic-wave" src="img/line.png" width="300%" height="100%" viewBox="0 0 1200 60"></img>
        -->
        <input class="input_field input_field-wave" type="text" id="search_input" onkeypress="if (event.keyCode == 13) doSearch(search_input.value);" />
        <label class="input_label input_label-wave" for="search_input">
            <span class="input_label-content">TYPE FOR SURPRISE</span>
        </label>
        <svg class="graphic graphic-wave" width="300%" height="150%" viewBox="0 0 1200 60" preserveAspectRatio="none">
          <path d="M0,56.5c0,0,298.666,0,399.333,0C448.336,56.5,513.994,46,597,46c77.327,0,135,10.5,200.999,10.5c95.996,0,402.001,0,402.001,0"/>
          <path d="M0,2.5c0,0,298.666,0,399.333,0C448.336,2.5,513.994,13,597,13c77.327,0,135-10.5,200.999-10.5c95.996,0,402.001,0,402.001,0"/>
        </svg>
      </span><br>

      <!--label class="go_button" onclick="doSearch(search_input.value);">
        <span style="font-size: 15px; cursor: pointer;">GO</span>
      </label-->
    </div>
  
  </div>

  <?php import("com/foot.php")?>  
  <script src="js/search_input.js"></script>
  <script>
    document.getElementById("search_input").focus();
  </script>

</body>

</html>
