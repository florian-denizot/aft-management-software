<?php
/**
 * @package     Joomla.Site
 * @subpackage  com_aftms
 *
 * @copyright   Copyright (C) 2016 Alliance Francaise Toronto. All rights reserved.
 * @license     LTBD
 */

defined('_JEXEC') or die;

JHtml::_('bootstrap.tooltip');
JHtml::_('behavior.keepalive');
JHtml::_('behavior.multiselect');

JHtml::script('com_aftms/jquery.tablesorter.min.js', false, true);
JHtml::script('com_aftms/courses.js', false, true);
JHtml::stylesheet('com_aftms/courses.css', false, true);

JPluginHelper::importPlugin('captcha');
$dispatcher = JDispatcher::getInstance();
$dispatcher->trigger('onInit','recaptcha');

JHtml::addIncludePath(JPATH_COMPONENT.'/helpers');

$filterFields = $this->filterForm->getFieldset('filter');
?>
<div class="coursegroups<?php echo $this->pageclass_sfx; ?>">
	<?php if ($this->params->get('show_page_heading')) : ?>
    <div class="page-header">
      <h1>
        <?php echo $this->escape($this->params->get('page_heading')); ?>
      </h1>
    </div>
  <?php endif; ?>
  
  <form method="post" action="<?php echo JRoute::_(AFTMSHelperRoute::getCourseGroupsRoute()); ?>" method="post" name="adminForm" id="adminForm">
    <fieldset class="form">
      <legend><?php echo JText::_('COM_AFTMS_FILTER_COURSES_BY'); ?></legend>
      <?php $filters = $this->filterForm->getGroup('filter'); ?>
      
      <?php if ($filters): ?>
        <?php foreach ($filters as $fieldName => $field) : ?>
          <?php if ($fieldName != 'filter_search') : ?>
            <?php echo $field->label; ?>
            <?php echo $field->input; ?>
          <?php endif; ?>
        <?php endforeach; ?>
      <?php endif; ?>
      
      <div class="controls">
        <input type="submit" class="btn btn-primary" name="submit" value="<?php echo JText::_('COM_AFTMS_SEARCH'); ?>"/>
      </div>
    </fieldset>
    <input type="hidden" name="task" value="" />
    <?php echo JHtml::_('form.token'); ?>
  </form>
  
  <!-- display a custom module position : aftms-coursegroups-sidebar -->
  <div>
    <?php JPluginHelper::importPlugin('content'); ?>
    <?php echo JHtml::_('content.prepare', '{loadposition aftms-coursegroups-sidebar}', '', 'mod_custom.content'); ?>
  </div>
  
  <div>
    <?php if (empty($this->items)) : ?>
      <div class="alert alert-info">
        <?php echo JText::_('JGLOBAL_SELECT_NO_RESULTS_MATCH'); ?>
      </div>
    <?php else : ?>
      <?php echo $this->loadTemplate('coursegroups'); ?>
      <?php echo JLayoutHelper::render('joomla.form.letmeknow', $this); ?>
    <?php endif ?>
  </div>
</div>