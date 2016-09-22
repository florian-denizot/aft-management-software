<?php 
/**
 * @package     Joomla.Administrator
 * @subpackage  com_aftms
 *
 * @copyright   Copyright (C) 2016 Alliance Francaise Toronto. All rights reserved.
 * @license     LTBD
 */

defined('_JEXEC') or die;

$app = JFactory::getApplication();
$function  = $app->input->getCmd('function', 'jSelectArticle');

echo JLayoutHelper::render('page_start', array('view' => $this) ,JPATH_ROOT .'/libraries/drad/admin/layouts/list/modal');
echo JLayoutHelper::render('form_start', array('view' => $this) ,JPATH_ROOT .'/libraries/drad/admin/layouts/list/modal');
  
  // Search tools bar
  echo JLayoutHelper::render('joomla.searchtools.default', array('view' => $this));
  
  if (empty($this->items)) : ?>
    <div class="alert alert-no-items">
     <?php echo JText::_('JGLOBAL_NO_MATCHING_RESULTS'); ?>
    </div>
  <?php else : ?>
    <table class="table table-striped" id="itemList">
      <thead>
        <tr>
          <?php echo JLayoutHelper::render('table_header_title', array('view' => $this) ,JPATH_ROOT .'/libraries/drad/admin/layouts/list'); ?>
          <?php echo JLayoutHelper::render('table_header_language', array('view' => $this) ,JPATH_ROOT .'/libraries/drad/admin/layouts/list'); ?>
          <?php echo JLayoutHelper::render('table_header_id', array('view' => $this) ,JPATH_ROOT .'/libraries/drad/admin/layouts/list'); ?>
        </tr>
      </thead>
      <tbody>
        <?php foreach ($this->items as $i => $item) : ?>
          <?php if ($item->language && JLanguageMultilang::isEnabled())
          {
            $tag = strlen($item->language);
            if ($tag == 5)
            {
              $lang = substr($item->language, 0, 2);
            }
            elseif ($tag == 6)
            {
              $lang = substr($item->language, 0, 3);
            }
            else {
              $lang = "";
            }
          }
          elseif (!JLanguageMultilang::isEnabled())
          {
            $lang = "";
          }
          
          ?> 
          <tr class="row<?php echo $i % 2; ?>" sortable-group-id="<?php echo $item->id; ?>">
            <td>
              <a href="javascript:void(0)" onclick="if (window.parent) window.parent.<?php echo $this->escape($function);?>('<?php echo $item->id; ?>', '<?php echo $this->escape(addslashes($item->title)); ?>');">
                <?php echo $this->escape($item->title); ?>
              </a>
            </td>
            <?php echo JLayoutHelper::render('table_cell_language', array('view' => $this,  'item' => $item) ,JPATH_ROOT .'/libraries/drad/admin/layouts/list'); ?>
            <?php echo JLayoutHelper::render('table_cell_id', array('item' => $item) ,JPATH_ROOT .'/libraries/drad/admin/layouts/list'); ?>
          </tr>

        <?php endforeach; ?>

      </tbody>
    </table>

    <?php echo $this->pagination->getListFooter(); ?>
  <?php endif; ?>
  
<?php echo JLayoutHelper::render('form_end', array('view' => $this) ,JPATH_ROOT .'/libraries/drad/admin/layouts/list');