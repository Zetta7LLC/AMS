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

		/********* USER PATH **********/
		$sqlGetUserPath = "SELECT a1.message m1, a2.message m2, count(*) count
							FROM
								(SELECT al1.activityId actIdA, MIN(al2.activityId) actIdB
								 FROM activityLog al1
								 LEFT OUTER JOIN activityLog al2 ON al1.deviceId = al2.deviceId 
								 WHERE al1.activityId < al2.activityId AND al1.typeId = 4 AND al2.typeId = 4 AND al1.appKey = ? AND al2.appKey = ?
								 GROUP BY al1.deviceId, al1.createStamp
								) a3
							LEFT OUTER JOIN
							activityLog a1 ON a1.activityId = a3.actIdA
							INNER JOIN
							activityLog a2 ON a2.activityId = a3.actIdB
							WHERE a1.appKey = ? AND a2.appKey = ?
							GROUP BY a1.message, a2.message
							ORDER BY count(*) DESC";
		
		$paramArr = array($GLOBALS["appkey"], $GLOBALS["appkey"], $GLOBALS["appkey"], $GLOBALS["appkey"]);
		
		$results = executePreparedSelect($sqlGetUserPath, $paramArr);
		
		$tableToOutPut = "<table class=\'gradienttable\'><tr><th>First Location</th><th>Second Location</th><th>Count</th></tr>";
		foreach($results as $row)
		{
			$tableToOutPut .= "<tr><td>" . $row["m1"] . "</td><td>" . $row["m2"] . "</td><td>" . $row["count"] . "</td></tr>";
		}
		$tableToOutPut .= "</table>";
		echo "var userPathData = \"" . $tableToOutPut . "\";";
		
	?>

	$(document).ready(function(){
		document.getElementById("mainGraph").innerHTML = userPathData;
	});
</script>

<?php
	require_once("includes/navigation.php");
	require_once("includes/mainGraph.php");
	require_once("includes/footer.php");
?>		