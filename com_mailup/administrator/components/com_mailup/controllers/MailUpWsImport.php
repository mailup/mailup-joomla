<?php

defined('_JEXEC') or die('Restricted access');

jimport( 'joomla.html.parameter' );
    



class MailUpWsImport {
	
	private $soapClient;
    private $xmlResponse;
    protected $domResult;
    protected $namespace = "http://ws.mailupnet.it/";
    
    
	function __construct() {
        
        $componentParams = &JComponentHelper::getParams('com_mailup');
		
		$url = $componentParams->get('WSDLUrl_import', 'defaultValue');
		$user = $componentParams->get('user', 'defaultValue');
	    $password = $componentParams->get('pwd', 'defaultValue');
	    $nameUnique = $componentParams->get('nameUnique', 'defaultValue');
                
        $WSDLUrl = 'http://'.$url.'/Services/WSMailUpImport.asmx?WSDL';

        $headers = array('User' => $user, 'Password' => $password);
        $this->header = new SOAPHeader($this->namespace, 'Authentication', $headers);
        $this->soapClient = new SoapClient($WSDLUrl, array('trace' => 1, 'exceptions' => 0));
        $this->soapClient->__setSoapHeaders($this->header);
	}
     
    function __destruct() {
        unset($this->soapClient);
    }
     
    public function getFunctions() {
        print_r($this->soapClient->__getFunctions());
    }
     

    
    
    
////////////////////////// WS IMPORT FUNTION /////////////////////////////////

	public function GetNlLists() {
		try {
			$this->soapClient->GetNlLists();
			//$this->printLastRequest();
			//$this->printLastResponse();
			//die();
			if ($this->readReturnCode("GetNlLists","errorCode") != 0) 
			echo "<br /><br />Errore GetNlLists: ". $this->readReturnCode("GetNlLists","errorDescription");
			return $this->printLastResponse();
			
		} catch (SoapFault $soapFault) {	
				var_dump($soapFault);
		}
	
	//return $this->readReturnCode("GetLists", "lists");
	}    
    
    
    
    public function GetIdWsUser($user_console) {
		try {
			$this->soapClient->GetIdWsUser($user_console);
//			$this->printLastRequest();
//			echo $this->printLastResponse();
//			die();
			if ($this->readReturnCode("GetIdWsUser","errorCode") != 0) 
			echo "<br /><br />Errore GetIdWsUser: ". $this->readReturnCode("GetIdWsUser","errorDescription");
			return $this->printLastResponse();
			
		} catch (SoapFault $soapFault) {	
				var_dump($soapFault);
		}
	
	//return $this->readReturnCode("GetIdWsUser", "lists");
	}  
    
    
     public function GetNlListsUser($id_user_console) {
		try {
			$this->soapClient->GetNlListsUser($id_user_console);
//			$this->printLastRequest();
//			echo $this->printLastResponse();
//			die();
			if ($this->readReturnCode("GetNlListsUser","errorCode") != 0) 
			echo "<br /><br />Errore GetNlListsUser: ". $this->readReturnCode("GetNlListsUser","errorDescription");
			return $this->printLastResponse();
			
		} catch (SoapFault $soapFault) {	
				var_dump($soapFault);
		}
	
	//return $this->readReturnCode("GetNlListsUser", "lists");
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
//	  			$out[$i][$v] = $rCode->item($i)->getElementsByTagName($v)->item(0)->nodeValue;
	  		}
	  		$out[] = $obj;
//  			var_dump($rCode->item($i)->getElementsByTagName('listID')->item(0)->nodeValue);
//	  		var_dump($rCode->item($i)->getElementsByTagName('listName')->item(0)->nodeValue);
  		}
  		return $out;

	}
	
	
	
	
	
	
	
	
	public function getURLContent($url=null) {	
	// Crea la risorsa CURL
	    $ch = curl_init();
	 
	    // Imposta l'URL e altre opzioni
	    curl_setopt($ch, CURLOPT_URL, $url);
	    curl_setopt($ch, CURLOPT_HEADER, 0);
	    curl_setopt($ch, CURLOPT_RETURNTRANSFER,true);
	    // Scarica l'URL e lo passa al browser
	    $output = curl_exec($ch);
	    $info = curl_getinfo($ch, CURLINFO_HTTP_CODE);
	    // Chiude la risorsa curl
	    curl_close($ch);
	    if ($output === false || $info != 200) {
	      $output = null; }
	      
	    return $output;
	}

	
	
	

	
///////////////////////// PLUGIN FUNCTION ////////////////////////////////////////	
	public function executeMethod($method, $returnCode) {
		
		if($method == 'xmlSubscribe') {
			if($returnCode == 1) {
				$val = 'errore generico';	
			}
			elseif($returnCode == 2) {
				$val = 'OK';
			}
			
		$db = JFactory::getDBO();
			$query = $db->getQuery(true);
			$query = 'UPDATE `#__mailup_subscriber` SET `lists` = "'.$val.'" WHERE `id` =1';
			$db->setQuery($query);
			$db->query();
		}
			
	}
	
	  
 } 