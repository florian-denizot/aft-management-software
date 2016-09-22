<?php
/**
 * @package     Joomla.Site
 * @subpackage  com_weblinks
 *
 * @copyright   Copyright (C) 2005 - 2014 Open Source Matters, Inc. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */

defined('_JEXEC') or die;

require_once(JPATH_COMPONENT.DS.'lib'.DS.'views'.DS.'viewlist.php');

/**
 * HTML View class for the WebLinks component
 *
 * @package     Joomla.Site
 * @subpackage  com_weblinks
 * @since       1.5
 */
class AFTMSViewCourseGroups extends DefaultViewList
{
	protected $items;

	protected $pagination;

	protected $state;
  
  public $filterForm;
  
  protected $activeFilters;
  
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
    $state		      = $this->get('State');
    
    
    $this->min_year = $state->get('age_range.min_year', 0);
    $this->min_month = $state->get('age_range.min_month', 0);
    $this->max_year = $state->get('age_range.max_year', 0);
    $this->max_month = $state->get('age_range.max_month', 0);
  
    return parent::display($tpl);
  }
  
  
  /**
	 * Returns an array of fields the table can be sorted by
	 *
	 * @return  array  Array containing the field name to sort by as the key and display text as value
	 *
	 * @since   3.0
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
