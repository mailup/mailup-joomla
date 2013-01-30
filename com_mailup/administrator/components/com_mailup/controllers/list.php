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
class MailUPControllerList extends JControllerForm
{
	
	public function applyList() {
		
		$model = $this->getModel();
		
		$form = JRequest::get();
		
		$plugin = &JPluginHelper::getPlugin('user', 'mailupsubscriber');
    	$pluginParams = new JParameter($plugin->params);
    			
		//Passwor e username console
	    $user_console = $pluginParams->get('user', 'defaultValue');
	    $pwd_console = $pluginParams->get('pwd', 'defaultValue');
	    $id_console = $pluginParams->get('consoleID', 'defaultValue');
		$nameUnique = $pluginParams->get('nameUnique', 'defaultValue');
		
		
		$WsSend = new MailUpWsSend();
		$WsSend->loginFromId($user_console, $pwd_console, $id_console);
	
		$xmlLists = $WsSend->CreateList($form['jform']['name'], $nameUnique);
		

		if ($xmlLists) {
                  
			$xml = $model->xmltostring($xmlString);
                   
                    foreach ($xml->List as $list) {
						$selectList = array('listId' => (string)$list['Id'], 'listName'=> (string)$list['Name'], 'guid'=>(string)$list['Guid']);

		            }
       }


		$model->insertList($form['jform']['name'], $form['jform']['description'], $selectList['listId'], $selectList['guid'], $form['jform']['alias']);

		
		$this->setRedirect(JRoute::_('index.php?option=' . $this->option . '&view=' . $this->view_list, false));
		
		
	}
	
	
	public function updateList() {
		
		$model = $this->getModel();
		
		$form = JRequest::get();
		
		// SE RENDO NON VISIBILE LA LISTA FORZO NON VISIBILI ANCHE I GRUPPI
		if(!$form['jform']['visible']) {
			
			$model->updateGroup($form['jform']['id']);
			
		}
		
		$model->unpdateList($form['jform']['id'], $form['jform']['alias'], $form['jform']['description'], $form['jform']['visible']);

		
		$this->setRedirect(JRoute::_('index.php?option=' . $this->option . '&view=' . $this->view_list, false));
		
	}	
	
	
	
	
	
	
}
