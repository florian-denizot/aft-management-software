<?php
defined('_JEXEC') or die;

JHtml::_('bootstrap.tooltip');
JHtml::_('behavior.keepalive');

$defaultCourseImage= JUri::base() . '/media/com_aftms/images/default_course.png';
?>

<?php foreach ($this->items as $i => $item) : ?>
  <?php if(!isset($item->image)): ?>
    <?php $item->image = $defaultCourseImage; ?>
  <?php endif; ?>

  <div class="row" id="course_group_<?php echo $item->id; ?>">
    
    <div class="col-md-1">
      <a href="<?php echo JRoute::_('index.php?option=com_aftms&view=coursegroup&id='. $item->id); ?>">
        <img src="<?php echo $item->image; ?>" alt="<?php echo $this->escape($item->title); ?>" class="img-responsive">
      </a>
    </div>
    
    <div class="col-md-6">
      <a href="<?php echo JRoute::_('index.php?option=com_aftms&view=coursegroup&id='. $item->id); ?>">
        <strong><?php echo $item->title; ?></strong>
      </a>
    </div>
    
    <div class="col-md-5">
      <?php echo $item->category_title ?></small>
    </div>
  </div>
<?php endforeach; ?>

<div class="clearfix"></div>

<div class="text-center"><?php echo $this->pagination->getPagesLinks(); ?></div>