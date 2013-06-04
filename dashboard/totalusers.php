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

		/********* Total Users **********/
		$sqlGetTotalUsers = "SELECT count(*) yAxis, DATE_FORMAT(createstamp, '%e-%b-%y') xAxis, MAX(createStamp) cStamp
							 FROM
								(SELECT deviceId, MIN(createStamp) createStamp 
								 FROM activityLog 
								 WHERE appKey = ?
								 GROUP BY deviceId) a
							 GROUP BY xAxis 
							 ORDER BY cStamp";

		$paramArr = array($GLOBALS["appkey"]);
		
		$results = executePreparedSelect($sqlGetTotalUsers, $paramArr);
		
		//iterate through the counts to add them up to show total unique users
		$totalCount = 0;
		$arrResults = array();
		$arrIndex = 0;
		foreach($results as $row)
		{
			$totalCount += $row["yAxis"];
			$arrResults[$arrIndex++] = 
				array("xAxis" => $row["xAxis"], 
					  "yAxis" => $totalCount);
		}

		echo displayGraphData($arrResults);
	?>

	$(document).ready(function(){
		if(callGraphFunction)
			lineGraph('mainGraph', graphData, '<?php echo $GLOBALS["appname"]; ?>: Total Users');
	});
</script>
<?php
	require_once("includes/navigation.php");
	require_once("includes/mainGraph.php");
?>