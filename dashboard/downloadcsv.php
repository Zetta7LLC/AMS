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
	require_once('includes/amslib.php');
    
    $arrAppInfo = getApplicationInfo();

    $GLOBALS['appkey'] = $arrAppInfo[0];
    $GLOBALS['appname'] = $arrAppInfo[1];
?>
<?php
        $eventId = getQueryNum($_GET["eventid"]);
        $whereStatement = "";
        if($eventId != 0)
        {
            $whereStatement = "al.typeId = " . $eventId . " AND ";
        }

        /********* Get Event Info **********/
        $sqlGetEventWarning = "SELECT al.typeId, at.name type, al.message, al.description, al.notes, count(*) count
                               FROM activityLog al INNER JOIN activityType at ON al.typeId = at.typeId
                               WHERE " . $whereStatement . "appKey = ? 
                               GROUP BY message, description, notes
                               ORDER BY count(*) DESC";
        
        $paramArr = array($GLOBALS["appkey"]);
        
        $results = executePreparedSelect($sqlGetEventWarning, $paramArr);
        
        $csvArr = array();
        $csvArrCount = 0;

        $typeName = "";
        foreach($results as $row)
        {
            $csvArr[$csvArrCount++] = $row;
            $typeName = $row["type"];
        }

        if($eventId == 0)
            $typeName = "";

        download_send_headers($GLOBALS['appname'] . "-" . $typeName . ".csv");
        echo array2csv($csvArr);
?>