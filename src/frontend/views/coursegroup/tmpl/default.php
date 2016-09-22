<?php
/**
 * @package     Joomla.Site
 * @subpackage  com_aftms
 */

defined('_JEXEC') or die;

JHtml::_('bootstrap.tooltip');
JHtml::_('behavior.keepalive');

$coursegroup = $this->item;
?>

<div class="aftms-coursegroup">

  <div class="page-header">
    <h1><?php echo $this->escape($coursegroup->title); ?> </h1>
  </div>

  <?php if(isset($coursegroup->image)): ?>
    <img src="<?php echo $coursegroup->image; ?>" alt="<?php echo $this->escape($coursegroup->title); ?>" class="coursegroup-image"/>
  <?php endif; ?>
  
  <div class="coursegroup-description">
    <?php echo $coursegroup->description; ?>
  </div>
  
  <div class="coursegroup-courses">
    <h3><?php echo JText::_('COM_AFTMS_DATES_AND_TIMES'); ?></h3>
    <?php echo $this->loadTemplate('courses'); ?>
  </div>
  
</div>