<html>

<head>
  <link rel="stylesheet" type="text/css" href="/css/global.css" />
</head>

<body>
  
  <script src="/js/utils.js"></script>
  <?php
    import("core/constants.php");
    import("core/do_search.php");
    import("core/utils/user.php");
    import("core/utils/utils.php");
    //include "core/utils/utils.php";

    $session_id = $_COOKIE['session_id'];
    $group_id = getGroupIDBySessionID($session_id);
    $id = $_POST['id'] == "new" ? getNextID() : $_POST['id'];

    $case_all_privilege = $group_id <= PV_EDIT_ALL_ENTRY;
    $case_edit_self = $group_id <= PV_EDIT_OWN_WRITTEN_ENTRY && !(isEntryExist($id) && getUserNameBySessionID($session_id) != getEntryBasicInfo($id)[2]);


    if (checkSessionID($session_id) && ($case_all_privilege || $case_edit_self)) {
      if ($group_id <= PV_EDIT_ALL_ENTRY)
        $user = $_POST['user_name'];
      else $user = getUserNameBySessionID($session_id);
      $title = $_POST['title'];
      $subtitle = $_POST['subtitle'];
      $content = $_POST['content'];
      $cover = isEntryExist($id) ? getEntryBasicInfo($id, true)[6] : "";

      if (isEntryExist($id) && ($origin_author = getEntryBasicInfo($id)[2]) != $user)
        delContributionByUserName($origin_author, $id);
      addContributionByUserName($user, $id);

      if (!$_FILES["cover"]["error"]) {
        $cover = md5_file($_FILES["cover"]["tmp_name"]);
        if (checkExist($cover)) {
          //echo "0"; //echo "Same file has already uploaded";
        } else {
          storeUploadedFile($_FILES["cover"]["tmp_name"], $cover);
          //echo "Successfully uploaded";
          //echo $md5;
        }
      } else if ($_FILES["cover"]["error"] != 4 /* no file loaded */) {
        echo $_FILES["cover"]["error"];
      }

      if (!isEntryExist($id)) {
        trace("creating directory");
        //mkdir($_SERVER['DOCUMENT_ROOT'] . "wiki/" . $id);
        if (!file_exists(WIKI_DIR)) {
          //trace("creating wiki directory");
          mkdir(WIKI_DIR);
        }
        mkdir(getEntrybyID($id));
      }

      $meta_file = fopen(getEntrybyID($id) . "/meta.php", "w");
      fputs($meta_file, "<?php\n");
      fputs($meta_file, "\$title = '" . $title . "';\n");
      fputs($meta_file, "\$subtitle = '" . $subtitle . "';\n");
      fputs($meta_file, "\$author = '" . $user . "';\n");
      fputs($meta_file, "\$date = '" . date('m/d/y', time()) . "';\n");
      fputs($meta_file, "\$content = 'content.txt';\n");
      fputs($meta_file, "\$cover = '$cover';\n");
      fputs($meta_file, " ?>");
      fclose($meta_file);

      $content_file = fopen(getEntrybyID($id) . "/content.txt", "w");
      fputs($content_file, $content);
      fclose($content_file);

      echo "<script>";
      echo "doJump('/entry.php?id=" . $id . "');";
      echo "</script>";
    } else {
      println("Permission Denied :-(");
      println("Detected abnormal behavior. IP address recorded.");
    }
  ?>

</body>

</html>
