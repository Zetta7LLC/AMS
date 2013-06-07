<?php
/*
Copyright 2013 Zetta7 LLC - Bellevue, WA

This file is part of AMS (Android Monitoring Service) created by Zetta7 LLC.

    AMS is free software: you can redistribute it and/or modify
    it under the terms of the GNU General Public License as published by
    the Free Software Foundation, either version 3 of the License, or
    any later version.

    AMS is distributed in the hope that it will be useful,
    but WITHOUT ANY WARRANTY; without even the implied warranty of
    MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
    GNU General Public License for more details.

    You should have received a copy of the GNU General Public License
    along with AMS.  If not, see <http://www.gnu.org/licenses/>.
*/
	require_once('includes/header.php');
?>
<script type="text/javascript">
</script>
	
<?php
	require_once("includes/navigation.php");
?>
<div id="infoContainer" style="position:relative;width:780px;float:left;padding-left:10px;padding-top:32px;">
	<h2>General information about your app(s)</h2>
<?php
	echo "<h3 style='margin-bottom:0px;'>Number of Applications <a href='manageapps.php' style='font-size: 14px;'>(manage)</a></h3>";
	$sql = "SELECT count(*) as numOfApps FROM application";
	
	$results = executePreparedSelect($sql, null);
	echo "<strong style='color:#000'>" . $results[0]["numOfApps"] . "</strong>";


	echo "<h3 style='margin-bottom:0px;'>Total Hours Spent</h3>";
	$sql = "SELECT sum(TIMEDIFF(endStamp, startStamp)) totalTimeSpent
			FROM(
			SELECT start.deviceId, start.createstamp startStamp, MIN(end.createStamp) endStamp
			FROM(
			SELECT deviceId, createStamp 
			FROM activityLog 
			WHERE message = 'onCreate'
			) start
			INNER JOIN(
			SELECT deviceId, createStamp 
			FROM activityLog 
			WHERE message = 'onStop'
			 ) end ON start.deviceId = end.deviceId AND start.createStamp < end.createStamp
			GROUP BY start.deviceId, startStamp) a";
	
	$results = executePreparedSelect($sql, null);
	echo "<strong style='color:#000'>" . $results[0]["totalTimeSpent"] . "</strong>";

	echo "<h3 style='margin-bottom:0px;'>Total number of unique users</h3>";
	$sql = "SELECT COUNT( DISTINCT deviceID ) as uniqueUser FROM activityLog";

	$results = executePreparedSelect($sql, null);
	echo "<strong style='color:#000'>" . $results[0]["uniqueUser"] . "</strong>";

	echo "<h3 style='margin-bottom:0px;'>Total Errors Warning Info Success</h3>";
	$sql = "SELECT count(*) AS events, at.name AS eventType
			FROM activityLog al
			INNER JOIN activityType at ON al.typeId = at.typeId
			GROUP BY at.name";
	
	$results = executePreparedSelect($sql, null);
	
	$tableToOutPut = "<table class='gradienttable'><tr><th>Event</th><th>Count</th></tr>";
	foreach($results as $row)
	{
		$tableToOutPut .= "<tr><td>" . $row["eventType"] . "</td><td>" . $row["events"] . "</td></tr>";
	}
	$tableToOutPut .= "</table>";

	echo $tableToOutPut;
?>		
</div>
<?php
require_once("includes/mainGraph.php");
?>