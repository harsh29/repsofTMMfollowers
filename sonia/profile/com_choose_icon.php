<a href="#choose-icon" data-uk-modal></a>
<iframe id="like_back" style="display: none;"></iframe>
<div id="choose-icon" class="uk-modal">
    <div class="uk-modal-dialog">
        <a class="uk-modal-close uk-close"></a>
        Choose Avatar
        <form id="icon_form" action="/back/change_icon.php" class="uk-form uk-form-horizontal" method="post" enctype="multipart/form-data">
          <div class="uk-form-row uk-text-center">
            <div class="uk-form-file" style="margin-top: 2em;">
              <img class="icon-common" id="preview" src="/img/default.jpg" style="width: 10em; height: 10em; margin-bottom: 0.7em; cursor: pointer;" onclick="document.getElementById('icon-file').click();" /><br>
              <button class="uk-button" type="submit" style="margin-top: 2em; margin-bottom: 1em;">Update</button>
              <input id="icon-file" name="icon" type="file" onchange="document.getElementById('preview').src = window.URL.createObjectURL(this.files.item(0));" />
            </div>
          </div>
        </form>
    </div>
</div>