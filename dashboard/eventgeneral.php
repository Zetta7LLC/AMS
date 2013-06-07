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

		/********* Events General Graph **********/
		$sqlGetEventGeneral = "SELECT count(*) AS yAxis, at.name AS xAxis
							 FROM activityLog al
							 INNER JOIN activityType at ON al.typeId = at.typeId
							 WHERE appKey = ?
							 GROUP BY at.name";
		
		$paramArr = array($GLOBALS["appkey"]);
		
		$results = executePreparedSelect($sqlGetEventGeneral, $paramArr);
		echo displayGraphData($results);
	?>

	$(document).ready(function(){
		if(callGraphFunction)
			barGraph('mainGraph', graphData, '<?php echo $GLOBALS["appname"]; ?>: Events General');
	});
</script>
<?php
	require_once("includes/navigation.php");
	require_once("includes/mainGraph.php");
?>

<a style="float:left;margin-left:290px;" href="eventspecific.php?eventid=0">See specific events</a>

<?php
	require_once("includes/footer.php");
?>		