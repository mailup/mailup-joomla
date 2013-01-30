<?php
/**
 * @package		Joomla.Site
 * @subpackage	mod_login
 * @copyright	Copyright (C) 2005 - 2012 Open Source Matters, Inc. All rights reserved.
 * @license		GNU General Public License version 2 or later; see LICENSE.txt
 */

// no direct access
defined('_JEXEC') or die('Restricted access');

class modMailupHelper
{
	static function getReturnURL($params, $type)
	{
		$app	= JFactory::getApplication();
		$router = $app->getRouter();
		$url = null;
		if ($itemid =  $params->get($type))
		{
			$db		= JFactory::getDbo();
			$query	= $db->getQuery(true);

			$query->select($db->quoteName('link'));
			$query->from($db->quoteName('#__menu'));
			$query->where($db->quoteName('published') . '=1');
			$query->where($db->quoteName('id') . '=' . $db->quote($itemid));

			$db->setQuery($query);
			if ($link = $db->loadResult()) {
				if ($router->getMode() == JROUTER_MODE_SEF) {
					$url = 'index.php?Itemid='.$itemid;
				}
				else {
					$url = $link.'&Itemid='.$itemid;
				}
			}
		}
		if (!$url)
		{
			// stay on the same page
			$uri = clone JFactory::getURI();
			$vars = $router->parse($uri);
			unset($vars['lang']);
			if ($router->getMode() == JROUTER_MODE_SEF)
			{
				if (isset($vars['Itemid']))
				{
					$itemid = $vars['Itemid'];
					$menu = $app->getMenu();
					$item = $menu->getItem($itemid);
					unset($vars['Itemid']);
					if (isset($item) && $vars == $item->query) {
						$url = 'index.php?Itemid='.$itemid;
					}
					else {
						$url = 'index.php?'.JURI::buildQuery($vars).'&Itemid='.$itemid;
					}
				}
				else
				{
					$url = 'index.php?'.JURI::buildQuery($vars);
				}
			}
			else
			{
				$url = 'index.php?'.JURI::buildQuery($vars);
			}
		}

		return base64_encode($url);
	}

	static function getType()
	{
		$user = JFactory::getUser();
		return (!$user->get('guest')) ? 'logout' : 'login';
	}
	
	
	
	
	
	
	public function treelists(){
		$db = JFactory::getDBO();

		$user =& JFactory::getUser();
		$subId = modMailupHelper::getSubId($user->id);

		//SELEZIONO TUTTE LE LISTE E LO STATO DELL'UTENTE SU DI ESSE
		$query = modMailupHelper::buildListsQuery();
		$db->setQuery($query);
		$lists = $db->loadObjectList();
		
		foreach($lists as $l){
			$query = modMailupHelper::buildGroupsQuery($l->listid);
			$db->setQuery($query);
			$l->groups = $db->loadObjectList();
		}
		$out = array("mailupUserid" => $subId, "lists" => $lists);

		return $out;
	}
	
	static function buildListsQuery(){
		$user =& JFactory::getUser();
		$subid = modMailupHelper::getSubId($user->id);
		
		$select = "
			a.id, 
			a.listid, 
			a.alias, 
			a.guid 
		";
		
		$from = "
			#__mailup_list a 
		";
		
		$where = array("a.visible=1");
		
		if($subid){
			$select .= ", IFNULL(u.enabled, 0) enabled, IFNULL(u.status, 0) status";
			$from .= "
				LEFT JOIN
					#__mailup_listsub u
				ON
					u.listid=a.listid
				AND
					u.subid={$subid}
			";
			//$where[] = "s.userid={$user->id}";
		}else{
			$select .= ", 0 enabled, 0 status";
		}
		
		return modMailupHelper::buildQuery($select, $from, $where);
	}
	
	static function buildGroupsQuery($listId){
		$user =& JFactory::getUser();
		$subid = modMailupHelper::getSubId($user->id);
		
		$select = "
			a.id, 
			a.groupid, 
			a.alias
		";
		
		$from = "
			#__mailup_group a 
		";
		
		$where = array("a.visible=1", "a.listid={$listId}");
		
		if($subid){
			$select .= ", IFNULL(u.enabled, 0) enabled, IFNULL(u.status, 0) status";
			$from .= "
				LEFT JOIN
					#__mailup_groupsub u
				ON
					u.groupid=a.groupid
				AND
					u.subid={$subid}
			";
			//$where[] = "s.userid={$user->id}";
		}else{
			$select .= ", 0 enabled, 0 status";
		}
		
		return modMailupHelper::buildQuery($select, $from, $where);
	}
	
	
	static function getSubId($id){
		static $instances;
		
		if(!$id){
			return;
		}
		
		if(!isset($instances)){
			$instances = array();
		}
		
		if(!isset($instances[$id])){
			$db =& JFactory::getDBO();
			$db->setQuery("SELECT id FROM #__mailup_subscriber WHERE userid={$id}");
			$instances[$id] = $db->loadResult();
		}
		
		return $instances[$id];
	}
	
	static function buildQuery($select, $from, $where){
		$where = is_array($where) ? implode(' AND ', $where) : $where;
		$query = "SELECT $select FROM $from WHERE $where";
		
		return $query;
		
	}
	
	static function loadJs(){
		static $loaded;
		
		if($loaded){
			return;
		}
		
		$loaded = true;
		
		$doc =& JFactory::getDocument();
		$doc->addScript('modules/mod_mailup/assets/js/mod_mailup.js');
	}
	
	static function loadCss(){
		static $loaded;
		
		if($loaded){
			return;
		}
		
		$loaded = true;
		
		$doc =& JFactory::getDocument();
		$doc->addStyleSheet('modules/mod_mailup/assets/css/mod_mailup.css');
	}
	
	static function loadLanguage(){
		static $loaded;
		
		if($loaded){
			return;
		}
		
		$loaded = true;
		
		$lang =& JFactory::getLanguage();
		$lang->load('com_mailup');
		
		$privacyAlert = JText::_('MOD_MAILUP CONTROL PRIVACY ALERT');
		$fieldsAlert = JText::_('MOD_MAILUP MISSING FIELDS ALERT');
		$emailAlert = JText::_('MOD_MAILUP INVALID EMAIL ALERT');
		
		$script = <<<EOS
window.addEvent('domready', function(){
	mod_mailup_helper.lang = {
		"MOD_MAILUP CONTROL PRIVACY ALERT": "$privacyAlert",
		"MOD_MAILUP MISSING FIELDS ALERT": "$fieldsAlert",
		"MOD_MAILUP INVALID EMAIL ALERT": "$emailAlert"
	}
});	
EOS;
		$doc = JFactory::getDocument();
		$doc->addScriptDeclaration($script);
		
	}
	
	function privacyLabel($params){
		$itemId = $params->get('privacy_itemid');
		
		JHTML::_('behavior.modal');
		
		$link = $itemId ? JHTML::link(JRoute::_("index.php?Itemid=$itemId&tmpl=component"), JText::_('MOD_MAILUP PRIVACY LINK TEXT'), 'class="modal"') : JText::_('MOD_MAILUP PRIVACY LINK TEXT');
		return JText::sprintf('MOD_MAILUP ACCEPT TERMS OF SERVICE', $link);
	}
	
}
