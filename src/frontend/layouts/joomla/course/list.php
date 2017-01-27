<?php
/**
 * @package     Joomla.Site
 * @subpackage  Layout
 *
 * @copyright   Copyright (C) 2005 - 2014 Open Source Matters, Inc. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */

defined('JPATH_BASE') or die;

$courses = $displayData;
?>
<div class="course-list">
  <?php if(count($courses)): ?>
    <table class="table table-striped course-list table-sorter">
      <thead>
        <tr>
          <th class="sorter-date"><?php echo JText::_('COM_AFTMS_HEADING_DATES_TIMES'); ?></th>
          <th><?php echo JText::_('COM_AFTMS_HEADING_CAMPUS'); ?></th>
          <th width="65px"><?php echo JText::_('COM_AFTMS_HEADING_PRICE'); ?></th>
          <th width="70px" data-sorter="false"></th>
        </tr>
      </thead>
      <tbody>
    <?php foreach($courses as $course): ?>
      <tr>
        <td>
          <div>
            <?php echo $course->start_date->format(JText::_('COM_AFTMS_DATE_FORMAT_1')) 
            . ' ' . JText::_('COM_AFTMS_TO2') . ' ' 
            . $course->end_date->format(JText::_('COM_AFTMS_DATE_FORMAT_1')); ?>
          </div>
          <div><?php echo $course->days_times; ?></div>
        </td>
        <td><?php echo $course->campus_name; ?></td>
        <td><?php echo $course->price_override; ?></td>
        <td><a href="#" class="btn btn-primary">Register</a></td>
      </tr>
    <?php endforeach;?>
      </tbody>
    </table>
  <?php endif; ?>
</div>