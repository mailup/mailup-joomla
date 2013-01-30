<?php

defined('_JEXEC') or die('Restricted access');

jimport( 'joomla.html.parameter' );
    



class MailUpWsManage {
	
	
	private $soapClient;
    private $xmlResponse;
    protected $domResult;
    protected $default_ns = 'wsvc.ss.mailup.it';
    
    function __construct() {
		
		$componentParams = &JComponentHelper::getParams('com_mailup');
		
		$url = $componentParams->get('WDSLUrl_manage', 'defaultValue');
		$WSDLUrl = 'https://'.$url.'/MailupManage.asmx?WSDL';
		
		
		
		$this->soapClient = new SoapClient($WSDLUrl, array("trace" => 1, "exceptions" => 0));
    }
     
    function __destruct() {
        unset($this->soapClient);
    }
     
    public function getFunctions() {
        print_r($this->soapClient->__getFunctions());
    }
     

    
    public function loginFromId($user_console, $pwd_console, $id_console) {
    	try {
            $loginData = array("user" => $user_console,
                               "pwd" => $pwd_console,
                               "consoleId" => $id_console);
                 
            $this->soapClient->loginFromId($loginData);
            //$this->printLastResponse();
            if ($this->readReturnCode("LoginFromId","errorCode") != 0) {
                //echo "<br /><br />Error in LoginFromId: ". $this->readReturnCode("LoginFromId","errorDescription");
                //die();
            }
            else $this->accessKey = $this->readReturnCode("LoginFromId","accessKey");
         //echo "<br>AccesKey: ". $this->accessKey;
            } catch (SoapFault $soapFault) {  
            	var_dump($soapFault);
            }
            
            //return $this->accessKey;
    }
    

    
    public function logout() {
        try {
            $this->soapClient->Logout(array("accessKey" => $this->accessKey));
            if ($this->readReturnCode("Logout","errorCode") != 0)
                echo "<br /><br />Error in Logout". $this->readReturnCode("Logout","errorDescription");
            } catch (SoapFault $soapFault) {   
      	}
    }
    
	
	
    
    
    
   	public function CreateGroup($listID, $groupName, $groupNotes) {
		try {
			$this->soapClient->CreateGroup(array("accessKey" => $this->accessKey, "listID" => $listID, "groupName" => $groupName, "groupNotes" => $groupNotes ));
//			$this->printLastRequest();
//			echo "<br><br><br>";
//			echo $this->printLastResponse();
//			die();
			if ($this->readReturnCode("CreateGroup","errorCode") != 0) 
			echo "<br /><br />Errore CreateGroup: ". $this->readReturnCode("CreateGroup","errorDescription");
			return $this->printLastResponse();
			
		} catch (SoapFault $soapFault) {	
				var_dump($soapFault);
		}
		
		//return $this->readReturnCode("CreateGroup", "lists");
	}  
    
    
    
	
	public function DeleteGroup($groupID, $listID, $deleteUsers = 0) {		
		try {
			$this->soapClient->DeleteGroup(array("accessKey" => $this->accessKey, "listID" => $listID, "groupID" => $groupID, "deleteUsers" => $deleteUsers ));
//			$this->printLastRequest();
//			echo $this->printLastResponse();
//			die();
			if ($this->readReturnCode("DeleteGroup","errorCode") != 0) 
			echo "<br /><br />Errore DeleteGroup: ". $this->readReturnCode("DeleteGroup","errorDescription");
			return $this->printLastResponse();
			
		} catch (SoapFault $soapFault) {	
				var_dump($soapFault);
		}
		
		//return $this->readReturnCode("DeleteGroup", "lists");
	}  	
	

	public function UpdateGroup($groupID, $option) {		
		try {
			$this->soapClient->UpdateGroup(array("accessKey" => $this->accessKey, "groupID" => $groupID, "deleteUsers" => $deleteUsers ));
//			$this->printLastRequest();
//			echo $this->printLastResponse();
//			die();
			if ($this->readReturnCode("UpdateGroup","errorCode") != 0) 
			echo "<br /><br />Errore UpdateGroup: ". $this->readReturnCode("UpdateGroup","errorDescription");
			return $this->printLastResponse();
			
		} catch (SoapFault $soapFault) {	
				var_dump($soapFault);
		}
		
		//return $this->readReturnCode("UpdateGroup", "lists");
	}  	
	
	
	
	
    
////////////////////////// GLOBAL ////////////////////////////////////////////    
    
 	private function readReturnCode($func, $param) {
		/*static $func_in = ""; //static variable to test xmlResponse update
		if ($func_in != $func) {//(!isset($this->xmlResponse))
			$func_in = $func;*/
			//prendi l'XML di ritorno se non l'ho già preso
			$this->xmlResponse = $this->soapClient->__getLastResponse();
			$dom = new DomDocument();
			$dom->loadXML($this->xmlResponse) or die("File XML non valido!");
			$xmlResult = $dom->getElementsByTagName($func."Result");
			//echo "XmlReuslt:" . $xmlResult;
			$this->domResult = new DomDocument();
			$this->domResult->LoadXML(html_entity_decode($xmlResult->item(0)->nodeValue)) or die("File XML1 non valido!");
		//}
		$rCode = $this->domResult->getElementsByTagName($param);
		//var_dump($rCode);
		return $rCode->item(0)->nodeValue;
 	}

    
	private function printLastRequest() {
		echo "<br>Request :<br>". htmlentities($this->soapClient->__getLastRequest()). "<br>";
	}
	
	private function printLastResponse() {
		//echo "<br />XMLResponse: " . $this->soapClient->__getLastResponse() . "<br />"; //htmlentities();
		return $this->soapClient->__getLastResponse();
	}
	
	public function getAcessKey() {
		return $this->accessKey;
	}
	
	public function option($key, $value) {
		return array("Key" => $key, "Value" => $value);
	}
	
  
 } 