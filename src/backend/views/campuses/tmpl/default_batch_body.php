<?php
/**
 * @package     Joomla.Administrator
 * @subpackage  com_aftms
 *
 * @copyright   Copyright (C) 2016 Alliance Francaise Toronto. All rights reserved.
 * @license     LTBD
 */

defined('_JEXEC') or die;

$published = $this->state->get('filter.published');
?>

<p><?php echo JText::_('COM_AFTMS_COURSES_BATCH_TIP'); ?></p>
<div class="row-fluid">
  <div class="control-group span6">
		<div class="controls">
			<?php echo JHtml::_('batch.language'); ?>
		</div>
	</div>
	<div class="control-group span6">
		<div class="controls">
			<?php echo JHtml::_('batch.access'); ?>
		</div>
	</div>
</div>


