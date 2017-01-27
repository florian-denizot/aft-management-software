<?php
defined('_JEXEC') or die;

JHtml::_('bootstrap.tooltip');

$defaultCourseImage= JUri::base() . '/media/com_aftms/images/default_course.png';
?>
<div class="row-fluid">
  <div class="span5 offset1">
    <strong><?php echo JText::_('COM_AFTMS_COURSES'); ?></strong>
  </div>
   <div class="span2">
    <strong><?php echo JText::_('COM_AFTMS_HEADING_AGE_RANGE'); ?></strong>
  </div>
  <div class="span2">
    <strong><?php echo JText::_('COM_AFTMS_CATEGORY'); ?></strong>
  </div>
  <div class="span2">
    <strong><?php echo JText::_('COM_AFTMS_LEVEL'); ?></strong>
  </div>
</div>
<?php foreach ($this->items as $i => $item) : ?>
  <?php if(!isset($item->image)): ?>
    <?php $item->image = $defaultCourseImage; ?>
  <?php endif; ?>

  <div class="row-fluid" id="course_group_<?php echo $item->id; ?>">
    
    <div class="span1">
      <a href="<?php echo JRoute::_('index.php?option=com_aftms&view=coursegroup&id='. $item->id); ?>">
        <img src="<?php echo $item->image; ?>" alt="<?php echo $this->escape($item->title); ?>" class="img-responsive">
      </a>
    </div>
    
    <div class="span5">
      <a href="<?php echo JRoute::_('index.php?option=com_aftms&view=coursegroup&id='. $item->id); ?>">
        <strong><?php echo $item->title; ?></strong>
      </a>
    </div>
    <div class="span2">
      <?php echo AFTMSHelper::getAgeRangeLabel($item->min_month, $item->min_year, $item->max_month, $item->max_year); ?></small>
    </div>
    <div class="span2">
      <?php echo $item->category_title; ?></small>
    </div>
    <div class="span2">
      <?php echo $item->simple_lvl_text; ?></small>
    </div>
  </div>
  <?php echo JLayoutHelper::render('joomla.course.list', $item->courses); ?>
<?php endforeach; ?>

<div class="clearfix"></div>

<div class="text-center"><?php echo $this->pagination->getPagesLinks(); ?></div>