<!--
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
-->
		<div id="main" style="min-height:1000px;width:1160px;margin-left:auto;margin-right:auto;background: url(http://www.zetta7.com/images/bg.jpg) no-repeat;">
			<div style="float:left;margin-left:75px;">
				<a href="index.php">
					<img src="http://www.zetta7.com/images/logo.png" border="0" style="margin-top:20px;">
				</a>
			</div>
			<div class="nav" style='margin-left:368px;float:left;margin-top:16px;'>
				<h1>
					AMS Dashboard
				</h1>
			</div>
			<div style="clear:left;float:left;background-color:#EFEFEF;border:1px solid #CCC;padding:10px;width:185px;margin-top:43px;margin-left:73px;">
				<?php
				/************* MULTIPLE APPLICATION DROP DOWN *************/
				$sqlGetMultipleApps = "SELECT appKey, appName
										 FROM application 
										 ORDER BY CASE WHEN obsolete = 0 THEN 0 ELSE 1 END";
				
				$results = executePreparedSelect($sqlGetMultipleApps, null);

				if(count($results) > 1)
				{ //we have more than 1 application
					$selDropDown = "<div><select id='selApplications' onChange='changeApplication(this.value);'><option value='0'>-- Select Application --</option>";
					foreach($results as $row)
					{
						$selDropDown .= "<option value='" . $row["appKey"] . "'>" . $row["appName"] . "</option>";
					}
					$selDropDown .= "</select></div>";
					echo $selDropDown;
				}

				?>
				<ul id="mymenu" class="filetree">
					<li class="open">
						<span>Audience Graph</span>
						<ul>
							<li>
								<a href="androidversion.php">
									Android version
								</a>
							</li>
							<li>
								<a href="phonemodel.php">
									Phone Model
								</a>
							</li>
						</ul>
					</li>
					<li class="open">
						<span>
							Events
						</span>
						<ul>
							<li>
								<a href="eventgeneral.php">
									General
								</a>
							</li>
							<li>
								<a href="eventsuccess.php">
									Success
								</a>
							</li>
							<li>
								<a href="eventerror.php">
									Error
								</a>
							</li>
							<li>
								<a href="eventwarning.php"	>
									Warning
								</a>
							</li>
							<li>
								<a href="eventinfo.php">
									Info
								</a>
							</li>
						</ul>
					</li>
					<li class="open">
						<span>Usage Data</span>
						<ul>
							<li>
								<a href="userpath.php">
									User Path
								</a>
							</li>
							<li>
								<a href="timeofuse.php">
									Time of Use
								</a>
							</li>
							<li>
								<a href="userretention.php">
									User Retention
								</a>
							</li>
							<li>
								<a href="totalusers.php">
									Total Users
								</a>
							</li>	
						</ul>
					</li>
				<ul>
			</div>
			<script type="text/javascript">
				$(document).ready(function(){
					$("#mymenu").treeview({
				  		animated: "fast",
						collapsed: true,
						control: "#treecontrol",
						unique: true,
				 	});
				});
			</script>
			