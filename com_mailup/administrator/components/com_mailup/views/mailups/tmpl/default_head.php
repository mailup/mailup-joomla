<?php
// No direct access to this file
defined('_JEXEC') or die('Restricted Access');
?>
<tr>
	<th width="5">
		<?php echo JText::_('COM_MAILUP_MAILUP_HEADING_ID'); ?>
	</th>
	<th width="20">
		<input type="checkbox" name="toggle" value="" onclick="checkAll(<?php echo count($this->items); ?>);" />
	</th>			
	<th>
		<?php echo JText::_('COM_MAILUP_MAILUP_HEADING_USER_NAME'); ?>
	</th>
		<th>
		<?php echo JText::_('COM_MAILUP_MAILUP_HEADING_USER_EMAIL'); ?>
	</th>
		<th>
		<?php echo JText::_('COM_MAILUP_MAILUP_HEADING_USER_JOOMLA'); ?>
	</th>
		<th>
		<?php echo JText::_('COM_MAILUP_MAILUP_HEADING_USER_CREATE_DATE'); ?>
	</th>
</tr>
