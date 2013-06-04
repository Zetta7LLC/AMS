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
<html>
	<head>
		<link rel="stylesheet" type="text/css" href="includes/jquery.treeview.css">
		<link rel="stylesheet" type="text/css" href="includes/styles.css" />		
		<link rel="stylesheet" type="text/css" href="jqplot/jquery.jqplot.css" />
		<title>
			AMS Dashboard
		</title>
	</head>
	<body>
		<script src="http://ajax.googleapis.com/ajax/libs/jquery/2.0.0/jquery.min.js"></script>
		<script type="text/javascript" src="jqplot/jquery.jqplot.js"></script>
		<script type="text/javascript" src="jqplot/plugins/jqplot.barRenderer.js"></script>
		<script type="text/javascript" src="jqplot/plugins/jqplot.categoryAxisRenderer.js"></script>
		<script type="text/javascript" src="jqplot/plugins/jqplot.pointLabels.js"></script>
		<script type="text/javascript" src="jqplot/plugins/jqplot.bubbleRenderer.min.js"></script>
		<script type="text/javascript" src="jqplot/plugins/jqplot.pieRenderer.min.js"></script>
		<script type="text/javascript" src="jqplot/plugins/jqplot.donutRenderer.min.js"></script>
		<script type="text/javascript" src="jqplot/plugins/jqplot.highlighter.min.js"></script>
		<script type="text/javascript" src="jqplot/plugins/jqplot.cursor.min.js"></script>
		<script type="text/javascript" src="jqplot/plugins/jqplot.dateAxisRenderer.min.js"></script>
		<script src="includes/jquery.treeview.min.js"></script>
		<script src="includes/amsGeneral.js"></script>