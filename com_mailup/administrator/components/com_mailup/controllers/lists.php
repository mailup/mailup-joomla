<?php
// No direct access to this file
defined('_JEXEC') or die('Restricted access');

// import Joomla controlleradmin library
jimport('joomla.application.component.controlleradmin');

/**
 * MailUPs Controller
 */

require_once 'MailUpWsSend.php';
require_once 'MailUpWsImport.php';

class MailUPControllerLists extends JControllerAdmin {
	/**
	 * Proxy for getModel.
	 * @since	1.6
	 */
	public function getModel($name = 'Lists', $prefix = 'MailUPModel') {
		$model = parent::getModel($name, $prefix, array('ignore_request' => true));
		return $model;
	}

	public function deleteList() {

		$model = $this -> getModel();

		$cids = JRequest::getVar('cid', array(), '', 'array');
		$row = JTable::getInstance('list', 'MailUpTable');

		$componentParams = &JComponentHelper::getParams('com_mailup');

		$url = $componentParams -> get('FrontendURL', 'defaultValue');
		$user_console = $componentParams -> get('user', 'defaultValue');
		$pwd_console = $componentParams -> get('pwd', 'defaultValue');
		$id_console = $componentParams -> get('consoleID', 'defaultValue');
		$nameUnique = $componentParams -> get('nameUnique', 'defaultValue');

		$WsSend = new MailUpWsSend();
		$WsSend -> loginFromId($user_console, $pwd_console, $id_console);

		foreach ($cids as $idList) {

			$row -> load($idList);
			// COMMENTATA PER EVITARE DI CANCELLARE DATI SENSIBILI -> DA ABILITARE
			//$xmlLists = $WsSend->DeleteList($row->get('listid'), $nameUnique);

			$model -> deleteLists($idList);

		}

		$this -> setRedirect(JRoute::_('index.php?option=' . $this -> option . '&view=' . $this -> view_list, false));
	}

	public function importLists() {

		$model = $this -> getModel();

		$WsImport = new MailUpWsImport();

		$xmlLists = $WsImport -> GetNlLists();

		if ($xmlLists) {

			$xml = $model -> xmltostring($xmlLists);

			var_dump($xml);
			
			if($xml){
				$count = 1;
				foreach ($xml->List as $list) {
					$groups = array();
					foreach ($list->Groups->Group as $tmp) {
						$groups[(string)$tmp["idGroup"]] = array('idGroup' => (string)$tmp["idGroup"], 'groupName' => (string)$tmp["groupName"]);
	
					}
					$selectLists[$count] = array('value' => (string)$list['idList'], 'label' => (string)$list['listName'], 'guid' => (string)$list['listGUID'], "groups" => $groups);
					$count++;
				}
				
			}else{
				JError::raiseWarning(500, JText::_('COM_MAILUP_ERROR_IMPORTING_LISTS'));
			}
			
		}else{
			JError::raiseWarning(500, JText::_('COM_MAILUP_ERROR_IMPORTING_LISTS'));
		}
		
		$groups_to_delete = array();
		$lists_to_delete = array();

		foreach ($selectLists as $list) {

			$lists_to_delete[] = $list['value'];

			// SE LA LISTA CONTIENE GRUPPI LI INSERISCO NELLA TABELLA GRUPPI
			if (is_array($list['groups'])) {
				foreach ($list['groups'] as $group) {

					$groups_to_delete[] = $group['idGroup'];

					$result_group = $model -> selectGroups($group['idGroup']);

					if ($result_group == 0) {
						$model -> insertGroup($group['groupName'], $list['value'], $group['idGroup']);
					} else {
						$model -> updateGroup($group['groupName'], $list['value'], $group['idGroup']);
					}
				}
			}

			$result_list = $model -> selectLists($list['value']);

			if ($result_list == 0) {
				$model -> insertList($list['label'], $list['value'], $list['guid']);
			} else {
				$model -> updateList($list['label'], $list['value']);
			}
		}

		// ELIMINO LE LISTE E I GRUPPI NON PRESENTI NELL'ARRAY PERCHE' NON ESISTONO PIÙ SU MAILUP
		$lists_to_delete = implode(",", $lists_to_delete);
		$groups_to_delete = implode(",", $groups_to_delete);

		$model -> deleteListsGroups($lists_to_delete, $groups_to_delete);

		$this -> setRedirect(JRoute::_('index.php?option=' . $this -> option . '&view=' . $this -> view_list, false));

	}

}
