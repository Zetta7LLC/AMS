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
if(isset($_GET["action"])){
	if($_GET["action"] == "addapp"){
		$appName = $_GET["appname"];
		if(!empty($appName)){
			$appKey = md5(uniqid());
			$sqlInsertApp = "INSERT INTO application (appName, appKey) VALUES (?, ?);";
			$paramArr = array($appName, $appKey);
			$result = executePreparedDIU($sqlInsertApp, $paramArr);
			if($result != -1){
				echo "success";
			}else{
				echo "error";
			}	
		}
	}else if($_GET["action"] == "removeapp"){
		$appKey = $_GET["appkey"];
		if(!empty($appKey) && strlen($appKey) == 32){
			$sqlRemoveApp = "DELETE FROM application WHERE appKey = ? ;";
			$paramArr = array($appKey);
			$result = executePreparedDIU($sqlRemoveApp, $paramArr);
			if($result != -1){
				$removeData = $_GET["removedata"];
				if($removeData == "1"){
					$sqlRemoveAppData = "DELETE FROM activityLog WHERE appKey = ? ;";
					$result = executePreparedDIU($sqlRemoveAppData, $paramArr);
					if($result != -1){
						echo "success";
					}else{
						echo "error";
					}
				}else{
					echo "success";
				}
			}else{
				echo "error";
			}
		}
	}
}
?>
