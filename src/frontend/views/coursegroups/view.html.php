<?php
/**
 * @package     Joomla.Site
 * @subpackage  com_aftms
 *
 * @copyright   Copyright (C) 2016 Alliance Francaise Toronto. All rights reserved.
 * @license     LTBD
 */

defined('_JEXEC') or die;

/**
 * Course group list HTML View class
 */
class AFTMSViewCourseGroups extends DradSiteViewList
{
  protected $pageTitle = 'COM_AFTMS_COURSES_PAGE_TITLE';
  
  
  /**
	 * Execute and display a template script.
	 *
	 * @param   string  $tpl  The name of the template file to parse; automatically searches through the template paths.
	 *
	 * @return  mixed  A string if successful, otherwise a Error object.
	 */
  public function display($tpl = null)
	{  
    $this->campusList = array();
    
    return parent::display($tpl);
  }
  
  
  /**
	 * Returns an array of fields the table can be sorted by
	 *
	 * @return  array  Array containing the field name to sort by as the key and display text as value
	 */
	protected function getSortFields()
	{
		$sortFields = array(
			'a.ordering'     => JText::_('COM_AFTMS_HEADING_ORDERING'),
      'a.name'         => JText::_('COM_AFTMS_HEADING_NAME'),
      'cat.title'      => JText::_('COM_AFTMS_HEADING_CATEGORY'),
      'lvl.ordering'   => JText::_('COM_AFTMS_HEADING_LEVEL'),
      'c.title'        => JText::_('COM_AFTMS_HEADING_CAMPUS'),
		);
    
    return $sortFields;
	}
}
