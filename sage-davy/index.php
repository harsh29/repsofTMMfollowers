<?php

// error_reporting(11);

include "core/date.php";
include "core/view_count.php";
# include "showcase.php"

if (setViewedCookie()) {
	$con = cnxt();
	addCountOnDate($con, date("Y-m-d"));
}

include "showcase.php";

?>
