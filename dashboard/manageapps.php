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
<style type="text/css"> 
#addpanel, #removepanel
{
	text-align:center;
	background-color:#EFEFEF;
	border:solid 1px #C3C3C3;
	padding:20px;
	display:none;
	margin-bottom: 10px;
}
#controlSpan{
	float: right;
	margin-top: -40px;
}
#errSpan{
	color:red;
}
#noteSpan{
	font-size: 9px;
}
#noteSpan2{
	font-style: italic;
}
</style>
<div id="infoContainer" style="position:relative;width:780px;float:left;padding-left:10px;padding-top:32px;">
	<h2>Applications</h2>
	<span id="controlSpan"><a href="#" id="newapplink" onClick="showaddpanel();return false;">Add</a> | <a href="#" id="removeapplink" onClick="showremovepanel();return false;">Remove</a></span>
	<div id="addpanel">Application Name: <input type="text" id="txtappname"/> <input type="submit" id="btnrem" value="Add" onClick="addapplication();return false;"><br/><span id="noteSpan">Max. 20 characters</span><br/><span id="errSpan"></span></div>
	<div id="removepanel">
	<span id="noteSpan2">Warning: this action can't be reversed.</span> <br/>
	Application Key: <input type="text" id="txtappkey"/> <input type="submit" id="btnrem" value="Remove" onClick="removeapplication();return false;"><br/>
	<span style="font-size: 14px;"><input type="radio" name="rb_remove" value="0" checked>Application Only
	<input type="radio" name="rb_remove" value="1">Application With Data</span>
	<br/><span id="errSpan2"></span></div>
<?php	
	$sqlGetApps = "SELECT appName, appKey, appID, createStamp FROM application ORDER BY appID;";
	$paramArr = null;
	$results = executePreparedSelect($sqlGetApps, null);
	
	$tableOut = "<table class='gradienttable' style='width: 780px;'><tr><th>#</th><th>Name</th><th>Created On</th><th>Application Key</th></tr>";
	$count = 1;
	foreach($results as $row)
	{
		$tableOut .= "<tr><td>" . $count . "</td><td>" . $row["appName"] . "</td><td>" . date("m-d-Y", strtotime($row["createStamp"])) . "</td><td>" . $row["appKey"] . "</td></tr>";
		$count += 1;
	}
	$tableOut .= "</table>";

	echo $tableOut;
?>
</div>
<script type="text/javascript">
	var isAddPanelShown = false;
	var isRemovePanelShown = false;
	function showaddpanel(){
		if(isAddPanelShown){
			$("#addpanel").slideUp("slow");
			isAddPanelShown = false;
			document.getElementById("newapplink").innerHTML = "Add";
		}else{
			$("#addpanel").slideDown("slow");
			isAddPanelShown = true;
			document.getElementById("newapplink").innerHTML = "Hide";
		}
	}
	function showremovepanel(){
		if(isRemovePanelShown){
			$("#removepanel").slideUp("slow");
			isRemovePanelShown = false;
			document.getElementById("removeapplink").innerHTML = "Remove";
		}else{
			$("#removepanel").slideDown("slow");
			isRemovePanelShown = true;
			document.getElementById("removeapplink").innerHTML = "Hide";
		}
	}
	function addapplication(){
		var errSpan = document.getElementById("errSpan");
		var appName = document.getElementById("txtappname").value;
		errSpan.innerHTML = "";
		if(appName.length < 1 || appName.length > 20){
			errSpan.innerHTML = "Invalid name.";	
		}else{
			sendAjaxGetRequest("ajaxManageAppsHelper.php?action=addapp&appname=" + appName, ajaxReload);
		}
	}
	function removeapplication(){
		var errSpan2 = document.getElementById("errSpan2");
		var appKey = document.getElementById("txtappkey").value;
		errSpan.innerHTML = "";
		if(appKey.length != 32){
			errSpan2.innerHTML = "Invalid applicaiton key.";	
		}else{
			var removeData = $('input[name=rb_remove]:checked').val();
			sendAjaxGetRequest("ajaxManageAppsHelper.php?action=removeapp&appkey=" + appKey + "&removedate=" + removeData, ajaxReload2);
		}
	}
	function ajaxReload(response){
		if(response != "success"){
			document.getElementById("errSpan").innerHTML = "Something went wrong... Try again.";
		}else{
			window.location.reload();
		}
	}
	function ajaxReload2(response){
		if(response != "success"){
			document.getElementById("errSpan2").innerHTML = "Something went wrong... Try again.";
		}else{
			window.location.reload();
		}
	}
</script>
<?php
require_once("includes/mainGraph.php");
?>