<?php
/**
 * @package		Joomla.Site
 * @subpackage	mod_login
 * @copyright	Copyright (C) 2005 - 2012 Open Source Matters, Inc. All rights reserved.
 * @license		GNU General Public License version 2 or later; see LICENSE.txt
 */

// no direct access
defined('_JEXEC') or die('Restricted access');


$res = modMailupHelper::treelists(); 

$lists = $res['lists'];
//$result_groups = $res['result_groups'];


$user = JFactory::getUser();


//echo '<pre>';
//var_dump($result_lists);
//die();		

?>

<div id="mod_mailup<?php echo $module->id; ?>" class="mod_mailup" >
<form name="adminFormMailup" action="<?php echo JRoute::_('index.php') ?>" method="post" class="mod_mailup">
	<input type="hidden" name="jform[id]" value="<?php if(!$user->guest) { echo $res['mailupUserid']; } ?>" />
	
	<p class="form-mailup-name">
		<label><?php echo JText::_('MOD_MAILUP NAME LABEL') ?></label>
		<input id="mailup_name" name="jform[name]" <?php if(!$user->guest) {echo 'readonly="readonly" value="'.$user->name.'"';}?>  class="inputbox"/>
	</p>

	<p class="form-mailup-email">
		<label><?php echo JText::_('MOD_MAILUP EMAIL LABEL') ?></label>
		<input id="mailup_email" name="jform[email]" <?php if(!$user->guest) {echo 'readonly="readonly" value="'.$user->email.'"';}?> class="inputbox"/>
	</p>
	
			
			<fieldset id="mod_mailup" class="radio">
			
			<?php foreach($lists as $keyL=>$list) :
					
					if($user->guest) { $list->enabled = 0; }
				
			 ?>
				<div>	
					<label class="labellist"><?php echo $list->alias ?></label>
					<input type="radio" class="list<?php echo $keyL ?>" name="jform[lists][<?php echo $list->listid ?>][value]" value="1" <?php echo (($list->enabled) ? 'checked="checked"' : '""') ?> ><label><?php  echo JText::_('JYES') ?></label>
					<input type="radio" class="list<?php echo $keyL ?>" name="jform[lists][<?php echo $list->listid ?>][value]" value="0" <?php echo ((!$list->enabled) ? 'checked="checked"' : '""') ?> onclick="mod_mailup_helper.setToNo('group<?php echo $keyL ?>')"><label><?php  echo JText::_('JNO') ?></label>
				</div>	
			<?php  
					
				//foreach ($result_groups as $keyG=>$group) {
				foreach($list->groups as $keyG=>$group){
						
					if($user->guest) { $group->enabled = 0; }
					
					//if($group->listid == $list->listid) {
						
						//if($group->visible) {
			?>
					<div>	
						<label class="labellist"><?php echo '&nbsp|-- '.$group->alias ?></label>
						<input type="radio" class="group<?php echo $keyL ?>" id="list<?php echo $keyL ?>-group<?php echo $keyG ?>-1" name="jform[lists][<?php echo $list->listid ?>][groups][<?php echo $group->groupid ?>]" value="1" <?php echo ($group->enabled ? 'checked="checked"' : "") ?> onclick="mod_mailup_helper.setToYes('list<?php echo $keyL ?>')"><label><?php  echo JText::_('JYES') ?></label>
						<input type="radio" class="group<?php echo $keyL ?>" id="list<?php echo $keyL ?>-group<?php echo $keyG ?>-0" name="jform[lists][<?php echo $list->listid ?>][groups][<?php echo $group->groupid ?>]" value="0" <?php echo (!$group->enabled ? 'checked="checked"' : "") ?> ><label><?php  echo JText::_('JNO') ?></label>
					</div>			
			<?php		//}
					//}
				}
				endforeach;
			
			?>
			
			</fieldset>
			
			
		<div id="mod_mailup_buttons<?php echo $module->id; ?>" class="mod_mailup_buttons">
		<?php if($user->guest) : ?>
			<input name="chkPrivacy" id="mailup_chkPrivacy" type="checkbox" onchange="mod_mailup_helper.ctrlPrivacy();" />
			<label for="mailup_chkPrivacy"><?php echo modMailupHelper::privacyLabel($params);  ?></label>
			
			<input 
				class="button subbutton" 
				type="submit" 
				name="Submit" 
				 
				onClick="return mod_mailup_helper.checkEmail(document.id('mailup_email').value, document.id('mailup_name').value);" 
				value="<?php echo JText::_('MOD_MAILUP SUBSCRIBE BUTTON'); ?>" 
				/>
			<input type="hidden" name="option" value="com_mailup" />
			<input type="hidden" name="task" value="mailup" />
			<input type="hidden" name="frontend" value="1" />

		<?php else : ?>
			<input class="button subbutton" type="submit" name="Submit" value="<?php echo JText::_('MOD_MAILUP SUBSCRIBE BUTTON'); ?>" />
			<input type="hidden" name="option" value="com_mailup" />
			<input type="hidden" name="task" value="mailup" />
			<input type="hidden" name="frontend" value="1" />


		<?php endif; ?>
		
		</div>
	</form>
</div>
