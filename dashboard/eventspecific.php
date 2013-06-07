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
	require_once("includes/navigation.php");
?>
	<div style="float:left;width:795px;margin-left:7px;margin-top:35px;">
	<h3 style="float:left;">Specific Event View</h3>
	<?php
		$eventId = getQueryNum($_GET["eventid"]);
		
		echo '<select style="float:right;margin-top:27px;margin-right:8px;" onChange="changeEventType(this.value);">
			<option ';

		if($eventId == 0)
			echo "selected";

		echo ' value="0">
				--Event Type--
			</option>
			<option ';

		if($eventId == 1)
			echo "selected";

		echo ' value="1">
				Success
			</option>
			<option ';

		if($eventId == 2)
			echo "selected";

		echo ' value="2">
				Error
			</option>
			<option ';

		if($eventId == 3)
			echo "selected";

		echo ' value="3">
				Warning
			</option>
			<option ';

		if($eventId == 4)
			echo "selected";

		echo ' value="4">
				Info
			</option>
		</select>';

		$whereStatement = "";
		if($eventId != 0)
		{
			$whereStatement = "typeId = " . $eventId . " AND ";
		}

		/********* Get Event Info **********/
		$sqlGetEventWarning = "SELECT message, description, notes, count(*) c
							   FROM `activityLog` 
							   WHERE " . $whereStatement . "appKey = ? 
							   GROUP BY message, description, notes 
							   ORDER BY count(*) DESC";
		
		$paramArr = array($GLOBALS["appkey"]);
		
		$results = executePreparedSelect($sqlGetEventWarning, $paramArr);
		
		$tableToOutPut = "<table class='gradienttable'><tr><th>Message</th><th>Description</th><th>Notes</th><th>Count</th></tr>";
		foreach($results as $row)
		{
			$tableToOutPut .= "<tr><td>" . $row["message"] . "</td><td>" . $row["description"] . "</td><td>" . $row["notes"] . "</td><td>" . $row["c"] . "</td></tr>";
		}
		$tableToOutPut .= "</table>";

		echo $tableToOutPut;
	?>
</div>
<?php
	require_once("includes/footer.php");
?>		