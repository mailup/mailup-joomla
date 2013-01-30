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
require_once 'MailUpWsManage.php';

class MailUPControllerGroups extends JControllerAdmin
{
	/**
	 * Proxy for getModel.
	 * @since	1.6
	 */
	public function getModel($name = 'Groups', $prefix = 'MailUPModel') 
	{
		$model = parent::getModel($name, $prefix, array('ignore_request' => true));
		return $model;
	}
	
	
	public function deleteGroup(){
		
		$model = $this->getModel();
		
		$cids = JRequest::getVar( 'cid', array(), '', 'array' );
		$row = JTable::getInstance('group', 'MailUpTable');
			
		$componentParams = &JComponentHelper::getParams('com_mailup');
		
		$url = $componentParams->get('FrontendURL', 'defaultValue');
		$user_console = $componentParams->get('user', 'defaultValue');
	    $pwd_console = $componentParams->get('pwd', 'defaultValue');
	    $componentParams->get('consoleID', 'defaultValue');
		
		
		$WsManage = new MailUpWsManage();
		$WsManage->loginFromId($user_console, $pwd_console, $id_console);
			
		foreach($cids as $id){

			$row->load($id);
			$xmlGroup = $WsManage->DeleteGroup($row->get('groupid'), $id);
			
			$model->deleteGroups($id);
		}
		
		$this->setRedirect(JRoute::_('index.php?option=' . $this->option . '&view=' . $this->view_list, false));
		
	}
	

	
	
}