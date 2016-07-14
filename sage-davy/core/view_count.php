<?php

function cnxt()
{
	/*
	$con = mysql_connect("localhost", "root", "0d0e530008f4ed5a");
	mysql_select_db("sage_showcase", $con);
	return $con;
	*/
	return new mysqli("localhost", "root", "0d0e530008f4ed5a", "sage_showcase");
}

function getViewOnDate($con, $date)
{
	$sql = "SELECT * FROM `view_count` WHERE `time_stamp` = \"" . $date . "\"";
	$data = $con->query($sql);

	if ($data) return $data->fetch_array();

	return null;
}

function addViewOnDate($con, $date)
{
	$sql = "INSERT INTO `view_count`(`count`, `time_stamp`) VALUES (0, '$date')";
	$con->query($sql);
	return;
}

function getCountOnDate($con, $date)
{
	$data = getViewOnDate($con, $date);

	if ($data) return $data["count"];

	return 0;
}

function addCountOnDate($con, $date)
{
	$sql = "UPDATE `view_count` SET `count`= `count` + 1 WHERE `time_stamp` = \"" . $date . "\"";

	if (!getViewOnDate($con, $date)) {
		addViewOnDate($con, $date);
	}

	$con->query($sql);

	return;
}

function setCountOnDate($con, $date, $val)
{
	$sql = "UPDATE `view_count` SET `count`= \"" . $val . "\" WHERE `time_stamp` = \"" . $date . "\"";
	
	if (!getViewOnDate($con, $date)) {
		addViewOnDate($con, $date);
	}

	$con->query($sql);

	return;
}

/* return: true for first view today; false for having been set */
function setViewedCookie()
{
	if (!isset($_COOKIE["has_viewed"])) {
		setcookie("has_viewed", true, strtotime(daysAfter(date("Y-m-d"), 1)));
		return true;
	}

	return false;
}

?>
