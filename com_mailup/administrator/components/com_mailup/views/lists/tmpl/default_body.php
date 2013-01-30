<?php
// No direct access to this file
defined('_JEXEC') or die('Restricted Access');
?>
<?php foreach($this->items as $i => $item): ?>
	<tr class="row<?php echo $i % 2; ?>">
		<td>
			<?php echo $item->id; ?>
		</td>
		<td>
			<?php echo JHtml::_('grid.id', $i, $item->id); ?>
		</td>
		<td>
			<?php echo JHtml::link('index.php?option=com_mailup&task=list.edit&id='.$item->id, $item->alias); ?>
		</td>
		<td>
			<?php echo $item->name; ?>
		</td>
		<td>
			<?php echo $item->description; ?>
		</td>
		<td style="text-align: center;">
			<?php echo ($item->visible ? '<a title="" class="jgrid"><span class="state publish"></span></a>' : '<a title="" class="jgrid"><span class="state unpublish"></span></a>'); ?>
		</td>
		<!--
		<td>
			<?php //echo $item->listid; ?>
		</td>
		<td>
			<?php //echo $item->guid; ?>
		</td>
		<td>
			<?php //echo $item->create_date; ?>
		</td>
		-->
		<td style="text-align: center;">
			<?php echo $item->update_date; ?>
		</td>
	</tr>
<?php endforeach; ?>
