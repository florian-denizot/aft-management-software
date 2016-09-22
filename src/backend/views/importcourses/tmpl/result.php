<?php
/**
 * @package     Joomla.Administrator
 * @subpackage  com_aftms
 */

defined('_JEXEC') or die;

JHtml::_('behavior.keepalive');
JHTML::_('bootstrap.framework');

JHtml::script('com_aftms/importcourses.js', false, true);
JHtml::stylesheet('com_aftms/importcourses.css', array(), true);

JText::script('COM_AFTMS_IMPORT_COURSES_IMPORT_FAILED');
JText::script('COM_AFTMS_IMPORT_COURSES_IMPORT_FINISHED');
JText::script('COM_AFTMS_IMPORT_COURSES_NO_COURSE_IMPORTED');
JText::script('COM_AFTMS_IMPORT_COURSES_X_COURSE_IMPORTED');
JText::script('COM_AFTMS_IMPORT_COURSES_X_COURSE_ERROR');
JText::script('COM_AFTMS_IMPORT_COURSES_GOT_IT');


$app = JFactory::getApplication();
$input = $app->input;

?>
	
<div id="j-main-container">
  <div class="form-horizontal">
    <div class="row-fluid">
      <div class="span12">

        <input type="hidden" name="course_nb" id="course_nb" value="<?php echo $this->data['course_nb']; ?>"/>
        <input type="hidden" name="file" id="file" value="<?php echo $this->data['file']; ?>"/>


        <div class="alert alert-info" id="current-status">
          <?php echo JText::sprintf('COM_AFTMS_IMPORT_COURSES_COURSE_NB_TO_IMPORT', $this->data['course_nb'], floor($this->data['course_nb'] * 15 / 60));?>
        </div>


        <h4><?php echo JText::_('COM_AFTMS_IMPORT_COURSE_IMPORTATION_PROGRESS'); ?></h4>
        <div class="progress progress-striped active">
          <div class="bar bar-success" style="width: 0%;"></div>
          <div class="bar bar-warning" style="width: 0%;"></div>
          <div class="bar bar-danger" style="width: 0%;"></div>
        </div>

        <div id="messages"></div>
      </div>

    </div>
  </div>
</div>

