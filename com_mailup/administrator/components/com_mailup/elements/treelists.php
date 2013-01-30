<?php 
defined('_JEXEC') or die('Restricted access');

class JFormFieldTreelists extends JFormField {

	protected $type = 'Treelists';

	public function getInput() {
		jimport('joomla.filesystem.file');
		$doc = &JFactory::getDocument();
		$doc->addStyleDeclaration('#'.$this->id.' .labellist{width:350px; display:inline-block; font-weight:bold;"}');
		
		$doc->addScriptDeclaration('function setToNo(classe){
			$$("."+classe+\'[value="0"]\').each(function(e,i){
				e.setProperty("checked",true);
			});
			$$("."+classe+\'[value="1"]\').each(function(e,i){
				e.setProperty("checked",false);
			});
		}
		
		function setToYes(classe){
			$$("."+classe+\'[value="0"]\').each(function(e,i){
				e.setProperty("checked",false);
			});
			$$("."+classe+\'[value="1"]\').each(function(e,i){
				e.setProperty("checked",true);
			});
		}');

		
			
//		$ctrlName = $this->name;
		$class = 'class="radio"';
		$output = '';
//		$value = $this->value;
		$output .= '<fieldset id="'.$this->id.'"'.$class.'>';
		
		//SELEZIONO TUTTE LE LISTE E LO STATO DELL'UTENTE SU DI ESSE
		$db = JFactory::getDBO();
		$query = "SELECT a.id AS id, a.listid AS listid, a.alias AS alias, a.guid AS guid, o.enabled AS enabled, o.status AS status FROM #__mailup_list AS a LEFT JOIN #__mailup_listsub AS o ON a.listid = o.listid AND o.subid = ".$this->form->getValue('id');
		$db->setQuery($query);
		$result_lists = $db->loadObjectList();
		
		//SELEZIONO TUTTI I GRUPPI E LO STATO DELL'UTENTE SU DI ESSE
		$db = JFactory::getDBO();
		$query = "SELECT a.id AS id, a.listid AS listid, a.groupid AS groupid, a.alias AS alias, o.enabled AS enabled, o.status AS status FROM #__mailup_group AS a LEFT JOIN #__mailup_groupsub AS o on a.groupid = o.groupid AND o.subid = ".$this->form->getValue('id');
		$db->setQuery($query);
		$result_groups = $db->loadObjectList();
		
		//die(var_dump($result_groups));
				
		foreach($result_lists as $keyL=>$list){
			
			$output.= '<br><label class="labellist">'.$list->alias.'</label>';
	
			$output.= '<input type="radio" class="list'.$keyL.'" name="jform[lists]['.$list->listid.'][value]" value="1" '.(($list->enabled)?'checked="checked"':"").'><label>'.JText::_('JYES').'</label>';
			$output.= '<input type="radio" class="list'.$keyL.'" name="jform[lists]['.$list->listid.'][value]" value="0" '.((!$list->enabled)?'checked="checked"':"").' onclick="setToNo(\'group'.$keyL.'\')"><label>'.JText::_('JNO').'</label>';
			
			if($list->status == 1) { $list_status = '<a title="" class="jgrid"><span class="state unpublish"></span></a>'; }
			elseif($list->status == 2)  { $list_status = '<a title="" class="jgrid"><span class="state publish"></span></a>'; }
			
			$output.= '<label>'.$list_status.'</label>';
			
			
			foreach ($result_groups as $keyG=>$group) {
								
				if($group->listid == $list->listid) {

					$output.= '<br><label class="labellist">&nbsp&nbsp&nbsp&nbsp|-- '.$group->alias.'</label>';
					$output.= '<input type="radio" class="group'.$keyL.'" id="group'.$keyG.'-1" name="jform[lists]['.$list->listid.'][groups]['.$group->groupid.']" value="1" '.(($group->enabled)?'checked="checked"':"").' onclick="setToYes(\'list'.$keyL.'\')"><label>'.JText::_('JYES').'</label>';
					$output.= '<input type="radio" class="group'.$keyL.'" id="group'.$keyG.'-0" name="jform[lists]['.$list->listid.'][groups]['.$group->groupid.']" value="0" '.((!$group->enabled)?'checked="checked"':"").'><label>'.JText::_('JNO').'</label>';
					
				}
				
			}


		}
	
		$output .= '</fieldset>';

		return $output;
	}
}