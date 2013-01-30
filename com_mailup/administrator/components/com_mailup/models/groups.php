<?php
// No direct access to this file
defined('_JEXEC') or die('Restricted access');
// import the Joomla modellist library
jimport('joomla.application.component.modellist');
/**
 * MailUPList Model
 */
class MailUPModelGroups extends JModelList
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
		$query->select('groups.id AS id, groups.name AS name, groups.description AS description, groups.alias AS alias, groups.groupid AS groupid, lists.name AS listname, groups.visible AS visible, groups.create_date AS create_date, groups.update_date AS update_date');
		$query->from('#__mailup_group AS groups');
		$query->join('LEFT', '#__mailup_list AS lists ON lists.listid = groups.listid');
		// From the MailUP table
		$query->order('groups.listid');
		return $query;
	}
	
	
	
	
	public function deleteGroups($idGroup){
			
			$db = JFactory::getDBO();
			$query = "DELETE FROM #__mailup_group WHERE id =".$idGroup;
			$db->setQuery($query);
			$db->query();		

	}
	
}
