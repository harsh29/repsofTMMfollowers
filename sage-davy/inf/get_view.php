<?php

include "core/date.php";
include "core/view_count.php";

function getDaysViewCountTo($date, $count /* count of days */)
{
	$con = cnxt();
	$ret = "[";
	$i = 0;

	while ($i < $count) {
		if ($i > 0) {
			$ret .= ", ";
		}
		$ret .= getCountOnDate($con, daysBefore($date, $i));
		$i++;
	}

	return $ret . "]";
}

function getDisplayDate($date, $count)
{
	$con = cnxt();
	$ret = "[";
	$i = 0;

	while ($i < $count) {
		if ($i > 0) {
			$ret .= ", ";
		}
		
		if ($i == 0) $ret .= "\"today\"";
		else if ($i == 1) $ret .= "\"yesterday\"";
		else $ret .= "\"" . daysBefore($date, $i, "m-d") . "\"";
		
		$i++;
	}

	return $ret . "]";
}

if (isset($_GET["date"]) &&
	isset($_GET["count"])) {
	$date = date("Y-m-d", strtotime($_GET["date"]));
	echo getDaysViewCountTo($date, $_GET["count"]);
}

?>
