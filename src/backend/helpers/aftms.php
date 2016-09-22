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
 * AFTMS component helper.
 *
 * @package     Joomla.Administrator
 * @subpackage  com_aftms
 */
class AFTMSHelper extends JHelperContent
{
	public static $extension = 'com_aftms';
  
  /**
	 * @var    JObject  A cache for the available actions.
	 */
	protected static $actions;

	/**
	 * Configure the Linkbar.
	 *
	 * @param   string  $vName  The name of the active view.
	 *
	 * @return  void
	 */
	public static function addSubmenu($vName = 'coursegroups')
	{
    JHtmlSidebar::addEntry(
			JText::_('COM_AFTMS_SUBMENU_COURSES'),
			'index.php?option=com_aftms&view=courses',
			$vName == 'courses'
    );
		JHtmlSidebar::addEntry(
			JText::_('COM_AFTMS_SUBMENU_COURSEGROUPS'),
			'index.php?option=com_aftms&view=coursegroups',
			$vName == 'coursegroups'
		);
    JHtmlSidebar::addEntry(
			JText::_('COM_AFTMS_SUBMENU_COURSETYPES'),
			'index.php?option=com_categories&extension=com_aftms.coursetypes',
			$vName == 'categories.coursetypes'
    );
		JHtmlSidebar::addEntry(
			JText::_('COM_AFTMS_SUBMENU_COURSECATEGORIES'),
			'index.php?option=com_categories&extension=com_aftms.coursecategories',
			$vName == 'categories.coursecategories'
    );
    JHtmlSidebar::addEntry(
			JText::_('COM_AFTMS_SUBMENU_SESSIONS'),
			'index.php?option=com_categories&extension=com_aftms.sessions',
			$vName == 'categories.sessions'
    );
    JHtmlSidebar::addEntry(
			JText::_('COM_AFTMS_SUBMENU_LEVELS'),
			'index.php?option=com_categories&extension=com_aftms.levels',
			$vName == 'categories.levels'
    );
    JHtmlSidebar::addEntry(
			JText::_('COM_AFTMS_SUBMENU_CENTRES'),
			'index.php?option=com_aftms&view=campuses',
			$vName == 'campuses'
    );
    JHtmlSidebar::addEntry(
			JText::_('COM_AFTMS_SUBMENU_CLASSROOMS'),
			'index.php?option=com_aftms&view=classrooms',
			$vName == 'classrooms'
    );
    JHtmlSidebar::addEntry(
			JText::_('COM_AFTMS_SUBMENU_IMPORT_COURSES'),
			'index.php?option=com_aftms&view=importcourses',
			$vName == 'importcourses'
    );
	}

	/**
	 * Applies the aftms tag filters to arbitrary text as per settings for current user group
	 *
	 * @param   text  $text  The string to filter
	 *
	 * @return  string  The filtered string
	 *
	 * @deprecated  4.0  Use JComponentHelper::filterText() instead.
	*/
	public static function filterText($text)
	{
		JLog::add('AFTMSHelper::filterText() is deprecated. Use JComponentHelper::filterText() instead.', JLog::WARNING, 'deprecated');

		return JComponentHelper::filterText($text);
	}
  
  /**
	 * Get centre list in text/value format for a select field
	 *
	 * @return  array
	 */
	public static function getCampusOptions($lang = '')
	{ 
		$options = array();

		$db = JFactory::getDbo();
		$query = $db->getQuery(true)
			->select('id As value, title As text')
			->from('#__aftms_campuses AS a')
			->order('a.title');
      
    if($lang)
    {
      $query->where('a.language = "'.$lang.'"');
    }

		// Get the options.
		$db->setQuery($query);

		try
		{
			$options = $db->loadObjectList();
		}
		catch (RuntimeException $e)
		{
			JError::raiseWarning(500, $e->getMessage());
		}

		array_unshift($options, JHtml::_('select.option', '', JText::_('COM_AFTMS_OPTION_SELECT_CAMPUS')));

		return $options;
	}
  
  
  /**
	 * Get classroom list in text/value format for a select field
	 *
	 * @return  array
	 */
	public static function getClassroomOptions($lang = '', $campus = '')
	{
		$db = JFactory::getDbo();
		$query = $db->getQuery(true)
			->select('a.id as value, a.title as text, b.title as label, b.id as groupid')
			->from('#__aftms_classrooms AS a')   
      ->innerjoin('#__aftms_campuses as b ON b.id = a.campusid')
			->order('b.ordering ASC, a.ordering ASC')
      ->where('a.published = 1');
      
    if($lang)
    {
      $query->where('a.language = "'.$lang.'"');
    }
    
    if($campus)
    {
      $query->where('a.campusid = "'.$campus.'"');
    }

		// Get the options.
		$db->setQuery($query);

		try
		{
			$results = $db->loadObjectList();
		}
		catch (RuntimeException $e)
		{
			JError::raiseWarning(500, $e->getMessage());
		}
    
    
    $options = array();
    
    foreach($results as $row)
    {
      $optionClass = array('class' => $row->groupid);
      $optionsAttrs = array(
          'attr' => $optionClass, 
          'option.attr' => 'optionAttributes'
        );
      $options[] = JHtml::_('select.option', $row->value, $row->text, $optionsAttrs);
    }
    
		array_unshift($options, JHtml::_('select.option', '', JText::_('COM_AFTMS_OPTION_SELECT_CLASSROOM')));
    
		return $options;
	} 
}
