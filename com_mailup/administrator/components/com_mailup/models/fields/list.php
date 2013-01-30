<?php
// No direct access to this file
defined('_JEXEC') or die('Restricted access');
 
// import the list field type
jimport('joomla.form.helper');
jimport( 'joomla.form.fields.combo' );
JFormHelper::loadFieldClass('list');
JFormHelper::loadFieldClass('combo'); 

 
/**
 * MailUP Form Field class for the MailUP component
 */
class JFormFieldLists extends JFormFieldList
{
	/**
	 * The field type.
	 *
	 * @var		string
	 */
	protected $type = 'MailUP';
 
	/**
	 * Method to get a list of options for a list input.
	 *
	 * @return	array		An array of JHtml options.
	 */
	protected function getOptions() 
	{
		$db = JFactory::getDBO();
		$query = $db->getQuery(true);
		$query->select('#__mailup_list.id as id, name');
		$query->from('#__mailup_subscriber');
		$db->setQuery((string)$query);
		$lists = $db->loadObjectList();
		$options = array();
		if ($lists)
		{
			foreach($lists as $list) 
			{
				
				$options[] = JHtml::_('select.option', $list->id, $list->name);
			}
		}
		$options = array_merge(parent::getOptions(), $options);
		return $options;
	}
}