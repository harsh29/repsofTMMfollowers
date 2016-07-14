<link rel="stylesheet" type="text/css" href="/css/global.css" />
<script src="/js/utils.js"></script>
<?php
error_reporting(0);

import("core/constants.php");
import("core/file/io.php");
import("core/utils/utils.php");
import("core/utils/user.php");

$user_name = $_POST['user_name'];
$nick_name = $_POST['nick_name'];
$password = $_POST['password'];
$group = $_POST['group'];
$self_intro = $_POST['self_intro'];
$md5 = "";

echo $_POST['password'];

if (!$_FILES["icon"]["error"]) {
  $md5 = md5_file($_FILES["icon"]["tmp_name"]);
  if (checkExist($md5)) {
    //echo "0"; //echo "Same file has already uploaded";
  } else {
    storeUploadedFile($_FILES["icon"]["tmp_name"], $md5);
    //echo "Successfully uploaded";
    //echo $md5;
  }
} else {
  echo $_FILES["icon"]["error"];
}

createAccount($user_name, $nick_name, $password, $md5, $self_intro, $group);

?>
<script>
  doJump("/login.php");
</script>
