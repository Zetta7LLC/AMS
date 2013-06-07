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

		/********* Events Warning Graph **********/
		$sqlGetEventWarning = "SELECT count(*) AS yAxis, al.message xAxis
							   FROM activityLog al
							   WHERE typeId = 3 AND appKey = ?
							   GROUP BY al.typeId, al.message";
		
		$paramArr = array($GLOBALS["appkey"]);
		
		$results = executePreparedSelect($sqlGetEventWarning, $paramArr);
		
		echo displayGraphData($results);
	?>

	$(document).ready(function(){
		if(callGraphFunction)
			barGraph('mainGraph', graphData, '<?php echo $GLOBALS["appname"]; ?>: Warning Event');
	});
</script>
<?php
	require_once("includes/navigation.php");
	require_once("includes/mainGraph.php");
?>

<a style="float:left;margin-left:290px;" href="eventspecific.php?eventid=2">See specific warning events</a>

<?php
	require_once("includes/footer.php");
?>		