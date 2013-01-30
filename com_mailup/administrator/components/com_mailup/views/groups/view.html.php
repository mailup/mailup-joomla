<?php
// No direct access to this file
defined('_JEXEC') or die('Restricted access');
 
// import Joomla view library
jimport('joomla.application.component.view');
 
/**
 * MailUPs View
 */
class MailUPViewGroups extends JView
{
	/**
	 * MailUPs view display method
	 * @return void
	 */
	function display($tpl = null) 
	{
		// Get data from the model
		$items = $this->get('Items');
		$pagination = $this->get('Pagination');
 
		// Check for errors.
		if (count($errors = $this->get('Errors'))) 
		{
			JError::raiseError(500, implode('<br />', $errors));
			return false;
		}
		// Assign data to the view
		$this->items = $items;
		$this->pagination = $pagination;
 
		// Set the toolbar
		$this->addToolBar();
 
		// Display the template
		parent::display($tpl);
 
		// Set the document
		$this->setDocument();
	}
 
	/**
	 * Setting the toolbar
	 */
	protected function addToolBar() 
	{
		$canDo = MailUPHelper::getActions();
		JToolBarHelper::title(JText::_('COM_MAILUP_MANAGER_MAILUPS'), 'group');
		
		//JToolBarHelper::custom( 'groups.importLists', 'import', 'import', 'Import', false, false );
		
		JToolBarHelper::divider();
		
		if ($canDo->get('core.create')) 
		{
			//JToolBarHelper::addNew( 'group.add', 'JTOOLBAR_NEW');
		}
		if ($canDo->get('core.edit')) 
		{
			JToolBarHelper::editList('group.edit', 'JTOOLBAR_EDIT');
		}
		if ($canDo->get('core.delete')) 
		{
			//JToolBarHelper::deleteList('', 'groups.deleteGroup', 'JTOOLBAR_DELETE');
		}
		if ($canDo->get('core.admin')) 
		{
			JToolBarHelper::divider();
			//JToolBarHelper::preferences('com_mailup');
		}
	}
	/**
	 * Method to set up the document properties
	 *
	 * @return void
	 */
	protected function setDocument() 
	{
		$document = JFactory::getDocument();
		$document->setTitle(JText::_('COM_MAILUP_ADMINISTRATION'));
	}
}
