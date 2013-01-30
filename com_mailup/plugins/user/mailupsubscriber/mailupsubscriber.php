<?php
/**
 * @copyright	Copyright (C) 2005 - 2009 Open Source Matters, Inc. All rights reserved.
 * @license		GNU General Public License version 2 or later; see LICENSE.txt
 */

// No direct access
defined('_JEXEC') or die('Restricted access');

/**
 * Joomla User plugin
 *
 * @package		Joomla.Plugin
 * @subpackage	User.joomla
 * @since		1.5
 */

require_once JPATH_ADMINISTRATOR.'/components/com_mailup/controllers/MailUpWsSend.php';


class plgUserMailUPsubscriber extends JPlugin
{	

	/**
	 * Utility method to act on a user after it has been saved.
	 *
	 * This method sends a registration email to new users created in the backend.
	 *
	 * @param	array		$user		Holds the new user data.
	 * @param	boolean		$isnew		True if a new user is stored.
	 * @param	boolean		$success	True if user was succesfully stored in the database.
	 * @param	string		$msg		Message.
	 *
	 * @return	void
	 * @since	1.6
	 */
	
	
	
	public function onUserAfterSave($user, $isnew, $success, $msg)
	{

		$mailupUserid = $this->checkMailupUser($user['email']);
		
		
		if ($isnew && $success) {
		
			$db = JFactory::getDBO();
			
			if($mailupUserid) {
				
				//die("ci sono".$mailupUserid);
				
				$query = "UPDATE #__mailup_subscriber SET userid = '".$user['id']."', joomla_user = 1 WHERE id = '".$mailupUserid."'";
				$db->setQuery($query);
				$db->query();
			}
			else {
					
				//die("non ci sono".$mailupUserid);
				
				$query = "INSERT INTO #__mailup_subscriber (id, name, email, userid, code, joomla_user, create_date) VALUES (NULL, '".$user['name']."', '".$user['email']."', '".$user['id']."', '".strtoupper(md5(rand(1000,999999)))."', 1, '".date("Y-m-d")."')";
				$db->setQuery($query);
				$db->query();
			}
		}
		return true;

	}
	
	
	
	// Controllo se l'utente si è già iscritto alla newsletter
	public function checkMailupUser($email){
		
		$db = JFactory::getDbo();
		$query = "SELECT id FROM #__mailup_subscriber WHERE email = '".$email."'";
		$db->setQuery($query);
		$db->query();
		$result = $db->loadResult();
		
		return $result;
		
	}
	
	
	
	

	
	

	
	
}
