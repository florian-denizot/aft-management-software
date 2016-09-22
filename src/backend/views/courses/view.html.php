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
 * Course list view class
 */
class AFTMSViewCourses extends DradAdminViewList
{

  protected function addSubmenu()
  {
    AFTMSHelper::addSubmenu('courses');
  }	
  
  /**
	 * Returns an array of fields the table can be sorted by
	 *
	 * @return  array  Array containing the field name to sort by as the key and display text as value
	 */
	protected function getSortFields()
	{
    $sortFields = parent::getSortFields();
    
    $sortFields ['t.id']          = JText::_('COM_AFTMS_HEADING_TYPE');
    $sortFields ['campus_name']          = JText::_('COM_AFTMS_HEADING_CAMPUS');
    $sortFields ['s.id' ]         = JText::_('COM_AFTMS_HEADING_SESSION');
    $sortFields ['a.start_date']  = JText::_('COM_AFTMS_HEADING_DATES');
    
    return $sortFields;
	}
}