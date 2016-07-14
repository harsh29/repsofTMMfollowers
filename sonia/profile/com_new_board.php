<div id="new_board" style="display: none; padding-top: 3em;">
  <div style="position: relative; float: right; margin: -1.3em 2em 1em 1em;">
    <button class="common-botton submit-botton" onclick="document.getElementById('new_form').submit();">
      <i class="fa fa-check"></i>
    </button>
  </div>
  <div class="tm-main">
    <form id="new_form" action="/back/edit.php?" class="uk-form form-edit" method="post" enctype="multipart/form-data">
      <div class="uk-form-row" style="display: none;">
        <label class="uk-form-label">ID</label>
        <div class="uk-form-controls">
          <input id="id" name="id" type="text" placeholder="Wiki ID" value="<?php echo "new"; ?>">
        </div>
      </div>

      <div class="uk-form-row">
        <label class="uk-form-label tip-label">Title</label>
        <br>
        <input id="title" class="uk-form-blank title" name="title" placeholder="Name" type="text" style="height: 50px; font-size: 2.2em;">
        <input id="subtitle" class="uk-form-blank subtitle" name="subtitle" placeholder="Describe" type="text" style="height: 50px; font-size: 1.4em;">
      </div>

      <div class="uk-form-row" style="display: none;">
        <label class="uk-form-label">Author</label>
        <div class="uk-form-controls">
          <input id="user_name" name="user_name" type="text" placeholder="Your user name" value="<?php
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
        <textarea name="content" data-uk-htmleditor="{markdown: true, height: 450}"></textarea>
      </div>
    </form>
  </div>
</div>