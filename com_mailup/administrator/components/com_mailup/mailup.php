<?php
// No direct access to this file
defined('_JEXEC') or die('Restricted access');
 
//JTable::addIncludePath(JPATH_COMPONENT.DS.'tables');
JTable::addIncludePath(JPATH_ADMINISTRATOR.DS.'components'.DS.'com_mailup'.DS.'tables');

// Access check.
if (!JFactory::getUser()->authorise('core.manage', 'com_mailup')) 
{
	return JError::raiseWarning(404, JText::_('JERROR_ALERTNOAUTHOR'));
}
 
// require helper file
JLoader::register('MailUPHelper', dirname(__FILE__) . DS . 'helpers' . DS . 'mailup.php');
 
// import joomla controller library
jimport('joomla.application.component.controller');
 
// Get an instance of the controller prefixed by MailUP
$controller = JController::getInstance('MailUP');
 
// Perform the Request task
$controller->execute(JRequest::getCmd('task'));
 
// Redirect if set by the controller
$controller->redirect();
