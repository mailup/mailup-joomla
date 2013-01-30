<?php
// No direct access to this file
defined('_JEXEC') or die('Restricted access');
 
// import Joomla controllerform library
jimport('joomla.application.component.controllerform');

require_once 'MailUpWsImport.php';
require_once 'MailUpWsSend.php';
require_once 'MailUpWsManage.php';

/**
 * MailUP Controller
 */
class MailUPControllerGroup extends JControllerForm
{
	
		public function applyGroup() {
			
			$model = $this->getModel();
			
			$form = JRequest::get();
			
			$plugin = &JPluginHelper::getPlugin('user', 'mailupsubscriber');
	    	$pluginParams = new JParameter($plugin->params);
	    			
			//Passwor e username console
		    $user_console = $pluginParams->get('user', 'defaultValue');
		    $pwd_console = $pluginParams->get('pwd', 'defaultValue');
		    $id_console = $pluginParams->get('consoleID', 'defaultValue');
			$nameUnique = $pluginParams->get('nameUnique', 'defaultValue');
			
			
			$WsManage = new MailUpWsManage();
			$WsManage->loginFromId($user_console, $pwd_console, $id_console);
		
			$xmlGroup = $WsManage->CreateGroup($form['jform']['listid'], $form['jform']['name'], $form['jform']['description']);
			
			if ($xmlGroup) {
				$xml = $model->xmltostring($xmlString);
                    
	                    foreach ($xml->group as $group) {
							$selectGroup = array('groupID' => (string)$group->groupID, 'groupName'=> (string)$group->groupName, 'groupdNotes'=>(string)$group->groupNotes);
			            }
	       }
			
			
	       $model->insertGroup($form['jform']['name'], $form['jform']['description'], $form['jform']['listid'], $selectGroup['groupID']);
	
			
			$this->setRedirect(JRoute::_('index.php?option=' . $this->option . '&view=' . $this->view_list, false));
			
			
		}
		

		
	public function updateGroup() {
		
		$model = $this->getModel();
				
		$form = JRequest::get();

		// SE RENDO VISIBILE IL GRUPPO FORZO VISIBILE ANCHE LA LISTA
		if($form['jform']['visible']) {
			
			$model->setViewList($form['jform']['id']);

		}
		
		$model->updateGroup($form['jform']['id'], $form['jform']['description'], $form['jform']['alias'], $form['jform']['visible']);
		
		
		$this->setRedirect(JRoute::_('index.php?option=' . $this->option . '&view=' . $this->view_list, false));
		
	}	

	
}
