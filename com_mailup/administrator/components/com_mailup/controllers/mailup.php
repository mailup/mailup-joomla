<?php
// No direct access to this file
defined('_JEXEC') or die('Restricted access');

// import Joomla controllerform library
jimport('joomla.application.component.controllerform');
jimport('joomla.application.component.controller');

require_once JPATH_COMPONENT_ADMINISTRATOR . '/models/mailup.php';

require_once 'MailUpWsSend.php';

/**
 * MailUP Controller
 */
class MailUPControllerMailUP extends JControllerForm {

	public function saveUser() {

		$form = JRequest::get();
		
		$frontend = JRequest::getVar('frontend');
		$app =& JFactory::getApplication();		
		// inizializzato da frontend
		if ($frontend) {
			$user =& JFactory::getUser();
			$guest = $user->guest;
			
			if (!$form['jform']['id']) {
				$currentUserID = MailUPModelMailUP::addMailupUser($form['jform']['name'], $form['jform']['email'], $frontend, $guest);
			} else {
				$currentUserID = $form['jform']['id'];
			}
		}
		// inizializzato da backend
		else {
			$currentUserID = $form['jform']['id'];
		}
		// se currentUserID = 0 utente già iscritto a mailup e non registarto non faccio nulla
		if ($currentUserID != 0) {

			$result_lists = MailUPModelMailUP::getLists($currentUserID);
			$result_groups = MailUPModelMailUP::getGroups($currentUserID);
			 // echo '<pre>';
			// // var_dump($result_lists);
			 // var_dump($result_groups);
			 // var_dump($form['jform']['lists']);
			 // echo '</pre>';
			 // die();

			foreach ($form['jform']['lists'] as $keyL => $list) {
				//echo "<br>KeyL: ".$keyL." -> Value: ".$list['value'];

				if ($result_lists[$keyL] -> listid == $keyL) {

					if ($result_lists[$keyL] -> enabled != $list['value']) {

						// Se utente non è iscritto (enabled == 0) e ora lo deve essere ($list[value] == 1) allora lo scrivo
						if($list['value'] == 1){
							if ($result_lists[$keyL] -> enabled == 0) {
								//echo "<br>Non iscritto quindi è da iscrivere";
								$func = MailUPModelMailUP::Xmlsubscribe($currentUserID, $result_lists[$keyL] -> listid, $form['jform']['name'], $form['jform']['email']);
							}
						}
						
						if(!$frontend || !$guest){
							if ($result_lists[$keyL] -> enabled == 1 && $list['value'] == 0) {
								//echo "<br>Era iscritto ma è da disiscrivere";
								$func = MailUPModelMailUP::Xmlunsubscribe($currentUserID, $result_lists[$keyL] -> guid, $result_lists[$keyL] -> listid, $form['jform']['email']);
							}
						}
					}else{
						if($frontend && $guest && $list['value'] == 1){
							$app->enqueueMessage(JText::sprintf('MOD_MAILUP USER ALREADY REGISTERED', $result_lists[$keyL]->alias), 'notice');
						}
					}
					
					$group_sub = array();
					$group_unsub = array();
					
					// SALVO ARRAY DI GRUPPI PER UPDATE
					foreach ($result_groups as $g){
						$oldValue = $g->enabled ? $g->enabled : 0;
						
						if(isset($list['groups'][$g->groupid])){
							
							$newValue = $list['groups'][$g->groupid];
							
							if($frontend && $guest && !$newValue && $oldValue){
								$newValue = $oldValue;
							}
							
							if($newValue){
								$group_sub[] = $g->groupid;
							}else{
								$group_unsub[] = $g->groupid;
							}
							
							if($newValue != $oldValue){
								$msgString = $newValue ? 'COM_MAILUP USER SUCCESSFULLY REGISTERED GROUP' : 'COM_MAILUP USER SUCCESSFULLY UNREGISTERED GROUP';
								$app->enqueueMessage(JText::sprintf($msgString, $g->alias));
							}
							
						}else{
							if($oldValue){
								$group_sub[] = $g->groupid;
							}else{
								$group_unsub[] = $g->groupid;
							}
						}
						
					}
					
					// foreach ($list['groups'] as $keyG => $group) {
						// if($frontend && $guest){
							// if ($group) {
								// $group_sub[] = $keyG;
							// }
						// }else{
							// if ($group) {
								// $group_sub[] = $keyG;
							// } else {
								// $group_unsub[] = $keyG;
							// }
						// }
// 
					// }

					// AGGIORNO GRUPPI RELATIVI ALLA LISTA
					$replace = !($frontend && $guest);
					$func = MailUPModelMailUP::XmlUpdSubscriber($currentUserID, $result_lists[$keyL] -> guid, $result_lists[$keyL] -> listid, $group_sub, $group_unsub, $form['jform']['email'], $form['jform']['name'], $replace);  

				} // endif
			}

			MailUPModelMailUP::updateSubscriber($form['jform']['name'], $currentUserID);

		}// endif se utente non è iscritto
		// else {
			// JFactory::getApplication() -> enqueueMessage(JText::_('Sei già Iscritto alla Newsletter'), 'Notice');
		// }
		//die();
		$this -> setRedirect(JRoute::_('index.php?option=' . $this -> option . '&view=' . $this -> view_list, false));

	}

}
