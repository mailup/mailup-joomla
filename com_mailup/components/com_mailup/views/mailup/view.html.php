<?php
// No direct access to this file
defined('_JEXEC') or die('Restricted access');
 
// import Joomla view library
jimport('joomla.application.component.view');
 
/**
 * HTML View class for the HelloWorld Component
 */
class MailupViewMailup extends JView
{
	// Overwriting JView display method
	function display($tpl = null) 
	{
		parent::display($tpl);
	}
}
