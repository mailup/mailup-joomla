<?php
defined('_JEXEC') or die('Restricted access');

function getUserMailupId($email) {
	static $instances;
	
	if(!isset($instances)){
		$instances = array();
	}
	
	if(!isset($instances[$email])){
		$db = JFactory::getDBO();
		$query = "SELECT id FROM #__mailup_subscriber WHERE email LIKE {$db->quote($email)}";
		$db->setQuery($query);
		$db->query();
		$instances[$email] = $db->loadResult();
	}

	return $instances[$email];
}

function checkLasteUpdateTime($userId, $listId, $date){
	$db = JFactory::getDBO();
	$query = "
		SELECT 
			IFNULL(ls.last_update<$date, 1) 
		FROM 
			#__mailup_listsub ls
		WHERE
			ls.subid={$userId}
		AND 
			ls.listid={$listId}
		"; 
	$db->setQuery($query);
	return $db->loadResult();
}

function setLastUpdateTime($userId, $listId, $date){
	$db = JFactory::getDBO();
	$query = "UPDATE #__mailup_listsub SET last_update={$date} WHERE subid={$userId} AND listid={$listId}"; 
	$db->setQuery($query);
	$db->query();
}

function addUserMailup($email, $Fieldnn, $eventdate) {
	$db = JFactory::getDBO();
	$eventdate = date('Y-m-d', $eventdate);

	$searchJUserQuery = "SELECT id FROM #__users WHERE email LIKE {$db->quote($email)}";
	$db->setQuery($searchJUserQuery);
	$jUserId = $db->loadResult();
	
	if($jUserId){
		$values = "(NULL, {$db->quote($Fieldnn)}, {$db->quote($email)}, {$jUserId}, {$db->quote(strtoupper(md5(rand(1000,999999))))}, 1, {$db->quote($eventdate)})";
	}else{
		$values = "(NULL, {$db->quote($Fieldnn)}, {$db->quote($email)}, 0, {$db->quote(strtoupper(md5(rand(1000,999999))))}, 0, {$db->quote($eventdate)})";
	}
	
	$query = "INSERT INTO #__mailup_subscriber (`id`, `name`, `email`, `userid`, `code`, `joomla_user`, `create_date`) VALUES $values;";
	$db->setQuery($query);
	$db->query();
	$res = $db->insertid();

	return $res;
}




function unsubscribeUser($userid, $listid, $groups, $eventdate) {
	
	//$eventdate = date('Y-m-d', strtotime($eventdate));
	
	$db = JFactory::getDBO();
	$query = "UPDATE #__mailup_listsub SET enabled = '0', status = '3', unsubdate = NOW() WHERE subid = ".$userid." AND listid =".$listid;
	$db->setQuery($query);
	$db->query();
	

	$query = "UPDATE #__mailup_groupsub SET enabled = '0', status = '3', unsubdate = NOW() WHERE subid = ".$userid." AND groupid IN (".$groups.")";
	$db->setQuery($query);
	$db->query();
	
	return 0;
}



function subscribeUser($userid, $listid, $groups, $eventdate) {
	
	//$eventdate = date('Y-m-d', strtotime($eventdate));
	$eventdate = date('Y-m-d', $eventdate);
	
	$db = JFactory::getDBO();
	$query = "REPLACE INTO #__mailup_listsub (`listid`, `subid`, `subdate`, `enabled`, `status`) VALUES ('".$listid."', '".$userid."', '$eventdate', '1', '3')";
	$db->setQuery($query);
	$db->query();
	
	$sub_group = explode(",", $groups);
	if($sub_group) {
		foreach ($sub_group as $key=>$group) {
			if($group){
				$query = "REPLACE INTO #__mailup_groupsub (`groupid`, `subid`, `subdate`, `enabled`, `status`) VALUES ('".$group."', '".$userid."', '$eventdate', '1', '3')";
				$db->setQuery($query);
				$db->query();
			}
		}
	}

	return 0;
	
}

function checkUserDelete($userId){
	$db =& JFactory::getDBO();
	$search = "SELECT COUNT(*) FROM #__mailup_listsub WHERE subid={$userId} AND enabled=1";
	$db->setQuery($search);
	$count = $db->loadResult();
	
	if($count == 0){
		$deleteQuery = "
			DELETE
				s.*,
				gs.*
			FROM
				#__mailup_subscriber s
			LEFT JOIN
				#__mailup_groupsub gs
			ON
				gs.subid=s.id
			WHERE
				s.id={$userId}
		";
		$db->setQuery($deleteQuery);
		$db->query();
	}
}

function deleteUserReferences($userid, $listId){
	$db =& JFactory::getDBO();
	
	$deleteListQuery = "
		UPDATE
			#__mailup_listsub ls
		SET
			ls.enabled=0
		WHERE
			ls.subid={$userid}
		AND
			ls.listid={$listId}
	";
	
	$db->setQuery($deleteListQuery);
	$db->query();
	
	$deleteGroupQuery = "
		UPDATE
			#__mailup_groupsub gs
		INNER JOIN
			#__mailup_group g
		ON
			g.groupid=gs.groupid
		SET
			gs.enabled=0
		WHERE
			gs.subid={$userid}
		AND
			g.listid={$listId}
	";
	
	$db->setQuery($deleteGroupQuery);
	$db->query();
	
	return 0;
}



function queryToArray($qry) {
                
	$result = array();
                //string must contain at least one = and cannot be in first position
                if(strpos($qry,'=')) {

                 if(strpos($qry,'?')!==false) {
                   $q = parse_url($qry);
                   $qry = $q['query'];
                  }
                }else {
                        return false;
                }

                foreach (explode('&', $qry) as $couple) {
                        list ($key, $val) = explode('=', $couple);
                        $result[$key] = $val;
                }

                return empty($result) ? false : $result;

        }