<script src="/js/utils.js"></script>
<?php
error_reporting(0);

import("core/constants.php");
import("core/file/io.php");
import("core/utils/utils.php");
import("core/utils/user.php");

if (checkSessionID($session_id = $_COOKIE['session_id'])) {
	$md5 = "";

	if (!$_FILES["icon"]["error"]) {
	  $md5 = md5_file($_FILES["icon"]["tmp_name"]);
	  if (checkExist($md5)) {
	    //echo "0"; //echo "Same file has already uploaded";
	  } else {
	    storeUploadedFile($_FILES["icon"]["tmp_name"], $md5);
	    //echo "Successfully uploaded";
	    //echo $md5;
	  }
	  setFieldBySessionID($session_id, "icon", $md5);
	} else {
		if ($_FILES["icon"]["error"] == 4 /* no file uploaded */) {
			setFieldBySessionID($session_id, "icon", "");
		} 
	  echo $_FILES["icon"]["error"];
	}
}

?>
<script>
  doJump("/profile.php");
</script>