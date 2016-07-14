<!DOCTYPE html>
<html>

<head>

  <title>Search Results: <?php echo $_GET['kw'] ?></title>
  <?php import("com/public_head.php"); ?>
  <link rel="stylesheet" href="/css/uikit.min.css" />
  <link rel="stylesheet" href="/css/com/notify.almost-flat.min.css" />
  <link rel="stylesheet" type="text/css" href="css/global.css" />
  <link rel="stylesheet" type="text/css" href="fonts/font-awesome-4.4.0/css/font-awesome.min.css" />
  <link rel="stylesheet" type="text/css" href="css/main.css" />
  <link rel="stylesheet" type="text/css" href="css/card.css" />

  <script src="/js/jquery.js"></script>
  <script src="/js/uikit.min.js"></script>
  <script src="/js/com/notify.min.js"></script>

  <link rel="stylesheet" href="/css/com/tooltip.almost-flat.min.css" />
  <script src="/js/com/tooltip.js"></script>

</head>

<body>

	<script src="/js/utils.js"></script>
  <script src='js/ajex.js'></script>
  <script src="js/message/message.js"></script>

  <iframe id="like_back" style="display: none;"></iframe>
  <div class="main_wapper">
    <?php import("com/top_panel.php"); ?>

    <div class="input-search-container" id="search_box" style="margin-top: 2em;">
      <label class="go_button search-box-label">
        <span style="font-size: 3em; cursor: pointer;" onclick="doJump('/');"><span style="color: #828282;">In</span>leak</span>
      </label>
      <span class="input input-wave input-search">
        <input class="input_field input_field-wave" type="text" id="search_input" onkeypress="if (event.keyCode == 13) doSearch(search_input.value);" value="<?php echo $_GET['kw'] ?>" />

        <label class="input_label input_label-wave" for="search_input">
          <span class="input_label-content input_label-content-wave">Search</span>
        </label>
        <svg class="graphic graphic-wave" width="300%" height="100%" viewBox="0 0 1200 60" preserveAspectRatio="none">
          <path d="M0,56.5c0,0,298.666,0,399.333,0C448.336,56.5,513.994,46,597,46c77.327,0,135,10.5,200.999,10.5c95.996,0,402.001,0,402.001,0"/>
          <path d="M0,2.5c0,0,298.666,0,399.333,0C448.336,2.5,513.994,13,597,13c77.327,0,135-10.5,200.999-10.5c95.996,0,402.001,0,402.001,0"/>
        </svg>
      </span>
    </div>

    <div class="content">
      <div class="wrapper">
        <?php
          import("core/constants.php");
          import("core/do_search.php");
          import("core/show_card.php");

          $page = isset($_GET['pg']) ? $_GET['pg'] : 1;
          $entry_per_page = isset($_GET['per']) ? $_GET['per'] : 9;

          $results = doSearch($_GET['kw'], $page_count, $page, $entry_per_page);
          //trace("page_count: " . $page_count);
          //trace("");
          //trace("Show:");
          //foreach ($results as $result) {
          //  trace($result[0] . ": " . $result[1]);
          //}
          foreach ($results as $result) {
            showCard($result[4]);
          }

          if (!count($results)) {
            echo '<span style="font-size: 1em; font-weight: 500; margin: 2%;">';
            echo 'Nahh...Use keyword <a href="search.php?kw=@all">@all</a> to list all entries';
            echo '</span>';
          }
        ?>
      </div>
      <div class="pagination" style="margin-top: 2em;">
        <a>
          <?php

          $self = $_SERVER['PHP_SELF'];

          function getHref($_self, $_keyword, $_page, $_entry_per_page)
          {
              return $_self . '?kw=' . $_keyword . '&pg=' . $_page . '&per=' . $_entry_per_page;
          }

          if ($page > 1)
            echo '<a class="fa fa-chevron-left page_slider" href="' . getHref($self, $_GET['kw'], $page - 1, $entry_per_page) . '"></a>';

          for ($i = 0; $i < $page_count; $i++) {
            if (($i + 1) == $page) {
              echo '<a class="page_num chosen"';
            } else echo '<a class="page_num"';
            echo ' href="' . getHref($self, $_GET['kw'], $i + 1, $entry_per_page) . '"> ' . ($i + 1) . '</a>';
          }

          if ($page < $page_count)
            echo ' <a class="fa fa-chevron-right page_slider" href="' . getHref($self, $_GET['kw'], $page + 1, $entry_per_page) . '"></a>';

          ?>
        </a>
      </div>
    </div>
  </div>

  <?php import("com/foot.php")?>
  <script src="js/search_input.js"></script>
  <script src="/js/classie.js"></script>
  <script src="/js/ajex.js"></script>
  <script src="/js/show_card.js"></script>

</body>

</html>
