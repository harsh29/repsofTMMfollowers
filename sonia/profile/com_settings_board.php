<div id="settings_board" style="float: top; display: none; padding-top: 1.7em;">
  <div id="messageBox_field" class="uk-form-row"></div>
  <div style="position: relative; float: right; right: 2em;">
    <button class="common-botton submit-botton text-update" onclick="checkAndUpdate();">
      <i class="fa fa-check"></i>
    </button>
  </div>

  <form id="settings_form" class="uk-form uk-form-horizontal form-settings">
    <legend class="form-legend">Basic</legend>

    <div class="uk-form-row uk-text-left">
      <div class="uk-form-label">
        <label class="uk-form-label">User Name</label>
      </div>
      <div class="uk-form-controls uk-form-controls-text">
        <input id="user_name" name="user_name" type="text" value="<?php echo $user_name; ?>" disabled>
      </div>
    </div>

    <div class="uk-form-row uk-text-left" style="margin-top: 1em;">
      <div class="uk-form-label">
        <label class="uk-form-label uk-text-left">Nick Name</label>
      </div>
      <div class="uk-form-controls uk-form-controls-text">
        <input id="nick_name" name="nick_name" type="text" value="<?php echo $nick_name; ?>">
      </div>
    </div>

    <div class="uk-text-left" style="margin-top: 1em;">
      <label class="uk-form-label uk-text-left">Self Intro</label>
      <div class="uk-form-row uk-text-left">
        <textarea id="self_intro" name="self_intro" placeholder="Less than 164 characters(or 82 Chinese characters)" style="width: 100%; height: 10em; margin-top: 0.7em;"><?php echo $self_intro; ?></textarea>
      </div>
    </div><br>

    <legend class="form-legend">Secure &amp; Privilege</legend>
    <div class="uk-form-row uk-text-left">
      <div class="uk-form-label">
        <label class="uk-form-label uk-text-left">Password</label>
      </div>
      <div class="uk-form-controls uk-form-controls-text">
        <button id="show_password_button" class="uk-button uk-button-primary" type="button" onclick="passwordConfirm();">Change</button>
        <input id="password" name="password" type="password" style="display: none;" placeholder="New password">
      </div>
    </div>

    <div class="uk-form-row uk-text-left" style="margin-top: 1em;">
      <div class="uk-form-label">
        <label class="uk-form-label uk-text-left">Group</label>
      </div>
      <div class="uk-form-controls uk-form-controls-text">
        <select id="group_id" name="group_id" value="<?php echo $group_id; ?>" style="width: 10em;" <?php if (!($group_id <= PV_CHANGE_GROUP_ID)) echo "disabled"; ?>>
          <option value="0" <?php echo ($group_id == "0" ? 'selected="true"' : ""); ?>>Administrator</option>
          <option value="1" <?php echo ($group_id == "1" ? 'selected="true"' : ""); ?>>Editor</option>
          <option value="2" <?php echo ($group_id == "2" ? 'selected="true"' : ""); ?>>Norm</option>
        </select>
      </div>
    </div>
    <!--div class="uk-form-row">
      <span class="uk-form-label">Avatar</span>
      <div class="uk-form-controls uk-form-controls-text">
        <div class="uk-form-file">
          <button class="uk-button">Select</button>
          <input id="icon" name="icon" type="file" />
        </div>
      </div>
    </div-->
  </form>

</div>