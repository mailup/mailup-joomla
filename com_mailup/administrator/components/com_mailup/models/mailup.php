<?php
// No direct access to this file
defined('_JEXEC') or die('Restricted access');
 
// import Joomla modelform library
jimport('joomla.application.component.modeladmin');
require_once JPATH_COMPONENT_ADMINISTRATOR.'/controllers/MailUpWsSend.php';

/**
 * MailUP Model
 */
class MailUPModelMailUP extends JModelAdmin
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
	public function getTable($type = 'MailUP', $prefix = 'MailUPTable', $config = array()) 
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
		$form = $this->loadForm('com_mailup.mailup', 'mailup', array('control' => 'jform', 'load_data' => $loadData));
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
		return 'administrator/components/com_mailup/models/forms/mailup.js';
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
		$data = JFactory::getApplication()->getUserState('com_mailup.edit.mailup.data', array());
		if (empty($data)) 
		{
			$data = $this->getItem();
		}
		return $data;
	}
	
	
	

	
	
	public function addMailupUser($name, $email, $frontend=false, $guest=true) {
		
		if($name && $email) {
		
			$db = JFactory::getDBO();
			$query = "SELECT id FROM #__mailup_subscriber WHERE email = '".$email."'";
			$db->setQuery($query);
			$db->query();
			$userid = $db->loadResult();
			// se l'utente non esiste lo inserisco		
			if(!$userid){
				
				if($frontend && !$guest){
					$user =& JFactory::getUser();
					$values = "(NULL, {$db->quote($name)}, {$db->quote($email)}, {$user->id}, {$db->quote(strtoupper(md5(rand(1000,999999))))}, 1, {$db->quote(date("Y-m-d"))})";
				}else{
					$values = "(NULL, {$db->quote($name)}, {$db->quote($email)}, 0, {$db->quote(strtoupper(md5(rand(1000,999999))))}, 0, {$db->quote(date("Y-m-d"))})";
				}
				
				$query = "INSERT INTO #__mailup_subscriber (id, name, email, userid, code, joomla_user, create_date) VALUES $values";
				$db->setQuery($query);
				$db->query();
				$last_id = $db->insertid();
				
				$result = $last_id;
			}
			else {
				$result = $userid;
			}
		}
		else {
			$result = 0;
		}
		
		return $result;
		
	}
	
	public function getList($listId){
		if(!$listId){
			return;
		}
		$db =& JFactory::getDBO();
		$db->setQuery("SELECT * FROM #__mailup_list WHERE listid={$listId}");
		return $db->loadObject();
	}
	
	public function getGroup($groupId){
		if(!$groupId){
			return;
		}
		$db =& JFactory::getDBO();
		$db->setQuery("SELECT * FROM #__mailup_group WHERE groupid={$groupId}");
		return $db->loadObject();
	}
	
	public function getLists($currentUserID){
		
		//SELEZIONO TUTTE LE LISTE E LO STATO DELL'UTENTE SU DI ESSE
		$db = JFactory::getDBO();
		$query = "
			SELECT 
				a.id AS id, 
				a.listid AS listid, 
				a.alias AS alias, 
				a.guid AS guid, 
				o.enabled AS enabled, 
				o.status AS status 
			FROM 
				#__mailup_list AS a 
			LEFT JOIN 
				#__mailup_listsub AS o 
			ON 
				a.listid = o.listid 
			AND 
				o.subid = ".$currentUserID;
				
		$db->setQuery($query);
		$result_lists = $db->loadObjectList('listid');
		
		return $result_lists;
	}
	
	public function getGroups($currentUserID){
		
		//SELEZIONO TUTTI I GRUPPI E LO STATO DELL'UTENTE SU DI ESSE
		$db = JFactory::getDBO();
		$query = "SELECT a.id AS id, a.listid AS listid, a.groupid AS groupid, a.alias AS alias, o.enabled AS enabled, o.status AS status FROM #__mailup_group AS a LEFT JOIN #__mailup_groupsub AS o on a.groupid = o.groupid AND o.subid = ".$currentUserID;
		$db->setQuery($query);
		$result_groups = $db->loadObjectList('groupid');

		return $result_groups;
	}
	
	
	
	
	
	public function Xmlunsubscribe($userid, $guid, $listid, $email) {
		$componentParams = &JComponentHelper::getParams('com_mailup');
		$url = $componentParams->get('FrontendURL', 'defaultValue');
				
		$frontendURL = 'http://'.$url.'/frontend/Xmlunsubscribe.aspx?ListGuid='.$guid.'&list='.$listid.'&email='.$email;

		$WsSend = new MailUpWsSend();
		$res = file_get_contents($frontendURL);
		
		$list = MailUPModelMailUP::getList($listid);
		
		if(JDEBUG){
			error_log("trying to get $frontendURL");
			error_log("got result $res");
		}
				
		// 0 = succes unsubcription
		if($res == 0) {
			// AGGIORNO GRUPPI RELATIVI ALLA LISTA
			$db = JFactory::getDBO();
			//$query = "REPLACE INTO #__mailup_listsub SET subid = ".$userid.", listid = ".$listid.", unsubdate = '".date('Y-m-d H:i:s')."', enabled = 0, status = 3";
			$query = "
				REPLACE INTO #__mailup_listsub 
				SET 
					subid={$userid}, 
					listid={$listid},
					unsubdate=NOW(),
					enabled=0, 
					status=3,
					last_update=(SELECT IFNULL(ls2.last_update, 0) FROM #__mailup_listsub ls2 WHERE ls2.subid={$userid} AND ls2.listid={$listid})
				";
			
			$db->setQuery($query);
			$db->query();
			
			JFactory::getApplication()->enqueueMessage( JText::sprintf('COM_MAILUP USER SUCCESSFULLY UNREGISTERED', $list->alias), 'Message' );
		}
		if($res == 1) {
			JFactory::getApplication()->enqueueMessage( JText::sprintf('COM_MAILUP LIST UNREGISTER ERROR 1', $list->alias), 'Notice' );
		}
		if($res == 3) {
			JFactory::getApplication()->enqueueMessage( JText::sprintf('COM_MAILUP LIST UNREGISTER ERROR 3', $list->alias), 'Notice' );
		}
		
		return $res;       
	}
	

	
	public function Xmlsubscribe($userid, $listid, $name, $email) {
		
		$componentParams = &JComponentHelper::getParams('com_mailup');
		$params = array();
		
		$list = MailUPModelMailUP::getList($listid);
		
		$url = $componentParams->get('FrontendURL', 'defaultValue');
		$confirm = $componentParams->get('confirm_subscription', 1);
		
		$frontendURL = 'http://'.$url.'/frontend/Xmlsubscribe.aspx?list='.$listid.'&email='.$email.'&csvFldNames=campo1&csvFldValues='.urlencode($name);
		//$frontendURL .= $confirm ? '&confirm=true' : '&confirm=false';
		$frontendURL .=  "&confirm={$confirm}";
		
		$WsSend = new MailUpWsSend();
		$res = file_get_contents($frontendURL);
		
		if(JDEBUG){
			error_log("trying to get $frontendURL");
			error_log("got result $res");
		}
		
		
		$app = JFactory::getApplication();
		
		// 1 = generic error
		if($res == 0) {
			// AGGIORNO GRUPPI RELATIVI ALLA LISTA
			$db = JFactory::getDBO();
			$query = "
				REPLACE INTO #__mailup_listsub 
				SET 
					subid={$userid}, 
					listid={$listid},
					subdate=NOW(),
					enabled=1, 
					status=1,
					last_update=(SELECT IFNULL(ls2.last_update, 0) FROM #__mailup_listsub ls2 WHERE ls2.subid={$userid} AND ls2.listid={$listid})
				";
			$db->setQuery($query);
			$db->query();
			
			$app->enqueueMessage( JText::sprintf('COM_MAILUP USER SUCCESSFULLY REGISTERED', $list->alias), 'Message' );
		}
		if($res == 1) {
			$app->enqueueMessage( JText::sprintf('COM_MAILUP LIST REGISTER ERROR 1', $list->alias), 'Notice' );
		}
		if($res == 2) {
			$app->enqueueMessage( JText::sprintf('COM_MAILUP LIST REGISTER ERROR 2', $list->alias), 'Notice' );
		}
		if($res == 3) {
			$app->enqueueMessage( JText::sprintf('COM_MAILUP LIST REGISTER ERROR 3', $list->alias), 'Message' );
		}
		
		return $res;       
	}
	
	
	
	public function XmlUpdSubscriber($userid, $guid, $listid, $group_sub, $group_unsub, $email, $name, $replace=true) {
		
		$componentParams = &JComponentHelper::getParams('com_mailup');
		$params = array();
		$url = $componentParams->get('FrontendURL', 'defaultValue');
		
		
		// creo i gruppi ai quali si deve disiscrivere
		if(!empty($group_sub)) { $groups = '&group='.implode(";", $group_sub); }
		
		$frontendURL = 'http://'.$url.'/frontend/XmlUpdSubscriber.aspx?ListGuid='.$guid.'&list='.$listid.'&email='.$email.'&csvFldNames=campo1&csvFldValues='.urlencode($name).$groups;
		$frontendURL .= $replace ? '&replace=true' : '&replace=false';

		$WsSend = new MailUpWsSend();
		$res = file_get_contents($frontendURL);
		if(JDEBUG){
			error_log("trying to get $frontendURL");
			error_log("got result $res");
		}
		
		// se XmlUpdSubscriber avvenuta con successo aggiorno i gruppi a cui l'utente
		// si è disiscritto e i gruppi ai quali si è iscritto
		if($res == 0) {
			foreach($group_unsub as $G){
				$db = JFactory::getDBO();
				$query = "REPLACE INTO #__mailup_groupsub SET subid = ".$userid.", groupid = ".$G.", unsubdate = '".date('Y-m-d H:i:s')."', enabled = 0, status = 0";
				$db->setQuery($query);
				$db->query();
			}
			
			foreach($group_sub as $G){
				$db = JFactory::getDBO();
				$query = "REPLACE INTO #__mailup_groupsub SET subid = ".$userid.", groupid = ".$G.", subdate = '".date('Y-m-d H:i:s')."', enabled = 1, status = 1";
				$db->setQuery($query);
				$db->query();
			}		

			//JFactory::getApplication()->enqueueMessage( JText::_( 'Update iscrizione gruppi riuscita con successo' ), 'Message' );
		}
		
				
		return $res;       
	}
	
	
	public function updateSubscriber($name ,$currentUserID){

		// aggiorno nome dell'utente sulla tabella #__mailup_subscriber
		$db = JFactory::getDBO();
		$query = "UPDATE #__mailup_subscriber SET name = '".$name."' WHERE id = ".$currentUserID;
		$db->setQuery($query);
		$db->query();
		
	}
		
	
	
	
}
