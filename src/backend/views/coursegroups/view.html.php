<?php
/**
 * @package     Joomla.Administrator
 * @subpackage  com_aftms
 *
 * @copyright   Copyright (C) 2016 Alliance Francaise Toronto. All rights reserved.
 * @license     LTBD
 */

defined('_JEXEC') or die;

/**
 * Coursegroup list view class
 */
class AFTMSViewCoursegroups extends DradAdminViewList
{
  
  protected function addSubmenu()
  {
    AFTMSHelper::addSubmenu('coursegroups');
  }	
  
  /**
	 * Returns an array of fields the table can be sorted by
	 *
	 * @return  array  Array containing the field name to sort by as the key and display text as value
	 */
	protected function getSortFields()
	{
    $sortFields = parent::getSortFields();
    
    $sortFields ['category_title']    = JText::_('JCATEGORY');
    $sortFields ['type_title']        = JText::_('COM_AFTMS_TYPE');
    $sortFields ['min_level_title' ]  = JText::_('COM_AFTMS_MIN_LEVEL');
    $sortFields ['max_level_title']   = JText::_('COM_AFTMS_MAX_LEVEL');
    $sortFields ['a.featured']        = JText::_('JFEATURED');
    
    return $sortFields;
	}
  
}
