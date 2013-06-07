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
	require_once '../dashboard/includes/amslib.php';
	
	class AMSserver{
		private $AppKey;
		private $ArrActivity;
		private $DeviceId;
		private $DeviceSDK;
		private $DeviceModel;
		private $Platform;

		
		public function __construct() {
			$this->AppKey = "";
			$this->ArrActivity = array();
		}
		
		public function proccessRequest(){
			$xmlRequest = file_get_contents('php://input');
			
			if($this->parseXMLRequest($xmlRequest)){
				$db = connectToDB();
				if($db != null){
					//save xml request
					$saveXml = "INSERT INTO rawXMLRequest (rawXML) VALUES ('" . $xmlRequest . "');";
					$db->exec($saveXml);
					$requestId = $db->lastInsertId();
					//save activities
					$insertQuery = "";
					foreach($this->ArrActivity as $curActivity){
						$insertQuery .= "INSERT INTO activityLog (requestId, appKey, typeId, deviceId, deviceSDK, deviceModel, platform, message, description, notes) VALUES " 
										. "(" . $requestId . ",'" . $this->AppKey . "', " . $curActivity->TypeId . ", '" . $this->DeviceId . "', '" . $this->DeviceSDK . "','" . $this->DeviceModel . "','" . $this->Platform . "','" . $curActivity->Message . "', '" . $curActivity->Description . "', '" . $curActivity->Notes . "');";
					}
					$db->exec($insertQuery);
					$db = null;
					$this->sendResponse(200);
				}else{
					$this->sendResponse(500);
				}
			}else{
				$this->sendResponse(400);
			}
		}
		
		private function parseXMLRequest($xmlData){
			try{
				$AMSRequest = new SimpleXmlElement($xmlData);
				if($this->parseAppKey($AMSRequest->AppKey)){
					$this->DeviceId = $AMSRequest->DeviceId;
					$this->DeviceSDK = $AMSRequest->DeviceSDK;
					$this->Platform = $AMSRequest->Platform;
					$this->DeviceModel = $AMSRequest->DeviceModel;
					
					//loop over each activity
					foreach($AMSRequest->AppActivity as $curActivity){
						$myActivity = new AppActivity();
						$myActivity->TypeId = intval($curActivity->TypeId);
						$myActivity->Message = (string)$curActivity->Message;
						$myActivity->Description = (string)$curActivity->Description;
						$myActivity->Notes = (string)$curActivity->Notes;
						$this->ArrActivity[] = $myActivity;
					}
					return true;
				}
				return false;
			}catch(Exception $e){
				return false;
			}
		}
		//lenght should be 32 (a valid GUID)
		private function parseAppKey($input){
			if(strlen($input) == 32){
				$this->AppKey = $input;
				return true;
			}
			return false;
		}
		private function sendResponse($code){
			$msg = "200 - Success";
			if($code == 400){
				$msg = "400 - Invalid request";
			}else if($code == 500){
				$msg = "500 - Internal server error";
			}
			$responseMsg = "<?xml version=\"1.0\" encoding=\"UTF-8\"?>
							<AMSResponse>
								<Message>" . $msg . "</Message>
							</AMSResponse>";
			echo $responseMsg;
		}
	}
	
	class AppActivity{
		public $TypeId;
		public $Message;
		public $Description;
		public $Notes;
	}
?>
