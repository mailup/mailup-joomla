<?php
// No direct access to this file
defined('_JEXEC') or die('Restricted access');
 
// import the list field type
jimport('joomla.form.helper');
JFormHelper::loadFieldClass('list');
 
/**
 * MailUP Form Field class for the MailUP component
 */
class JFormFieldMailUP extends JFormFieldList
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
		$query->select('#__mailup_subscriber.id as id, name, email, user_code, email_user_state, lists, groups, email_date_subscription');
		$query->from('#__mailup_subscriber');
		//$query->leftJoin('#__categories on catid=#__categories.id');
		$db->setQuery((string)$query);
		$messages = $db->loadObjectList();
		$options = array();
		if ($messages)
		{
			foreach($messages as $message) 
			{
				$options[] = JHtml::_('select.option', $message->id, $message->name . ($message->email ? ' (' . $message->user_code . ')' : ''));
			}
		}
		$options = array_merge(parent::getOptions(), $options);
		return $options;
	}
}
