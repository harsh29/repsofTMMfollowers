<?php

function daysAfter($date, $days, $format = "Y-m-d")
{
	return date($format, strtotime("$date +$days day"));
}

function daysBefore($date, $days, $format = "Y-m-d")
{
	return date($format, strtotime("$date -$days day"));
}

?>
