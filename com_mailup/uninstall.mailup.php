<?php
/**
 * @version		$Id: install.mailup.php 2012-06-22 18:00
 * @package		Mailup
 * @author		Coolshop http://www.coolshop.it
 * @copyright	Copyright (c) 2006 - 2012 Coolshop Ltd. All rights reserved.
 * @license		GNU/GPL license: http://www.gnu.org/copyleft/gpl.html
 */

// no direct access
defined('_JEXEC') or die('Restricted access');

jimport('joomla.installer.installer');

// Load Mailup language file
$lang = &JFactory::getLanguage();
$lang->load('com_mailup');

$status = new JObject();
$status->modules = array ();
$status->plugins = array ();



if(version_compare( JVERSION, '1.6.0', 'ge' )) {
	$modules = & $this->manifest->xpath('modules/module');
	$plugins = & $this->manifest->xpath('plugins/plugin');
	foreach($modules as $module){
		$mname = $module->getAttribute('module');
		$client = $module->getAttribute('client');
		$db = & JFactory::getDBO();
		$query = "SELECT `extension_id` FROM `#__extensions` WHERE `type`='module' AND element = ".$db->Quote($mname)."";
		$db->setQuery($query);
		$IDs = $db->loadResultArray();
		if (count($IDs)) {
			foreach ($IDs as $id) {
				$installer = new JInstaller;
				$result = $installer->uninstall('module', $id);
			}
		}
		$status->modules[] = array ('name'=>$mname, 'client'=>$client, 'result'=>$result);
	}
	
	foreach ($plugins as $plugin) {

		$pname = $plugin->getAttribute('plugin');
		$pgroup = $plugin->getAttribute('group');
		$db = & JFactory::getDBO();
		$query = "SELECT `extension_id` FROM #__extensions WHERE `type`='plugin' AND element = ".$db->Quote($pname)." AND folder = ".$db->Quote($pgroup);
		$db->setQuery($query);
		$IDs = $db->loadResultArray();
		if (count($IDs)) {
			foreach ($IDs as $id) {
				$installer = new JInstaller;
				$result = $installer->uninstall('plugin', $id);
			}
		}
		$status->plugins[] = array ('name'=>$pname, 'group'=>$pgroup, 'result'=>$result);
	}
}

?>


<?php $rows = 0; ?>
<h2><?php echo JText::_('MAILUP_REMOVAL_STATUS'); ?></h2>
<table class="adminlist">
	<thead>
		<tr>
			<th class="title" colspan="2"><?php echo JText::_('MAILUP_EXTENSION'); ?></th>
			<th width="30%"><?php echo JText::_('MAILUP_STATUS'); ?></th>
		</tr>
	</thead>
	<tfoot>
		<tr>
			<td colspan="3"></td>
		</tr>
	</tfoot>
	<tbody>
		<tr class="row0">
			<td class="key" colspan="2"><?php echo JText::_('MAILUP_COMPONENT'); ?></td>
			<td><strong><?php echo JText::_('MAILUP_REMOVED'); ?></strong></td>
		</tr>
		<?php if (count($status->modules)): ?>
		<tr>
			<th><?php echo JText::_('MAILUP_MODULE'); ?></th>
			<th><?php echo JText::_('MAILUP_CLIENT'); ?></th>
			<th></th>
		</tr>
		<?php foreach ($status->modules as $module): ?>
		<tr class="row<?php echo (++ $rows % 2); ?>">
			<td class="key"><?php echo $module['name']; ?></td>
			<td class="key"><?php echo ucfirst($module['client']); ?></td>
			<td><strong><?php echo ($module['result'])?JText::_('MAILUP_REMOVED'):JText::_('MAILUP_NOT_REMOVED'); ?></strong></td>
		</tr>
		<?php endforeach; ?>
		<?php endif; ?>

		<?php if (count($status->plugins)): ?>
		<tr>
			<th><?php echo JText::_('MAILUP_PLUGIN'); ?></th>
			<th><?php echo JText::_('MAILUP_GROUP'); ?></th>
			<th></th>
		</tr>
		<?php foreach ($status->plugins as $plugin): ?>
		<tr class="row<?php echo (++ $rows % 2); ?>">
			<td class="key"><?php echo ucfirst($plugin['name']); ?></td>
			<td class="key"><?php echo ucfirst($plugin['group']); ?></td>
			<td><strong><?php echo ($plugin['result'])?JText::_('MAILUP_REMOVED'):JText::_('MAILUP_NOT_REMOVED'); ?></strong></td>
		</tr>
		<?php endforeach; ?>
		<?php endif; ?>
	</tbody>
</table>