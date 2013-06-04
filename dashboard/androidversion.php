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
		/********* ANDROID VERSION **********/
		$sqlGetAndroidVersion = "SELECT deviceSDK AS xAxis, count(*) AS yAxis
										 FROM
											 (SELECT deviceSDK, deviceId 
											  FROM activityLog 
											  WHERE appKey = ?
											  GROUP BY deviceId
											 ) a
										 GROUP BY deviceSDK";

		$paramArr = array($GLOBALS["appkey"]);

		$results = executePreparedSelect($sqlGetAndroidVersion, $paramArr);

		echo displayGraphData($results);
	?>

	$(document).ready(function(){
		if(callGraphFunction)
			piChart('mainGraph', graphData, "<?php echo $GLOBALS["appname"]; ?>: Android Version");
	});
</script>
<?php
	require_once("includes/navigation.php");
	require_once("includes/mainGraph.php");
?>		