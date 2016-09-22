<?php
/**
 * @package     Joomla.Administrator
 * @subpackage  com_aftms
 */

defined('_JEXEC') or die;

// Include the component HTML helpers.
JHtml::addIncludePath(JPATH_COMPONENT . '/helpers/html');

JHtml::_('behavior.formvalidation');
JHtml::_('behavior.keepalive');
JHtml::_('formbehavior.chosen', 'select');
JHtml::_('bootstrap.tooltip');
JHtml::_('behavior.multiselect');

$app = JFactory::getApplication();
$input = $app->input;


?>

<form action="<?php echo JRoute::_('index.php?option=com_aftms&view=importcourses&layout=result'); ?>" method="post" name="adminForm" id="adminForm" class="form-validate" enctype="multipart/form-data">
	
  <?php if (!empty( $this->sidebar)) : ?>
    <div id="j-sidebar-container" class="span2">
      <?php echo $this->sidebar; ?>
    </div>
    <div id="j-main-container" class="span10">
  <?php else : ?>
    <div id="j-main-container">
  <?php endif;?>
    <div class="form-horizontal">
      <div class="row-fluid">
        <div class="span12">
          <fieldset>
             <div class="control-group">
              <div class="control-label"> <?php echo $this->form->getLabel('file'); ?></div>
              <div class="controls"> <?php echo $this->form->getInput('file'); ?></div>
            </div>
            
            <div class="control-group">
              <div class="control-label"> <?php echo $this->form->getLabel('update'); ?></div>
              <div class="controls"> <?php echo $this->form->getInput('update'); ?></div>
            </div>
            
            <?php echo $this->form->getLabel('heading'); ?>
            
            <div class="control-group">
              <div class="control-label"> <?php echo $this->form->getLabel('sessionid'); ?></div>
              <div class="controls"> <?php echo $this->form->getInput('sessionid'); ?></div>
            </div>
            <div class="control-group">
              <div class="control-label"> <?php echo $this->form->getLabel('classroomid'); ?></div>
              <div class="controls"> <?php echo $this->form->getInput('classroomid'); ?></div>
            </div>
            
           </fieldset>
          </div>
        </div>

      <input type="hidden" name="option" value="com_aftms" />
			<input type="hidden" name="task" value="" />
      <?php echo JHtml::_('form.token'); ?>
    </div>
  </div>
</form>
