<?php $courses = $this->item->courses; ?>

<?php if(isset($courses) && count($courses)): ?>

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
        <td data-date="<?php echo $course->start_date->format(JText::_('COM_AFTMS_DATE_FORMAT_2')); ?>">
          <div>
            <?php echo $course->start_date->format(JText::_('COM_AFTMS_DATE_FORMAT_1')) 
            . ' ' . JText::_('COM_AFTMS_TO2') . ' ' 
            . $course->end_date->format(JText::_('COM_AFTMS_DATE_FORMAT_1')); ?>
          </div>
          <div><?php echo $course->days_times; ?></div>
        </td>
        <td><?php echo $course->centre_name; ?></td>
        <td>$<?php echo $course->price_override; ?></td>
        <td class="text-right"><a href="<?php echo $course->url; ?>" class="btn btn-primary btn-sm" target="_blank"><?php echo JText::_('COM_AFTMS_REGISTER'); ?></a></td>
      </tr>
    <?php endforeach; ?>
    </tbody>
  </table>

<?php else: ?>

  <div class="alert alert-info"><?php echo JText::_('COM_AFTMS_NO_COURSE_MATCH'); ?></div>

<?php endif; ?>