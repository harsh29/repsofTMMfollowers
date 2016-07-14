<div id="inbox_board">
	<div id="inbox-edit-panel" class="edit-panel" style="display: none;">
		<?php
		  import("core/utils/utils.php");
		  $tooltip = (($device = getDevice()) == 'iPhone' || $device == 'iPad') ? "" : 'data-uk-tooltip="{pos: \'bottom\', animation: true, offset: 20}"';
		?>
		<button class="common-botton delete-botton small-icon with-border edit-panel-button" onclick="doDeleteMessage();" <?php echo $tooltip; ?> title="Delete the selected">
		  <i class="fa fa-trash"></i>
		</button>
		<button class="common-botton normal-botton small-icon with-border edit-panel-button" <?php echo $tooltip; ?> title="View">
		  <i class="fa fa-eye"></i>
		</button>
		<span id="inbox-selected-count" class="normal-tag-text" style="cursor: default;"></span>
	</div>
	<div id="message-list" class="inbox-left-right-align" style="margin-top: 1em;">
		<?php

		import("core/constants.php");
		import("core/message/message.php");
		import("core/utils/user.php");

		$session_id = isset($_COOKIE["session_id"]) ? $_COOKIE["session_id"] : "";
		if ($session_id && checkSessionID($session_id)) {
			$msg_list = getMessageByField("session_id", $session_id);
			$head_time = null;
			$current_time = time();
			$block_index = 0;
			$i = 0;
			echo
				'<script>
					var inbox_message_block = new Array();
				</script>';
			foreach (array_reverse($msg_list) as $message) {
				if (!$head_time || ($message->timestamp + INBOX_DIVIDING_RANGE < $head_time)) {
					$head_time = $message->timestamp;
					$is_today = date("d", $current_time) == date("d", $head_time);
					$display_time = ($is_today ? "" : date("m/d ", $head_time)) . date("h:i a", $head_time);
					echo '<span id="inbox-block' . $block_index . '" class="inbox-headline" style="display: inline-block; margin-bottom: 0; margin-top: 0.7em;">' . $display_time . '</span>';
					echo
						'<script>
							inbox_message_block.push(new Array());
						</script>';
					$block_index++;
				}
				echo
					'<div id="inbox-item' . $i . '" class="inbox-item" onclick="messageToggle(' . $i . ');">
						<img class="meta_avatar icon-common inbox-avatar" align="absmiddle" style="width: 1.5em; height: 1.5em;" src="' . getIconByUserName($message->sender) . '" />
						<span class="inbox-user-name">' . $message->sender . '</span>
						<span class="inbox-message-preview uk-text-truncate" style="display: inline-block;">' . $message->text . '</span>
					</div>';
				echo
					'<script>
						inbox_message_block[inbox_message_block.length - 1].push(' . $i . ');
					</script>';
				$i++;
			}
			echo '<div id="no-message" class="inbox-headline" style="display: none; width: 100%; margin-top: 1em; text-align: center; color: #C4C4C4;">No message</div>';
			echo
				'<script>
					var inbox_message_count = ' . $i . ';
					refreshMessage();
				</script>';
		}

		?>
		<!--span class="inbox-headline" style="display: inline-block; margin-bottom: 0.5em;">10:30 am</span>
		<div class="inbox-item">
			<img class="meta_avatar icon-common" align="absmiddle" style="width: 1.5em; height: 1.5em;" src="/img/default.jpg" />
			<span class="inbox-user-name">Sonia</span>
			<span class="inbox-message-preview uk-text-truncate" style="display: inline-block;">message text ddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddd</span>
			<button class="common-botton delete-botton small-icon inbox-delete-button" onclick="doDelete();">
		      <i class="fa fa-trash"></i>
		    </button>
		</div>
		<div class="inbox-item">
			<img class="meta_avatar icon-common" align="absmiddle" style="width: 1.5em; height: 1.5em;" src="/img/default.jpg" />
			<span class="inbox-user-name">Lancelot</span>
			<span class="inbox-message-preview uk-text-truncate" style="display: inline-block;">message text ddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddd</span>
			<button class="common-botton delete-botton small-icon inbox-delete-button" onclick="doDelete();">
		      <i class="fa fa-trash"></i>
		    </button>
		</div>
		<span class="inbox-headline" style="display: inline-block; margin-top: 1em; margin-bottom: 0.5em;">9:30 am</span>
		<div class="inbox-item">
			<img class="meta_avatar icon-common" align="absmiddle" style="width: 1.5em; height: 1.5em;" src="/img/default.jpg" />
			<span class="inbox-user-name">Sonia</span>
			<span class="inbox-message-preview uk-text-truncate" style="display: inline-block;">message text ddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddd</span>
			<button class="common-botton delete-botton small-icon inbox-delete-button" onclick="doDelete();">
		      <i class="fa fa-trash"></i>
		    </button>
		</div>
		<div class="inbox-item">
			<img class="meta_avatar icon-common" align="absmiddle" style="width: 1.5em; height: 1.5em;" src="/img/default.jpg" />
			<span class="inbox-user-name">Lancelot</span>
			<span class="inbox-message-preview uk-text-truncate" style="display: inline-block;">message text ddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddd</span>
			<button class="common-botton delete-botton small-icon inbox-delete-button" onclick="doDelete();">
		      <i class="fa fa-trash"></i>
		    </button>
		</div-->
	</div>
</div>