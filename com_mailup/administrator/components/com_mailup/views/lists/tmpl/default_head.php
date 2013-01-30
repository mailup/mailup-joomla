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
		<th width="300">
		<?php echo JText::_('COM_MAILUP_MAILUP_HEADING_LISTS_ALIAS'); ?>
	</th>
		<th width="330">
		<?php echo JText::_('COM_MAILUP_MAILUP_HEADING_LISTS_NAME'); ?>
	</th>
		<th width="300">
		<?php echo JText::_('COM_MAILUP_MAILUP_HEADING_LISTS_DESCRIPTION'); ?>
	</th>
		<th width="25">
		<?php echo JText::_('COM_MAILUP_MAILUP_HEADING_LISTS_VISIBLE'); ?>
	</th>
	<!--
		<th width="30">
		<?php //echo JText::_('COM_MAILUP_MAILUP_HEADING_LISTS_LISTID'); ?>
	</th>
		<th width="100">
		<?php //echo JText::_('COM_MAILUP_MAILUP_HEADING_LISTS_GUID'); ?>
	</th>
	<th>
		<?php //echo JText::_('COM_MAILUP_MAILUP_HEADING_LISTS_CRETAEDATE'); ?>
	</th>
	-->
	<th width="75">
		<?php echo JText::_('COM_MAILUP_MAILUP_HEADING_LISTS_UPDATEDATE'); ?>
	</th>
</tr>