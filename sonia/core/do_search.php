<?php
if (!empty($GLOBALS['_DO_SEARCH_PHP_'])) return;
$GLOBALS['_DO_SEARCH_PHP_'] = true;

import("core/constants.php");
import("core/utils/utils.php");
import("core/file/io.php");

function checkID($id, $keyword)
{
  $meta_file = getEntrybyID($id) . "/meta.php";

  if (file_exists($meta_file)) {
    include $meta_file;
    similar_text(strtolower($keyword), strtolower($title), $similar);

    //trace("title: " . $title . "; subtitle: " . $subtitle . "; similar percentage: " . $similar . "%");

    return $similar;
  } else {
    trace("error: cannot find meta file in entry id " . $id);
  }
}

function getContent($id, $content_file, $raw_content = false)
{
  $handle = file(getEntrybyID($id) . "/" . $content_file);
  $content = "";
  foreach ($handle as $str) {
      $content .= $str . ($raw_content ? "" : "</br>");
  }
  //echo "content: " . $content;

  return $content;
}

function getEntryBasicInfo($id, $raw_content = false)
{
  include getEntrybyID($id) . "/meta.php";
  return array($title, $subtitle, $author, $date, $id, getContent($id, $content, $raw_content),
               $raw_content ? $cover : (isset($cover) && checkExist($cover) ? getFile($cover) : DEFAULT_COVER));
}

function doSearch($keyword, &$page_count, $page = 1, $entry_per_page = 9)
{
  //trace("start searching of keyword \"" . $keyword . "\"");
  $found = travDir(WIKI_DIR);
  $match = array();
  $is_all = false;

  if ($keyword == "@all") $is_all = true;

  foreach ($found as $id) {
      $similar = checkID($id, $keyword);
      if ($similar >= 40 || $is_all) {
        $match[$id] = $similar;
      }
  }

  //trace("");
  //trace("results:");

  arsort($match);
  $i = 1;
  $ret = array();
  $page_count = intval(count($match) / $entry_per_page) + (count($match) % $entry_per_page ? 1 : 0);
  foreach ($match as $id => $val) {
    if ($i > (($page - 1) * $entry_per_page)
        && $i <= $page * $entry_per_page) {
      $ret[] = getEntryBasicInfo($id);
    }
    //trace($i . ". match(" . $val . "%): " . $id);
    $i++;
  }

  return $ret;
}

?>
