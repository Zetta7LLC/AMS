************** READ ME File ******************

File Date 		: 06/04/2013
File version 	: 1.1
Software		: AMS
Software Version: Version 1.1

Description:
AMS (Application Monitoring Service) is the easiest way to log information about any Android application. This service has the ability to show information about the user or application behavior in a single, compact, and unified system. The service is split up into three sections: AMS Android library, AMS Webservice, and the AMS Dashboard. 


AMS Android Library:
This library is a simple import into the Android applciation and then it will communicate with the webservice. It has functions for error, info, success, and warning logging. Once a logging request is called (it's asynchronous so the user won't even notice it!), the device will send an XML to the webservice with the information specified where it will get logged.


AMS Webservice:
Once the webservice receives a uniquely structured XML file, it will parse it out and will save it into the database.

AMS Dashboard:
The dashboard acts completely separate from the library and the webservice and does not communicate with them at all. The goal is to display the data in the easiest way possible. Examples of the information shown: Android version pie chart, agrregated event information, user path table, total users graph, etc.

Note From Authors:
While we don't know of any issues at this time, this is code, if you were to encounter a bug (which we're going to assume is not our fault) please do not hesitate to email us at support@zetta7.com. We also take compliments and suggestions at this email.

System Requirements:
Web server
MySql Database (mariaDB is also supported)

Installation Notes:
Part 1 - MySQL Database
	Steps:
	1. Create a new database (ex: amsUser): should have insert and select permissions available
	2. Locate the AMS/Scripts/CreateTables.sql
	3. Import into your database: $>mysql -u {dbuser} -p -h {dbserver} {table} < CreateTables.sql

Part 2 - AMS Dashboard
	Steps:
	1. Download AMS/dashboard folder
	2. Move it to a target location
	3. Open AMS/dashboard/includes/amslib.php and populate the following variables
		a. $myDBServer 		- Ex: localhost
		b. $myDBUserName 	- Ex: root_user
		c. $myDBPassword 	- Ex: root_pswd
		d. $myDBName 		- Ex: amsUser

Part 3 - AMS Web Service
	Steps:
	1. Download AMS/webservice folder if you don't already have it
	2. Move it to the same location where the dashboard folder exists

Part 4 - AMS Android Library
	Steps:
	1. Download AMS/andrlidlib/AMSLibarary.java
	2. Include it in your Anroid application.
	3. The AMSLibrary constructer takes string clientId and string endPoint. The clientId is the GUID that is returned when you create a new application in the AMS Dashboard. The endPoint is the URL to the AMS webservice. 
	Note: see AMS/documentation/amsandroidlibrary.html for details on how to use the library.

Additional Information:
For more information about AMS project such as documentation and screenshots please refer to the AMS/documentation folder.

New Features: <br/>
	Event Veiwer <br/>
	Export logs to CVS <br/>
	App Manager: Add / remove applications <br/>
	

Bug Fixes:
n/a

Known Issues:
n/a
