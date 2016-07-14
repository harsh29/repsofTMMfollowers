<!DOCTYPE html>
<html>

<head>

  <?php define("IS_EDIT", true); ?>
  <?php
    import("core/utils/utils.php");
    import("core/do_search.php");
    $is_edit = false;
    $basic_info = array("", "", "", "", "", "");

    if (!isset($_GET['id'])) $_GET['id'] = "new";

    if ($_GET['id'] && isEntryExist($_GET['id'])) {
      $basic_info = getEntryBasicInfo($_GET['id'], true);
      $is_edit = true;
    }
  ?>

  <title><?php echo $is_edit ? "Edit" : "Create"; ?></title>
  <?php import("com/public_head.php"); ?>
  <link rel="stylesheet" href="/css/codemirror.css" />
  <link rel="stylesheet" href="/css/uikit.min.css" />
  <link rel="stylesheet" href="/css/com/form-file.almost-flat.min.css" />
  <link rel="stylesheet" href="/css/com/htmleditor.almost-flat.min.css" />
  <link rel="stylesheet" href="/css/global.css" />
  <link rel="stylesheet" href="/fonts/font-awesome-4.4.0/css/font-awesome.min.css" />
  <link rel="stylesheet" href="/css/main.css" />
  <link rel="stylesheet" href="/css/card.css" />
  <link rel="stylesheet" href="/css/edit.css" />

  <script src="/js/codemirror/lib/codemirror.js"></script>
  <script src="/js/codemirror/mode/markdown/markdown.js"></script>
  <script src="/js/codemirror/addon/mode/overlay.js"></script>
  <script src="/js/codemirror/mode/xml/xml.js"></script>
  <script src="/js/codemirror/mode/gfm/gfm.js"></script>
  <script src="/js/marked.min.js"></script>
  <script src="/js/jquery.js"></script>
  <script src="/js/uikit.min.js"></script>
  <script src="/js/com/htmleditor.js"></script>

  <link rel="stylesheet" href="/css/com/tooltip.almost-flat.min.css" />
  <script src="/js/com/tooltip.js"></script>

</head>

<body>

  <?php import("com/top_panel.php"); ?>
  <div class="main_wapper profile-board" style="margin-top: 5em; padding: 0;">
    <div style="position: relative; float: right; margin: 1em;">
      <?php
        if ($is_edit) {
          echo '<button class="common-botton delete-botton" onclick="deleteConfirm();"><i class="fa fa-trash"></i></button>';
          echo '<iframe id="post_delete" style="display: none;"></iframe>';
        }
      ?>
      <button class="common-botton submit-botton" onclick="document.getElementById('main_form').submit();">
        <i class="fa fa-check"></i>
      </button>
    </div>
    <div class="main-div-edit">
      <script src="js/utils.js"></script>
      <?php
        echo "<script>";
        echo "var title = '" . $basic_info[0] . "';";
        echo "var id = '" . $_GET['id'] . "';";
      ?>
        function deleteConfirm()
        {
          UIkit.modal.prompt("Type the title of this entry to confirm:", "",
            function (val) {
                if (val == title) {
                  document.getElementById("post_delete").src = "back/delete_entry.php?id=" + id;
                  doJump("/");
                } else {
                  UIkit.modal.alert("Wrong title!");
                }
            });
        }
      </script>

      <?php
      import("core/utils/user.php");

      $url = "login.php?cb=" . urlencode($_SERVER['PHP_SELF']) . "&cb_q=" . urlencode($_SERVER['QUERY_STRING']);
      $session_id = $_COOKIE['session_id'];

      if (!checkSessionID($session_id)) {
        echo "<script>";
        echo "doJump('" . $url . "');";
        echo "</script>";
      }

      ?>

      <!--legend class="headtext uk-text-center" style="width: 100%; margin-top: 3em;">
        <a class="go_button">
          <span style="font-size: 7em;"><?php echo $is_edit ? "Edit" : "Create"; ?></span>
        </a>
        <div id="messageBox_field" class="uk-form-row"></div>
      </legend-->
      <div class="tm-main" style="margin-top: 3em;">
        <form id="main_form" action="/back/edit.php?" class="uk-form form-edit" method="post" enctype="multipart/form-data">
          <div class="uk-form-row" style="display: none;">
            <label class="uk-form-label">ID</label>
            <div class="uk-form-controls">
              <input id="id" name="id" type="text" placeholder="Wiki ID" value="<?php echo $_GET['id']; ?>" <?php if ($_GET['id'] == "new") echo "disabled"; ?>>
            </div>
          </div>

          <div class="uk-form-row">
            <label class="uk-form-label tip-label">Title</label>
            <br>
            <input id="title" class="uk-form-blank title" name="title" type="text" value="<?php echo $basic_info[0]; ?>" style="height: 50px; font-size: 2.2em;">
            <input id="subtitle" class="uk-form-blank subtitle" name="subtitle" placeholder="Describe" type="text" value="<?php echo $basic_info[1]; ?>" style="height: 50px; font-size: 1.4em;">
          </div>

          <div class="uk-form-row">
            <label class="uk-form-label tip-label">Author</label>
            <div class="uk-form-controls">
              <input id="user_name" name="user_name" type="text" placeholder="Your user name" value="<?php
                if ($is_edit)
                  echo $basic_info[2];
                else
                  echo getUserNameBySessionID($session_id);
              ?>" <?php import("core/constants.php"); if (!(getGroupIDBySessionID($session_id) <= PV_EDIT_ALL_ENTRY)) echo "disabled"; ?>>
            </div>
          </div>

          <div class="uk-form-row">
            <span class="uk-form-label tip-label">Snapshots</span><br>
            <div class="uk-form-file" style="margin-top: 0.5em;">
              <button class="uk-button" type="button" onclick="document.getElementById('form-file').click();">Select</button>
              <input id="form-file" name="cover" type="file" onchange="document.getElementById('preview').src = window.URL.createObjectURL(this.files.item(0));" />
              <!--img id="preview" src="/img/default.jpg" style="width: 7em; height: 7em; border: 0; border-radius: 50%; margin-bottom: 0.7em; cursor: pointer;" onclick="document.getElementById('form-file').click();" /-->
            </div>
          </div>

          <div class="uk-form-row" style="margin-top: 2em;">
            <!--label>Content</label><br><br-->
            <textarea name="content" placeholder="Content" data-uk-htmleditor="{markdown: true, maxsplitsize: 2048}"><?php echo $basic_info[5]; ?></textarea>
          </div>
        </form>
      </div>
    </div>
  </div>

  <?php include "com/foot.php"?>
</body>

</html>
