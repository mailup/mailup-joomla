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
		<?php echo JText::_('COM_MAILUP_MAILUP_HEADING_GROUP_ALIAS'); ?>
	</th>
	<th width="330">
		<?php echo JText::_('COM_MAILUP_MAILUP_HEADING_GROUP_NAME'); ?>
	</th>
	<th width="300">
		<?php echo JText::_('COM_MAILUP_MAILUP_HEADING_GROUP_DESCRIPTION'); ?>
	</th>
	<th width="25">
		<?php echo JText::_('COM_MAILUP_MAILUP_HEADING_GROUP_VISIBLE'); ?>
	</th>
	<th width="260">
		<?php echo JText::_('COM_MAILUP_MAILUP_HEADING_GROUP_LISTNAME'); ?>
	</th>
	<!--
	<th width="20">
		<?php //echo JText::_('COM_MAILUP_MAILUP_HEADING_GROUP_GROUPID'); ?>
	</th>
	<th width="65">
		<?php //echo JText::_('COM_MAILUP_MAILUP_HEADING_GROUP_CRETAEDATE'); ?>
	</th>
	-->
	<th width="75">
		<?php echo JText::_('COM_MAILUP_MAILUP_HEADING_GROUP_UPDATEDATE'); ?>
	</th>
</tr>
