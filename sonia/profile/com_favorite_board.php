<div id="favorite_board" style="display: none;">
  <div id="favorite-edit-panel" class="edit-panel" style="display: none;">
    <?php
      import("core/utils/utils.php");
      $tooltip = (($device = getDevice()) == 'iPhone' || $device == 'iPad') ? "" : 'data-uk-tooltip="{pos: \'bottom\', animation: true, offset: 20}"';
    ?>
    <button class="common-botton delete-botton small-icon with-border edit-panel-button" onclick="doDeleteFavorite();" <?php echo $tooltip; ?> title="Delete the selected">
      <i class="fa fa-trash"></i>
    </button>
    <button class="common-botton normal-botton small-icon with-border edit-panel-button" onclick="doView('favorite');" <?php echo $tooltip; ?> title="View">
      <i class="fa fa-eye"></i>
    </button>
    <span id="favorite-selected-count" class="normal-tag-text" style="cursor: default;"></span>
  </div>
  <div class="content">
    <div class="wrapper">
      <?php
        import("core/constants.php");
        import("core/do_search.php");
        import("core/show_card.php");

        $page = isset($_GET['pg']) ? $_GET['pg'] : 1;
        $entry_per_page = isset($_GET['per']) ? $_GET['per'] : 9;

        $session_id = $_COOKIE['session_id'];
        $results = getLikeBySessionID($session_id);
        $page_count = intval(count($results) / $entry_per_page) + (count($results) % $entry_per_page ? 1 : 0);
        //trace("page_count: " . $page_count);
        //trace("");
        //trace("Show:");
        //foreach ($results as $result) {
        //  trace($result[0] . ": " . $result[1]);
        //}
        $i = 1;
        foreach ($results as $id) {
          if ($i > (($page - 1) * $entry_per_page)
              && $i <= $page * $entry_per_page)
            showCard($id, "favorite");
          $i++;
        }
      ?>
    </div>
    <?php
        if (!count($results)) {
          echo '<div class="inbox-headline" style="text-align: center; width: 100%; color: #C4C4C4;">';
          echo 'Oops, you don\'t have any favorite entries right now';
          echo '</div>';
        }
    ?>
    <div class="pagination" style="margin-top: 2em;">
      <a>
        <?php

        $self = $_SERVER['PHP_SELF'];

        if ($page > 1)
          echo '<a class="fa fa-chevron-left page_slider" href="' . getHref($self, $page - 1, $entry_per_page) . '"></a>';

        for ($i = 0; $i < $page_count && $page_count > 1; $i++) {
          if (($i + 1) == $page) {
            echo '<a class="page_num chosen"';
          } else echo '<a class="page_num"';
          echo ' href="' . getHref($self, $i + 1, $entry_per_page) . '"> ' . ($i + 1) . '</a>';
        }

        if ($page < $page_count)
          echo ' <a class="fa fa-chevron-right page_slider" href="' . getHref($self, $page + 1, $entry_per_page) . '"></a>';

        ?>
      </a>
    </div>
  </div>
</div>
<script src="/js/ajex.js"></script>
<script src="/js/show_card.js"></script>