<?php
// No direct access to this file
defined('_JEXEC') or die('Restricted access');
 
// import the list field type
//jimport('joomla.form.helper');
JFormHelper::loadFieldClass('list');
//jimport( 'joomla.form.fields' );
 
/**
 * MailUP Form Field class for the MailUP component
 */
class JFormFieldGroup extends JFormFieldList
{
	/**
	 * The field type.
	 *
	 * @var		string
	 */
	protected $type = 'Group';
 
	/**
	 * Method to get a list of options for a list input.
	 *
	 * @return	array		An array of JHtml options.
	 */
	protected function getOptions() 
	{
//		die();
		$db = JFactory::getDBO();
		$query = $db->getQuery(true);
		$query->select('listid, name');
		$query->from('#__mailup_list');
		
		//die($query);
		$db->setQuery((string)$query);
		$lists = $db->loadObjectList();
		$options = array();
		if ($lists)
		{
			foreach($lists as $list) 
			{
				
				$options[] = JHtml::_('select.option', $list->listid, $list->name);
			}
		}
		$options = array_merge(parent::getOptions(), $options);
		return $options;
	}
}