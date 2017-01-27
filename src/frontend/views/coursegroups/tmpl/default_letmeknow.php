<?php
/**
 * @package     Joomla.Site
 * @subpackage  com_aftms
 *
 * @copyright   Copyright (C) 2016 Alliance Francaise Toronto. All rights reserved.
 * @license     LTBD
 */

defined('_JEXEC') or die;

//var_dump($displayData);

$campusList = $this->campusList;
?>

<form class="form-horizontal" id="letmeknowform">
  <div id="letMeKnowModal" class="modal fade" role="dialog" aria-labelledby="<?php echo JText::_('COM_AFTMS_LET_ME_KNOW'); ?>" aria-hidden="true">
    <div class="modal-dialog modal-lg">
      <div class="modal-content">
        <div class="modal-header">
           <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
          <h4 class="modal-title" id="myModalLabel"><?php echo JText::_('COM_AFTMS_LET_ME_KNOW'); ?></h4>
        </div>
        <div class="modal-body">

          <div class="alert alert-danger hidden" id="error-message"></div>
          <div class="alert alert-success hidden" id="success-message"></div>

          <div class="form-group">
            <label class="control-label col-sm-3" for="name"><?php echo JText::_('COM_AFTMS_COURSE_NAME'); ?></label>
            <div class="col-sm-9">
              <input type="text" id="course_name" name="course_name" class="form-control" readonly>
            </div>
          </div>
          <div class="form-group">
            <label class="control-label col-sm-3" for="campus"><?php echo JText::_('COM_AFTMS_CHOOSEN_CAMPUS'); ?></label>
            <div class="col-sm-9">
              <select id="campus" name="campus" class="form-control" required>
                <option value="1">Toronto Downtown</option>
                <option value="2">North York</option>
                <option value="3">Mississauga</option>
                <option value="4">Markham</option>
                <option value="5">Oakville</option>
              </select>
            </div>
          </div>
          <div class="form-group">
            <label class="control-label col-sm-3" for="name"><?php echo JText::_('COM_AFTMS_NAME'); ?></label>
            <div class="col-sm-9">
              <input type="text" id="name" name="name" class="form-control" required>
            </div>
          </div>
          <div class="form-group">
            <label class="control-label col-sm-3" for="email"><?php echo JText::_('COM_AFTMS_EMAIL'); ?></label>
            <div class="col-sm-9">
              <input type="email" id="email" name="email" class="form-control" required>
            </div>
          </div>
          <div class="form-group">
            <label class="control-label col-sm-3" for="phone"><?php echo JText::_('COM_AFTMS_PHONE'); ?></label>
            <div class="col-sm-9">
              <input type="tel" id="phone" name="phone" class="form-control">
            </div>
          </div>
          <div class="form-group">
            <label class="control-label col-sm-3" for="comment"><?php echo JText::_('COM_AFTMS_COMMENTS'); ?></label>
            <div class="col-sm-9">
              <textarea id="comment" name="comment" rows="5" class="form-control"></textarea>
            </div>
          </div>

          <div class="form-group">
            <div class="col-sm-9 col-sm-offset-3">
              <div id="recaptcha"></div>
            </div>
          </div>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-default" data-dismiss="modal"><?php echo JText::_('COM_AFTMS_CLOSE'); ?></button>
          <input type="submit" class="btn btn-primary" id="sendData" value="<?php echo JText::_('COM_AFTMS_SEND'); ?>" data-loading-text="<?php echo JText::_('COM_AFTMS_LOADING'); ?>">
        </div>
      </div>
    </div>
  </div>
</form>
