<?php
/**
 * @package     Joomla.Administrator
 * @subpackage  com_aftms
 *
 * @copyright   Copyright (C) 2016 Alliance Francaise Toronto. All rights reserved.
 * @license     LTBD
 */

defined('_JEXEC') or die;

?>
<button class="btn" type="button" onclick="document.getElementById('batch-access').value='';document.getElementById('batch-user-id').value='';" data-dismiss="modal">
	<?php echo JText::_('JCANCEL'); ?>
</button>
<button class="btn btn-success" type="submit" onclick="Joomla.submitbutton('coursegroup.batch');">
	<?php echo JText::_('JGLOBAL_BATCH_PROCESS'); ?>
</button>