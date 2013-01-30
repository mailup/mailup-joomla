<?php
define( '_JEXEC', 1 );
define( 'JPATH_BASE', dirname(dirname(dirname(__FILE__))));
define( 'DS', DIRECTORY_SEPARATOR );

require_once JPATH_BASE .DS . 'configuration.php';

require_once JPATH_BASE .DS.'includes'.DS.'defines.php';
require_once JPATH_BASE .DS.'includes'.DS.'framework.php';

require_once JPATH_BASE .DS.'libraries'.DS.'joomla'.DS.'factory.php';
$mainframe =& JFactory::getApplication('site');

require_once dirname(__FILE__).'/rc4.php';
require_once dirname(__FILE__).'/mailup_method.php';

$params = JComponentHelper::getParams('com_mailup');
$pwd = $params->get('rc4_pwd');

$data = urldecode($_SERVER['QUERY_STRING']);

if(JDEBUG){
	jimport('joomla.log.log');
	JLog::addLogger(array('text_file'=>'mailup_webhooks.php'));
	$toLog = array();
	$toLog[] = $_SERVER['QUERY_STRING'];
	$toLog[] = $data;
}

//var_dump($data);

$data = base64_decode($data);
//var_dump($data);
//$pwd = 'j00mla';


//echo $data;
//echo "<br><br>";

//$queryString = rc4crypt::decrypt($pwd, $data);

$querystring_decode = rc4crypt::decrypt($pwd, $data);
if(JDEBUG){
	$toLog[] = utf8_encode($data);
	//$toLog[] = ($data);
	$toLog[] = $querystring_decode;
	
}
$data = queryToArray($querystring_decode);

//var_dump($querystring_decode);
if(JDEBUG){
	$toLog[] = json_encode($data);
}


$emailName = 'eml';
$eventDateName = 'evd';
$eventTypeName = 'evt';
$listIdName = 'idl';
$nlIdName = 'idn';
$groupsName = 'gru';

$data[$emailName] = urldecode($data[$emailName]);
$data[$groupsName] = urldecode($data[$groupsName]);
$data[$eventDateName] = strtotime(urldecode($data[$eventDateName]));

if(JDEBUG){
	$toLog[] = json_encode($data);
}

$date = $data[$eventDateName];
$email = $data[$emailName];

$userid = getUserMailupId($data[$emailName]);
$shouldUpdate = true;

if(!isset($data[$listIdName])){
	$shouldUpdate = false;
}elseif($userid){
	$shouldUpdate = checkLasteUpdateTime($userid, $data[$listIdName], $date);
}


if(JDEBUG){
	$toLog[] = $data[$eventTypeName];
	if(!$shouldUpdate){
		$toLog[] = 'out of date / error';
	}
}


if($shouldUpdate){
	switch($data[$eventTypeName]){
		case 'UNSUBSCRIBE':
			//$userid = getUserMailupId($data[$emailName]);
			$res = unsubscribeUser($userid, $data[$listIdName], $data[$groupsName], $data[$eventDateName]);
		
			if(JDEBUG){
				//$toLog[] = 'UNSUBSCRIBE';
				$toLog[] = $res;
			}
		break;
		case 'SUBSCRIBE':
			//$userid = getUserMailupId($data[$emailName]);
		
			if(!$userid) {
				$userid = addUserMailup($data[$emailName], $data[$emailName], $data[$eventDateName]);
			}
		
			$res = subscribeUser($userid, $data[$listIdName], $data[$groupsName], $data[$eventDateName]);
		
			if(JDEBUG){
				$toLog[] = $res;
			}
		break;
		case 'CHANGEPROFILE':
			//$userid = getUserMailupId($data[$emailName]);
		
			if(!$userid) {
				$userid = addUserMailup($data[$emailName], $data[$emailName], $data[$eventDateName]);
			}
			
			deleteUserReferences($userid, $data[$listIdName]);
			$res = subscribeUser($userid, $data[$listIdName], $data[$groupsName], $data[$eventDateName]);
			
			if(JDEBUG){
				$toLog[] = $res;
			}
		break;
		case 'DELETE': 
			//$userid = getUserMailupId($data[$emailName]);
			
			if(!$userid) {
				if(JDEBUG){
					$toLog[] = 'user not found';
				}
				break;
			}
			
			$res = deleteUserReferences($userid, $data[$listIdName]);
			checkUserDelete($userId);
			
			if(JDEBUG){
				$toLog[] = $res;
			}
		break;
	}
	
	setLastUpdateTime($userid, $data[$listIdName], $date);
}else{
	$res = 1;
}

echo $res;

if(JDEBUG){
	foreach($toLog as $v){
		JLog::add($v);
	}
}

$mainframe->close();








