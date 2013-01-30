<?php

defined('_JEXEC') or die('Restricted access');

jimport( 'joomla.html.parameter' );
    



class MailUpWsSend {
	
	private $soapClient;
    private $xmlResponse;
    protected $domResult;
    protected $default_ns = 'wsvc.ss.mailup.it';
    
    function __construct() {
		
    	// Prelevo URL WSDLUrl
//    	$plugin = &JPluginHelper::getPlugin('user', 'mailupsubscriber');
//		$pluginParams = new JParameter($plugin->params);
//		$WSDLUrl = 'https://'.$pluginParams->get('WSDLUrl_send', $default_ns).'/MailupSend.asmx?WSDL';
		
		$componentParams = &JComponentHelper::getParams('com_mailup');
		
		$url = $componentParams->get('WSDLUrl_send', $default_ns);
		$WSDLUrl = 'https://'.$url.'/MailupSend.asmx?WSDL';

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
                die();
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
    

    
//////////////////////////////////////////////////////////////////////////////////////////////////////////
   
	public function getLists() {
		try {
			$this->soapClient->GetLists(array("accessKey" => $this->accessKey));
			if ($this->readReturnCode("GetLists","errorCode") != 0) 
				echo "<br /><br />Errore GetLists: ". $this->readReturnCode("GetLists","errorDescription");
			return $this->printLastResponse();
			
		} catch (SoapFault $soapFault) {	
				//var_dump($soapFault);
		}
	//return $this->readReturnCode("GetLists", "lists");
	}
	
	
	
		public function CreateList($listName, $nameUnique) {
		try {
			$this->soapClient->CreateList(array("accessKey" => $this->accessKey, "name" => $listName, "autoId" => "" ));
			//$this->printLastRequest();
			//$this->printLastResponse();
			if ($this->readReturnCode("CreateList","errorCode") != 0) 
			echo "<br /><br />Errore CreateList: ". $this->readReturnCode("CreateList","errorDescription");
			return $this->printLastResponse();
			
		} catch (SoapFault $soapFault) {	
				var_dump($soapFault);
		}
	
	//return $this->readReturnCode("GetLists", "lists");
	}
	
	
		public function DeleteList($listId, $nameUnique) {
		try {
			$this->soapClient->DeleteList(array("accessKey" => $this->accessKey, "listId" => $listId, "autoId" => "" ));
			//$this->printLastRequest();
			//echo $this->printLastResponse();
			//die();
			if ($this->readReturnCode("DeleteList","errorCode") != 0) 
			echo "<br /><br />Errore DeleteList: ". $this->readReturnCode("DeleteList","errorDescription");
			return $this->printLastResponse();
			
		} catch (SoapFault $soapFault) {	
				var_dump($soapFault);
		}
	
	//return $this->readReturnCode("GetLists", "lists");
	}    
	
	

////////////////////////////////////////////////////////////////////////////////////////////////////////////	

 
    
    
   
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
	

	
	function xmlstr_to_array($xmlstr, $params) {
  		$doc = new DOMDocument();
  		$doc->loadXML($xmlstr) or die("File XML non valido!");
  		
  		$xmlResult = $doc->getElementsByTagName("GetListsResult");
		//echo "XmlReuslt:" . $xmlResult;
		$domResult = new DomDocument();
		$domResult->LoadXML(html_entity_decode($xmlResult->item(0)->nodeValue)) or die("File XML1 non valido!");
  		$rCode = $domResult->getElementsByTagName("list");
  		for($i=0; $i<$rCode->length; $i++){
  			$obj = new stdClass();
//          $out[] = array();
	  		foreach($params as $k=>$v){
	  			$obj->$v = $rCode->item($i)->getElementsByTagName($v)->item(0)->nodeValue;

	  		}
	  		$out[] = $obj;

  		}
  		return $out;

	}
	
  
 } 