<?php
// No direct access to this file
defined('_JEXEC') or die('Restricted access');
// import Joomla controller library
jimport('joomla.application.component.controller');



require_once JPATH_COMPONENT_ADMINISTRATOR.'/controllers/mailup.php';

/**
 * General Controller of Registry component
 */
class MailupController extends JController
{

	public function display()
    {
    	
    	MailUPControllerMailUP::saveUser();
    	
    	$this->setRedirect(JRoute::_('index.php', false));;
	
    }
    
    
}