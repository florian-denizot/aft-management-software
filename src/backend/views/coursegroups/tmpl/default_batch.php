<?php
/**
 * @package     Joomla.Administrator
 * @subpackage  com_aftms
 *
 * @copyright   Copyright (C) 2016 Alliance Francaise Toronto. All rights reserved.
 * @license     LTBD
 */

defined('_JEXEC') or die;

$user		= JFactory::getUser(); 
?>

<?php if ($user->authorise('core.create', 'com_aftms.coursegroup')
  && $user->authorise('core.edit', 'com_aftms.coursegroup')
  && $user->authorise('core.edit.state', 'com_aftms.coursegroup')) : ?>
  <?php echo JHtml::_(
    'bootstrap.renderModal',
    'collapseModal',
    array(
      'title' => JText::_('COM_AFTMS_COURSES_BATCH_OPTIONS'),
      'footer' => $this->loadTemplate('batch_footer')
    ),
    $this->loadTemplate('batch_body')
  ); ?>

<?php endif; ?>


