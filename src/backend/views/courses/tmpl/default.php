<?php
/**
 * @package     Joomla.Administrator
 * @subpackage  com_aftms
 *
 * @copyright   Copyright (C) 2016 Alliance Francaise Toronto. All rights reserved.
 * @license     LTBD
 */

defined('_JEXEC') or die;

$listOrder = $this->escape($this->state->get('list.ordering'));
$listDirn  = $this->escape($this->state->get('list.direction'));

echo JLayoutHelper::render('page_start', array('view' => $this) ,JPATH_ROOT .'/libraries/drad/admin/layouts/list');
echo JLayoutHelper::render('form_start', array('view' => $this) ,JPATH_ROOT .'/libraries/drad/admin/layouts/list');

  echo JLayoutHelper::render('sidebar', array('view' => $this) ,JPATH_ROOT .'/libraries/drad/admin/layouts/list');
  echo JLayoutHelper::render('main_container_start', array('view' => $this) ,JPATH_ROOT .'/libraries/drad/admin/layouts/list');

    // Search tools bar
    echo JLayoutHelper::render('joomla.searchtools.default', array('view' => $this));
    ?>

    <?php if (empty($this->items)) : ?>
      <div class="alert alert-no-items">
        <?php echo JText::_('JGLOBAL_NO_MATCHING_RESULTS'); ?>
      </div>
    <?php else : ?>
      <table class="table table-striped" id="itemList">
        <thead>
          <tr>
            <?php echo JLayoutHelper::render('table_header_ordering', array('view' => $this) ,JPATH_ROOT .'/libraries/drad/admin/layouts/list'); ?>
            <?php echo JLayoutHelper::render('table_header_checkbox', null ,JPATH_ROOT .'/libraries/drad/admin/layouts/list'); ?>
            <?php echo JLayoutHelper::render('table_header_status', array('view' => $this) ,JPATH_ROOT .'/libraries/drad/admin/layouts/list'); ?>
            <?php echo JLayoutHelper::render('table_header_title', array('view' => $this) ,JPATH_ROOT .'/libraries/drad/admin/layouts/list'); ?>
             <th width="10%" class="nowrap hidden-phone">
              <?php echo JHtml::_('searchtools.sort', 'COM_AFTMS_HEADING_CAMPUS', 'campus_name', $listDirn, $listOrder); ?>
            </th>
            <?php echo JLayoutHelper::render('table_header_access', array('view' => $this) ,JPATH_ROOT .'/libraries/drad/admin/layouts/list'); ?>
            <?php echo JLayoutHelper::render('table_header_created_by', array('view' => $this) ,JPATH_ROOT .'/libraries/drad/admin/layouts/list'); ?>
            <?php echo JLayoutHelper::render('table_header_id', array('view' => $this) ,JPATH_ROOT .'/libraries/drad/admin/layouts/list'); ?>
          </tr>
        </thead>
        <tbody>

          <?php foreach ($this->items as $i => $item) : ?>

            <tr class="row<?php echo $i % 2; ?>" sortable-group-id="<?php echo $item->id; ?>">
              <?php echo JLayoutHelper::render('table_cell_ordering', array('view' => $this, 'item' => $item) ,JPATH_ROOT .'/libraries/drad/admin/layouts/list'); ?>
              <?php echo JLayoutHelper::render('table_cell_checkbox', array('i' => $i, 'item' => $item) ,JPATH_ROOT .'/libraries/drad/admin/layouts/list'); ?>
              <?php echo JLayoutHelper::render('table_cell_status', array('view' => $this, 'i' => $i, 'item' => $item) ,JPATH_ROOT .'/libraries/drad/admin/layouts/list'); ?>
              <?php echo JLayoutHelper::render('table_cell_title', array('view' => $this, 'i' => $i, 'item' => $item) ,JPATH_ROOT .'/libraries/drad/admin/layouts/list'); ?>
              <td class="small hidden-phone">
                <?php echo $this->escape($item->campus_name); ?>
              </td>
              <?php echo JLayoutHelper::render('table_cell_access', array('view' => $this, 'item' => $item) ,JPATH_ROOT .'/libraries/drad/admin/layouts/list'); ?>
              <?php echo JLayoutHelper::render('table_cell_created_by', array('view' => $this, 'item' => $item) ,JPATH_ROOT .'/libraries/drad/admin/layouts/list'); ?>
              <?php echo JLayoutHelper::render('table_cell_id', array('item' => $item) ,JPATH_ROOT .'/libraries/drad/admin/layouts/list'); ?>
            </tr>

          <?php endforeach; ?>

        </tbody>
      </table>

      <?php echo $this->loadTemplate('batch'); ?>

      <?php echo $this->pagination->getListFooter(); ?>
      
    <?php endif; ?>

  <?php echo JLayoutHelper::render('main_container_end', null ,JPATH_ROOT .'/libraries/drad/admin/layouts/list'); ?>
<?php echo JLayoutHelper::render('form_end', null ,JPATH_ROOT .'/libraries/drad/admin/layouts/list'); ?>