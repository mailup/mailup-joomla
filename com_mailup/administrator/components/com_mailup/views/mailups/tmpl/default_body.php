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
			<?php echo JHtml::link('index.php?option=com_mailup&task=mailup.edit&id='.$item->id, $item->name); ?>
		</td>
		<td>
			<?php echo $item->email; ?>
		</td>
		<td style="text-align: center;">
			<?php echo ($item->joomla_user ? '<a title="" class="jgrid"><span class="state publish"></span></a>' : '<a title="" class="jgrid"><span class="state unpublish"></span></a>'); ?>
		</td>
		<td style="text-align: center;">
			<?php echo $item->create_date; ?>
		</td>
	</tr>
<?php endforeach; ?>
