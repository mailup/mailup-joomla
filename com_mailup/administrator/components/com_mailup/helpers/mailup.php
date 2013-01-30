<?php
// No direct access to this file
defined('_JEXEC') or die('Restricted access');
 
/**
 * HelloWorld component helper.
 */
abstract class MailUPHelper
{
	/**
	 * Configure the Linkbar.
	 */
	public static function addSubmenu($submenu) 
	{

		JSubMenuHelper::addEntry(JText::_('COM_MAILUP_SUBMENU_USERS'), 'index.php?option=com_mailup', $submenu == 'MailUPs');
		JSubMenuHelper::addEntry(JText::_('COM_MAILUP_SUBMENU_LISTS'), 'index.php?option=com_mailup&view=lists', $submenu == 'lists');
		JSubMenuHelper::addEntry(JText::_('COM_MAILUP_SUBMENU_GROUPS'), 'index.php?option=com_mailup&view=groups', $submenu == 'groups');

		// set some global property
		$document = JFactory::getDocument();
	
		$document->addStyleDeclaration('.icon-32-import {background-image: url('.JURI::root().'administrator/components/com_mailup/images/icon-32-import.png);}');
		if ($submenu == 'lists') 
		{
			$document->setTitle(JText::_('COM_MAILUP_ADMINISTRATION_LISTS'));
		}
		if ($submenu == 'groups') 
		{
			$document->setTitle(JText::_('COM_MAILUP_ADMINISTRATION_GROUPS'));
		}
	}
	/**
	 * Get the actions
	 */
	public static function getActions($messageId = 0)
	{
		
		$user	= JFactory::getUser();
		$result	= new JObject;
		
 
		if (empty($messageId)) {
			$assetName = 'com_mailup';
		}
		else {
			$assetName = 'com_mailup.message.'.(int) $messageId;
		}
 
		$actions = array(
			'core.admin', 'core.manage', 'core.create', 'core.edit', 'core.delete'
		);
 
		foreach ($actions as $action) {
			$result->set($action,	$user->authorise($action, $assetName));
		}
 
		return $result;
	}
}
