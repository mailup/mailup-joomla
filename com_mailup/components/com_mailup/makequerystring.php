<?php
defined('_JEXEC') or die('Restricted access');
//echo 'ok';

//echo 

require_once(dirname(__FILE__).'/rc4.php');

//$crypt = new Crypt_RC4();
//$rc4 = new rc4crypt();

//echo 'ok2';

//$data = "m=12&f=3&type=subscribe&email=test1@nweb.it&idl=3&ts=20120601120324";


$data = "EventDate=20120601120324&IdConsole=a6457&IdList=1&Groups=3,6&EventType=SUBSCRIBE&Email=bonetto_andrea@libero.it";


echo $data;
echo "<br><br>";


$pwd = 'provaprova';
$data = rc4crypt::encrypt($pwd, $data);

$data = base64_encode($data);

echo $data;



//echo rc4crypt::encrypt($pwd, $data);
