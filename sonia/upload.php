<!DOCTYPE html>
<html>

<head>

  <?php import("com/public_head.php"); ?>
  <link rel="stylesheet" href="css/uikit.min.css" />
  <link rel="stylesheet" type="text/css" href="css/global.css" />
  <link rel="stylesheet" type="text/css" href="fonts/font-awesome-4.4.0/css/font-awesome.min.css" />
  <link rel="stylesheet" type="text/css" href="css/main.css" />

  <script src="js/jquery.js"></script>
  <script src="js/uikit.min.js"></script>

  <link rel="stylesheet" href="/css/com/tooltip.almost-flat.min.css" />
  <script src="/js/com/tooltip.js"></script>

</head>

<body>

  <script src="/js/utils.js"></script>
  <script>
    var is_init = true;
    function getMD5()
    {
      if (is_init) {
        is_init = false;
        return;
      }
      var iframe = document.getElementById('post_frame').contentWindow;
      var ret = iframe.document.body.innerText;
      if (ret.length > 1) {
        alert("Successfully uploaded, handler: " + ret);
        document.getElementById("show").src = "fb_local/" + ret.substr(0, 1) + "/" + ret;
      } else {
        alert("Error code: " + ret);
      }
    }
  </script>

  <form action="core/file/upload.php" method="post" enctype="multipart/form-data" class="uk-form uk-text-center" style="margin-top: 5%;"
        target="post_frame">
    <div class="go_button" style="margin-bottom: 5em;">
      <span style="font-size: 50px;">Upload</span>
    </div>

    <button type="button" class="uk-button" onclick="document.getElementById('form-file').click();">Select</button>
    <input name="upfile" id="form-file" type="file" style="display: none;" onchange="document.getElementById('file-name').innerHTML = getFileName(this.value);" />
    <a id="file-name">No file selected</a>
    <div class="uk-form-row uk-text-center" style="margin-top: 20px;">
      <button class="uk-button" type="submit" name="submit">Submit</button>
    </div>
  </form>

  <iframe id="post_frame" name="post_frame" style="" onload="getMD5();"></iframe>
  <img id="show"></img>

  <?php import("com/foot.php")?>

</body>

</html>
