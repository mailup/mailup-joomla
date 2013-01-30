<?php
/**
 * @package		Joomla.Site
 * @subpackage	mod_login
 * @copyright	Copyright (C) 2005 - 2012 Open Source Matters, Inc. All rights reserved.
 * @license		GNU General Public License version 2 or later; see LICENSE.txt
 */

// no direct access
defined('_JEXEC') or die('Restricted access');

// Include the syndicate functions only once
require_once dirname(__FILE__).'/helper.php';

JHtml::_('behavior.keepalive');
JHtml::_('behavior.mootools');

//$params->def('greeting', 1);

modMailupHelper::loadJs();
modMailupHelper::loadCss();
modMailupHelper::loadLanguage();

$type	= modMailupHelper::getType();
$return	= modMailupHelper::getReturnURL($params, $type);
$user	= JFactory::getUser();

require JModuleHelper::getLayoutPath('mod_mailup', $params->get('layout', 'default'));
