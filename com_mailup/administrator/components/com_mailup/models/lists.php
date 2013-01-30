<?php
// No direct access to this file
defined('_JEXEC') or die('Restricted access');
// import the Joomla modellist library
jimport('joomla.application.component.modellist');
/**
 * MailUPList Model
 */
class MailUPModelLists extends JModelList
{
	/**
	 * Method to build an SQL query to load the list data.
	 *
	 * @return	string	An SQL query
	 */
	protected function getListQuery()
	{
		// Create a new query object.		
		$db = JFactory::getDBO();
		$query = $db->getQuery(true);
		// Select some fields
		$query->select('*');
		// From the MailUP table
		$query->from('#__mailup_list');
		$query->order('listid');
		return $query;
	}
	
	
	public function deleteLists($idList){
			
			$db = JFactory::getDBO();
			$query = "DELETE FROM #__mailup_list WHERE id =".$idList;
			$db->setQuery($query);
			$db->query();

	}

	
	
	
	
	public function xmltostring($xmlLists){
		
		$xmlString = html_entity_decode($xmlLists);
                
        $startLists = strpos($xmlString, '<Lists>');
        $endPos = strpos($xmlString, '</Lists>');
        $endLists = $endPos + strlen('</Lists>') - $startLists;

        $xmlLists = substr($xmlString, $startLists, $endLists);

        $xmlLists = str_replace("&", "&amp;", $xmlLists);

        $xml = simplexml_load_string($xmlLists);
        
        return $xml;
	}
	
	public function selectGroups($idGroup){
		$db = JFactory::getDBO();
		$query = "SELECT COUNT(*) FROM #__mailup_group WHERE groupid = ".$idGroup;
		$db->setQuery($query);
		$result_group = $db->loadResult();
		
		return $result_group;
	}
	
	
	public function selectLists($idList){
		$db = JFactory::getDBO();
		$query = "SELECT COUNT(*) FROM #__mailup_list WHERE listid = ".$idList;
		$db->setQuery($query);
		$result_list = $db->loadResult();
		
		return $result_list;
	}
	
	
	public function insertGroup($groupName, $value, $idGroup){
		
		$db = JFactory::getDBO();
		$query = "INSERT INTO #__mailup_group (`id`, `name`, `alias`, `listid`, `groupid`, `create_date`, `update_date`) ";
		$query .= "VALUES (NULL, '".trim($groupName)."', '".trim($groupName)."', ".$value." , '".$idGroup."', '".date('Y-m-d')."', '".date('Y-m-d')."')";
		$db->setQuery($query);
		$db->query();
	}

	
	public function updateGroup($groupName, $value, $idGroup){
		$db = JFactory::getDBO();
		$query = "UPDATE #__mailup_group SET `name` = '".trim($groupName)."', `listid` = '".$value."', `update_date` = '".date('Y-m-d')."' WHERE groupid = ".$idGroup;
		$db->setQuery($query);
		$db->query();
	}
	
	
	public function insertList($label, $value, $guid){
		$db = JFactory::getDBO();
		$query = "INSERT INTO #__mailup_list (`id`, `name`, `alias`, `listid`, `guid`, `create_date`, `update_date`) ";
		$query .= "VALUES (NULL, '".trim($label)."', '".trim($label)."', ".$value." , '".$guid."', '".date('Y-m-d')."', '".date('Y-m-d')."')";
		$db->setQuery($query);
		$db->query();	
	}
	
	public function updateList($label, $value){
		$db = JFactory::getDBO();
		$query = "UPDATE #__mailup_list SET `name` = '".trim($label)."',  `update_date` = '".date('Y-m-d')."' WHERE listid = ".$value;
		$db->setQuery($query);
		$db->query();
	}

	
	public function deleteListsGroups($lists_to_delete, $groups_to_delete){
	
		// delete gruppi eliminati su mailup
		if(!empty($groups_to_delete)) {
			$db = JFactory::getDBO();
			$query = "DELETE FROM `#__mailup_group` WHERE `#__mailup_group`.`groupid` NOT IN (".$groups_to_delete.")";
			$db->setQuery($query);
			$db->query();
		}
		
		// delete liste eliminati su mailup
		if(!empty($lists_to_delete)) {
			$db = JFactory::getDBO();
			$query = "DELETE FROM `#__mailup_list` WHERE `#__mailup_list`.`listid` NOT IN (".$lists_to_delete.")";
			$db->setQuery($query);
			$db->query();
		}
	}
	
	
	
}
