<?php
/*
	Copyright 2013 Zetta7 LLC - Bellevue, WA

	This file is part of AMS (Application Monitoring Service) created by Zetta7 LLC.

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

//Credentials for db connection
$myDBServer = "";
$myDBUserName = "";
$myDBPassword = "";
$myDBName = "";

//Returns db object if connection successful, null otherwise
function connectToDB(){
	global $myDBServer, $myDBUserName, $myDBPassword, $myDBName;
	$db = null;
	# connect to the database  
	try {  
		$db = new PDO("mysql:host=" . $myDBServer . ";dbname=" . $myDBName . ";charset=utf8", $myDBUserName, $myDBPassword);
		$db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
		$db->setAttribute(PDO::ATTR_EMULATE_PREPARES, false);  
	}  
	catch(PDOException $e) {
		showSQLError($e->getMessage(),""); 
		$db = null;
	} 
	return $db;
}
//Executes SELECT statements only! Returns a result set or null
function executeSelect($query){
	$db = connectToDB();
	$result = null;
	if($db != null){
		try{
			$stmt = $db->query($query);
			$result = $stmt->fetchAll(PDO::FETCH_ASSOC);
		}catch(PDOException $e){
			showSQLError($e->getMessage(),$query);
		}
		$db = null;
	}
	return $result;
}
//Executes UPDATE, INSERT or DELETE statements. Returns number of affected rows
function executeDIU($query){
	$db = connectToDB();
	$result = -1;
	if($db != null){
		try{
			$result = $db->exec($query);
		}catch(PDOException $e){
			showSQLError($e->getMessage(),$query);
		}
		$db = null;
	}
	return $result;
}
//Executes SELECT using a prepared statement. This is a more secure version of executeSelect
function executePreparedSelect($query, $arrParams){
	$db = connectToDB();
	$result = null;
	if($db != null){
		try{
			$stmt = $db->prepare($query);
			$stmt->execute($arrParams);
			$result = $stmt->fetchAll(PDO::FETCH_ASSOC);
		}catch(PDOException $e){
			showSQLError($e->getMessage(),$query);
		}
		$db = null;
	}
	return $result;
}
//Executes DIU prepared statemens. This is more secure than executeDIU
function executePreparedDIU($query, $arrParams){
	$db = connectToDB();
	$result = -1;
	if($db != null){
		try{
			$stmt = $db->prepare($query);
			$stmt->execute($arrParams);
			$result = $stmt->rowCount();
		}catch(PDOException $e){
			showSQLError($e->getMessage(),$query);
		}
		$db = null;
	}
	return $result;
}
//Display SQL error.
function showSQLError($errorMsg, $query) 
{
	$url = replaceSingletick($_SERVER['REQUEST_URI']);
	$serverIP = $_SERVER['SERVER_ADDR'];
	$clientIP = $_SERVER['REMOTE_ADDR'];
	#Log this somewhere safe
	#echo "Whoops... Unexpected error. <br/> ErrorMsg: [" . $errorMsg . "] <br/> URI: " . $url . "<br/> SERVER IP: " . $serverIP . " <br/> CLIENT IP: " . $clientIP . "<br/> Query: " . $query;
}

//Replaces single tick with ` for db complience
function replaceSingletick($subject){
	return str_replace("'", "`",$subject);
}

//format the graph data, the first column has quotes around it and the second is a number
function ConvertToJSArray($results)
{
	$strDataToDisplay = "[";
	foreach($results as $row )
	{
		if($strDataToDisplay != "[")
			$strDataToDisplay.= ",";
		
		$strDataToDisplay .= "['" . $row["xAxis"] . "', " . $row["yAxis"] . "]";
	}
	$strDataToDisplay .= "]";

	return $strDataToDisplay;
}

//gets the application id that is in the cookie. 
//if there isnt a cookie, it will choose one at random
function getApplicationInfo()
{
	$results = null;
	if(isset($_COOKIE['amsApplication']) && $_COOKIE['amsApplication'] != "")
	{
		$appKey = $_COOKIE['amsApplication'];
		$sqlGetAppInfo = "SELECT appKey, appName
					   FROM application
					   WHERE appKey = ?";

	   $paramArr = array($appKey);

	   $results = executePreparedSelect($sqlGetAppInfo, $paramArr);
	}
	else
	{
		$sqlGetAppInfo = "SELECT appKey, appName
										 FROM application 
										 ORDER BY CASE WHEN obsolete = 0 THEN 0 ELSE 1 END LIMIT 1";
				
		$results = executePreparedSelect($sqlGetAppInfo, null);

	}

	setcookie('amsApplication', $results[0]["appKey"], time() + (86400 * 10), '/', '', false, false);

	return array(0 => $results[0]["appKey"], 1 => $results[0]["appName"]);
}

function displayGraphData($results)
{
	if(count($results) == 0)
	{
		return "var callGraphFunction = false;$(document).ready(function(){document.getElementById('mainGraph').innerHTML = 'There appears to be no data available at this time.';});";
	}
	else
	{	
		return "var callGraphFunction = true;var graphData = " . ConvertToJSArray($results) . ";";
	}
}

//Validates the the querystring has a number
function getQueryDecimal($qString){
	if(is_null($qString)){ //null
		return 0;
	}
	else if(is_float($qString)){ //a double
		return $qString;
	}
	else{ //a string
		//$ptn = "/[^0-9.]*/";
		//return  preg_replace($ptn, "", $qString);
		$strReturn = 0.0; //cast as float;
		$strReturn += $qString;	
		return $strReturn;
	}
}
//Validates that input is either an integer or decimal
function getQueryNum($qString){
	if(is_null($qString)){ //null
		return 0;
	}
	else if(is_int($qString)){ //an intereger
		return $qString;
	}
	else{ //its a string
		$qString = getQueryDecimal($qString);
		$strReturn = 0;
		$strReturn += $qString;
		return $strReturn;
	}
}

?>
