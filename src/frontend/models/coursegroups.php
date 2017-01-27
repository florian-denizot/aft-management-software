<?php
/**
 * @package     Joomla.Site
 * @subpackage  com_aftms
 *
 * @copyright   Copyright (C) 2016 Alliance Francaise Toronto. All rights reserved.
 * @license     LTBD
 */

defined('_JEXEC') or die;

JLoader::register('AFTMSHelper', JPATH_SITE . '/components/com_aftms/helpers/aftms.php');

/**
 * Coursegroup list model
 */
class AFTMSModelCoursegroups extends DradSiteModelList
{
  /*
   * The name of the config section to load in the drad config file.
   * 
   * @var   string 
   */
  protected $drad_element = 'coursegroup';
  
  /**
	 * Constructor.
	 *
	 * @param   array  $config  An optional associative array of configuration settings.
   *
	 * @see     JController
	 */
	public function __construct($config = array())
	{
		if (empty($config['filter_fields']))
		{
			$config['filter_fields'] = array(
        'r.campusid', 'campusid', 'c.id', 'campus_id',
        'a.catid', 'catid', 'category_id',
        'a.lvlid', 'lvlid', 'level_id',
        'b.sessionid', 'sessionid', 'session_id'
			);
		}
    
		parent::__construct($config);
	}

	/**
	 * Method to auto-populate the model state.
	 *
	 * Note. Calling getState in this method will result in recursion.
	 *
	 * @param   string  $ordering   An optional ordering field.
	 * @param   string  $direction  An optional direction (asc|desc).
	 *
	 * @return  void
	 */
	protected function populateState($ordering = 'a.title', $direction = 'asc')
	{
    $app = JFactory::getApplication();
		$input = $app->input;
    
    $filters = $input->get('filter', array(), 'array');
    
    // Hack to set the right values in the states 
    // when all checkboxes are empty
    if(!$filters)
    {
      $input->set(
        'filter', 
        array(
          'category_id' => '', 
          'level_id' => '',
          'campus_id'=> ''
        )
      );
    }
    
    $this->getUserStateFromRequest($this->context . '.filter', 'filter', array());
    
    $categoryId = $app->getUserStateFromRequest($this->context . '.filter.category_id', 'filter_category_id');
		$this->setState('filter.category_id', $categoryId);
    
    $level = $app->getUserStateFromRequest($this->context . '.filter.level', 'filter_level');
    $this->setState('filter.level', $level);
    
    $age = $app->getUserStateFromRequest($this->context . '.filter.age', 'filter_age');
    $this->setState('filter.age', $age);
    
    $campusId = $app->getUserStateFromRequest($this->context . '.filter.campus_id', 'filter_campus_id');
		$this->setState('filter.campus_id', $campusId);
    
    $frequency = $app->getUserStateFromRequest($this->context . '.filter.frequency', 'filter_frequency');
		$this->setState('filter.frequency', $frequency);
    
    $advancedlevelId = $app->getUserStateFromRequest($this->context . '.filter.advanced_level_id', 'filter_level_id');
		$this->setState('filter.advanced_level_id', $advancedlevelId);
    
		// List state information.
		parent::populateState($ordering, $direction);
	}
  
  /**
	 * Method to get a store id based on model configuration state.
	 *
	 * This is necessary because the model is used by the component and
	 * different modules that might need different sets of data or different
	 * ordering requirements.
	 *
	 * @param   string  $id  A prefix for the store id.
	 *
	 * @return  string  A store id.
	 */
	protected function getStoreId($id = '')
	{
		// Compile the store id.
		
    //$id .= ':' . $this->getState('filter.campus_id');
//		$id .= ':' . $this->getState('filter.category_id');
//		$id .= ':' . $this->getState('filter.level_id');
//    $id .= ':' . $this->getState('filter.frequency');
    
		return parent::getStoreId($id);
	}
  
  protected function getListQuery()
	{    
    $db = $this->getDbo();
    
    $query = parent::getListQuery();
    
    $query->select('a.typeid, a.catid, a.min_lvl, a.max_lvl, a.simple_lvl, a.featured, a.image_ext, a.min_year, a.min_month, a.max_year, a.max_month')
      ->where('a.published = 1');
    
    // Join over the categories.
		$query->select('cat.title AS category_title')
			->join('LEFT', '#__categories AS cat ON cat.id = a.catid')
      ->where('cat.extension = "com_aftms.coursecategories"');
      
    // Join over the advanced levels.
    $query->select('lvl1.title AS minimum_level, lvl2.title AS maximum_level');
		$query->join('LEFT', '#__categories AS lvl1 ON lvl1.id = a.min_lvl')
      ->where('lvl1.extension = "com_aftms.levels"');
    $query->join('LEFT', '#__categories AS lvl2 ON lvl2.id = a.max_lvl')
      ->where('lvl2.extension = "com_aftms.levels"');
    
    
    // Filter by a single or group of categories.
		$categoryID = $this->getState('filter.category_id');

		if (is_numeric($categoryID))
		{    
			$cat_tbl = JTable::getInstance('Category', 'JTable');
			$cat_tbl->load($categoryID);
			$rgt = $cat_tbl->rgt;
			$lft = $cat_tbl->lft;
      
			$query->where('cat.lft >= ' . (int) $lft)
				->where('cat.rgt <= ' . (int) $rgt);
		}
		elseif (is_array($categoryID) && (count($categoryID) > 0))
		{
			JArrayHelper::toInteger($categoryID);
			$categoryID = implode(',', $categoryID);

			if (!empty($categoryID))
			{
				$query->where('a.catid IN (' . $categoryID . ')');
			}
		}
    
    // Filter by Age.
		$age = $this->getState('filter.age');
    
		if (is_numeric($age))
		{      
			$query->where('a.min_year <= ' . $age)
        ->where('a.max_year' >= ' . $age');
		}
    
    // Filter by level.
		$level = $this->getState('filter.level');
    
		if (is_numeric($level))
		{      
			$query->where('a.simple_lvl = ' . $level);
		}
    elseif (is_array($level) && (count($level) > 0))
		{
			JArrayHelper::toInteger($level);
			$level = implode(',', $level);

			if (!empty($level))
			{
				$query->where('a.simple_lvl IN (' . $level . ')');
			}
		}
    
    // Filter by campus.
		/* 
    $campusID = $this->getState('filter.campus_id');
    
		if(is_numeric($campusID))
		{    
      $campusAssocs = JLanguageAssociations::getAssociations('com_aftms', '#__aftms_campuses', 'com_aftms.centre', $campusID, 'id', 'alias', null); 
      foreach ($campusAssocs as $tag => $campusAssoc)
      {
        $slug = explode(':', $campusAssoc->id);
        $campusAssociations[$tag] = $slug[0];
      }
      
      $campusIds = $campusID;
      if(isset($campusAssociations) && count($campusAssociations))
      {
        $campusIds .= ',' . implode(',', $campusAssociations);
      }
      
      $query->where('class.campusid IN (' . $campusIds . ')');
    }
    elseif(is_array($campusID) && (count($campusID) > 0))
    {
      JArrayHelper::toInteger($campusID);
			$campusID = implode(',', $campusID);
    }
    */
    
    // Filter by frequency.
    $frequency = $this->getState('filter.frequency');
    
    if (is_numeric($frequency))
		{
      $query->where('( LENGTH(co.date_pattern) - LENGTH( REPLACE( co.date_pattern, "COM", "" ) ) ) / LENGTH("COM") = ' . $frequency );
    }
    
    $query->order($this->getState('list.ordering', 'a.ordering') . ' ' . $this->getState('list.direction', 'ASC'));
    
    // Manage association dependant filters and join
    if(JLanguageAssociations::isEnabled())
    {
      // Join over the associations.
      $query->select('COUNT(asso2.id)>1 as association')
        ->join('LEFT', '#__associations AS asso ON asso.id = a.id AND asso.context=' . $db->quote('com_aftms.coursegroup'))
        ->join('LEFT', '#__associations AS asso2 ON asso2.key = asso.key')
        ->group('a.id');

      // Join over the courses
      $query->join('LEFT', '#__aftms_courses AS co ON co.groupid = asso2.id');
      
      // Filter by language
      $lang = JFactory::getLanguage();
      $query->where('(a.language = "' . $lang->getTag() . '" OR a.language = "*")');
    }
    else
    {
      // Join over the courses
      $query->join('LEFT', '#__aftms_courses AS co ON co.groupid = a.id');
    }
    
    return $query;
  }
  
  public function getItems()
  {
    
    $items = parent::getItems();
    
    foreach($items as $item)
    {
      // Get the label for the simple level categorizaion
      $item->simple_lvl_text = AFTMSHelper::getSimpleLevelLabel($item->simple_lvl);
      
      $item->courses = $this->processCourses($this->getCourses($item->id));
    }
    
    return $items;
  }
  
  private function getCourses($courseGroupId)
  {
    $db = $this->getDbo();
		$query = $db->getQuery(true);
    
    $query->select('b.id, b.title, b.start_date, b.end_date, b.date_pattern, b.price_override, b.url, b.campusid')
      ->from('#__aftms_courses AS b')
      ->where('b.published = 1')
      ->where('(DATEDIFF(b.end_date, b.start_date) / 2) > DATEDIFF(NOW(), b.start_date)') 
      ->where('b.end_date > CURRENT_DATE')
      ->order('b.start_date');
    
    // Manage association dependant filters and join
    if(JLanguageAssociations::isEnabled())
    {
      // Filter courses from current coursegroup
      $groupAssocIds = DradLanguageAssociations::getAssociationIds('com_aftms', '#__aftms_course_groups', 'com_aftms.coursegroup', $courseGroupId, 'id', 'alias', null); 
      $groupIds = implode(',', $groupAssocIds);
      
      $query->where('b.groupid IN (' . $groupIds . ')');
      
      // Filter by campus.
      $campusId = $this->getState('filter.campus_id');
      
      if(is_numeric($campusId))
      {    
        $campusAssocIds = DradLanguageAssociations::getAssociationIds('com_aftms', '#__aftms_campuses', 'com_aftms.centre', $campusId, 'id', 'alias', null); 
        $campusIds = implode(',', $campusAssocIds);

        $query->where('b.campusid IN (' . $campusIds . ')');
      }
      elseif(is_array($campusId) && (count($campusId) > 0))
      {
        $campusIds = '';
        
        foreach($campusId as $campus_id)
        {
          $campusAssocIds = DradLanguageAssociations::getAssociationIds('com_aftms', '#__aftms_campuses', 'com_aftms.centre', $campus_id, 'id', 'alias', null); 
          $campusIds = ($campusIds ? $campusIds .',':'') . implode(',', $campusAssocIds);
        }
        
        $query->where('b.campusid IN (' . $campusIds . ')');
      }
    }
    else
    {
      // Filter by the current coursegroup
      $query->where('b.groupid = ' . $courseGroupId);
      
      // Filter by campus
      $campusId = $this->getState('filter.campus_id');
    
      if(is_numeric($campusId))
      {
        $query->where('b.campusid = ' . $campusId);
      }
      elseif(is_array($campusId) && (count($campusId) > 0))
      {
        JArrayHelper::toInteger($campusId);
        $campusIds = implode(',', $campusId);
        
        $query->where('b.campusid IN (' . $campusIds . ')');
      }
    }
    
    $db->setQuery($query);
      
    return $db->loadObjectList();
  }
  
  private function processCourses($courses)
  {    
    $lang = JFactory::getLanguage();
    
    if(count($courses))
    {
      foreach($courses as $courseindex => $course)
      {
        // Transform string dates to JDate objects 
        $course->start_date = new JDate($course->start_date);
        $course->end_date = new JDate($course->end_date);

        //load date pattern field as an array
        $datePattern = new JRegistry;
        $datePattern->loadString($course->date_pattern);
        $course->date_pattern = $datePattern->toArray();

        $course->days_times = AFTMSHelper::displayDatePatterns($course->date_pattern);

        if(JLanguageAssociations::isEnabled())
        { 
          // Filter courses by campus
          $campusAssociations = JLanguageAssociations::getAssociations('com_aftms', '#__aftms_campuses', 'com_aftms.campus', $course->campusid, 'id', 'alias', null); 
          
          foreach ($campusAssociations as $tag => $campusAssociation)
          {
            $slug = explode(':', $campusAssociation->id);
            $campusAssociations[$tag]->simple_id = $slug[0];
          }
          
          // Select the current language association
          if(isset($campusAssociations[$lang->getTag()]))
          {
            $course->campusid = $campusAssociations[$lang->getTag()]->simple_id;
          }
          elseif(isset($campusAssociations['*']))
          {
            $course->campusid = $campusAssociations['*']->simple_id;
          }
        }
       
        $campus = JTable::getInstance('Campus', 'AFTMSTable');
        $campus->load($course->campusid);
        
        $course->campus_name = $campus->title;

        // Filter by frequency
        $frequency = $this->getState('filter.frequency');

        if (is_numeric($frequency))
        {
          if(!is_array($course->date_pattern['weekday']) 
            || count($course->date_pattern['weekday']) != (int)$frequency)
          {
            unset($courses[$courseindex]);
          }
        }
      }
    }
    
    return $courses;
  }
}
