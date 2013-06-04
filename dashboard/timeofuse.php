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
	<?php

		/********* Average Time of Use per Day *********/
		$sqlTimeOfUse = "SELECT MIN(startStamp) startStamp, DATE_FORMAT(startStamp, '%e-%b-%y') xAxis, AVG(TIMEDIFF(endStamp, startStamp)) yAxis
						 FROM(
							SELECT start.deviceId, start.createstamp startStamp, MIN(end.createStamp) endStamp 
							FROM(
								SELECT deviceId, createStamp 
								FROM activityLog 
								WHERE message = 'onCreate' AND appKey = ?
								) start
							INNER JOIN(
								SELECT deviceId, createStamp 
								FROM activityLog 
								WHERE message = 'onStop' AND appKey = ?
									  ) end ON start.deviceId = end.deviceId AND start.createStamp < end.createStamp
							GROUP BY start.deviceId, startStamp) a
						 GROUP BY xAxis
						 ORDER BY startStamp";

		$paramArr = array($GLOBALS["appkey"], $GLOBALS["appkey"]);
		
		$results = executePreparedSelect($sqlTimeOfUse, $paramArr);
		echo displayGraphData($results);
	?>

	$(document).ready(function(){
		if(callGraphFunction)
			lineGraph('mainGraph', graphData, '<?php echo $GLOBALS["appname"]; ?>: Average Time of Use (Seconds)');
	});
</script>
<?php
	require_once("includes/navigation.php");
	require_once("includes/mainGraph.php");
?>		