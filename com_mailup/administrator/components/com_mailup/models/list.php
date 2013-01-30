<?php
// No direct access to this file
defined('_JEXEC') or die('Restricted access');
 
// import Joomla modelform library
jimport('joomla.application.component.modeladmin');
 
/**
 * MailUP Model
 */
class MailUPModelList extends JModelAdmin
{
	/**
	 * Method override to check if you can edit an existing record.
	 *
	 * @param	array	$data	An array of input data.
	 * @param	string	$key	The name of the key for the primary key.
	 *
	 * @return	boolean
	 * @since	1.6
	 */
	protected function allowEdit($data = array(), $key = 'id')
	{
		// Check specific edit permission then general edit permission.
		return JFactory::getUser()->authorise('core.edit', 'com_mailup.message.'.((int) isset($data[$key]) ? $data[$key] : 0)) or parent::allowEdit($data, $key);
	}
	/**
	 * Returns a reference to the a Table object, always creating it.
	 *
	 * @param	type	The table type to instantiate
	 * @param	string	A prefix for the table class name. Optional.
	 * @param	array	Configuration array for model. Optional.
	 * @return	JTable	A database object
	 * @since	1.6
	 */
	public function getTable($type = 'List', $prefix = 'MailUPTable', $config = array()) 
	{
		return JTable::getInstance($type, $prefix, $config);
	}
	/**
	 * Method to get the record form.
	 *
	 * @param	array	$data		Data for the form.
	 * @param	boolean	$loadData	True if the form is to load its own data (default case), false if not.
	 * @return	mixed	A JForm object on success, false on failure
	 * @since	1.6
	 */
	public function getForm($data = array(), $loadData = true) 
	{
		// Get the form.
		$form = $this->loadForm('com_mailup.list', 'list', array('control' => 'jform', 'load_data' => $loadData));
		if (empty($form)) 
		{
			return false;
		}
		return $form;
	}
	/**
	 * Method to get the script that have to be included on the form
	 *
	 * @return string	Script files
	 */
	public function getScript() 
	{
		return 'administrator/components/com_mailup/models/forms/list.js';
	}
	/**
	 * Method to get the data that should be injected in the form.
	 *
	 * @return	mixed	The data for the form.
	 * @since	1.6
	 */
	protected function loadFormData() 
	{
		// Check the session for previously entered form data.
		$data = JFactory::getApplication()->getUserState('com_mailup.edit.list.data', array());
		if (empty($data)) 
		{
			$data = $this->getItem();
		}
		return $data;
	}
	
	
	
	public function xmltostring($xmlString) {
		
  			$xmlString = html_entity_decode($xmlLists);
                
            $startLists = strpos($xmlString, '<Lists>');
            $endPos = strpos($xmlString, '</Lists>');
            $endLists = $endPos + strlen('</Lists>') - $startLists;

            $xmlLists = substr($xmlString, $startLists, $endLists);

            $xmlLists = str_replace("&", "&amp;", $xmlLists);

            $xml = simplexml_load_string($xmlLists);
       
       		return $xml; 
	}
	
	

	public function unpdateList($id, $alias, $description, $visible){
			$db = JFactory::getDBO();
			$query = "UPDATE #__mailup_list SET `alias` = '".$alias."', `description` = '".$description."', `visible` = '".$visible."', `update_date` = '".date('Y-m-d')."' WHERE id = ".$id;
			$db->setQuery($query);
			$db->query();
	}	
	
	
	
	
	public function updateGroup($id){
			$db = JFactory::getDBO();
			$query = "UPDATE #__mailup_group SET `visible` = '0' WHERE listid = (SELECT listid FROM #__mailup_list WHERE id = ".$id.")";
			//die($query);
			$db->setQuery($query);
			$db->query();
	}
	
	
	public function insertList($name, $description, $listid, $guid, $alias){
			$db = JFactory::getDBO();
			$query = "INSERT INTO `#__mailup_list` (`id`, `name`, `description`, `listid`, `guid`, `alias`, `visible`, `create_date`, `update_date`) VALUES (NULL, '".$name."', '".$description."', '".$listId."', '".$guid."', '".$alias."', '1', '".date('Y-m-d')."', NULL)";
			$db->setQuery($query);
			$db->query();
	}
	
}
