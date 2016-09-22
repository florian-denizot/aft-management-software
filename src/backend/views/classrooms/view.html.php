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
 * Classroom list view class
 */
class AFTMSViewClassrooms extends DradAdminViewList
{

  protected function addSubmenu()
  {
    AFTMSHelper::addSubmenu('classrooms');
  }	
  
  /**
	 * Returns an array of fields the table can be sorted by
	 *
	 * @return  array  Array containing the field name to sort by as the key and display text as value
	 */
	protected function getSortFields()
	{
    $sortFields = parent::getSortFields();
    
    $sortFields ['campus_name']          = JText::_('COM_AFTMS_HEADING_CAMPUS');
    
    return $sortFields;
	}
}
