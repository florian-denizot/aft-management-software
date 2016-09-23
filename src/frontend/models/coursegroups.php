<?php
/**
 * @package     Joomla.Site
 * @subpackage  com_aftms
 *
 * @copyright   Copyright (C) 2016 Alliance Francaise Toronto. All rights reserved.
 * @license     LTBD
 */

defined('_JEXEC') or die;

use Joomla\Registry\Registry;
use Joomla\Utilities\ArrayHelper;

/**
 * Coursegroup list model
 */
class AFTMSModelCourseGroups extends DradSiteModelList
{
  /*
   * The name of the config section to load in the drad config file.
   * 
   * @var   string 
   */
  protected $drad_element = 'coursegroup';
  
  protected $tableName = '#__aftms_course_groups';
  
  /**
	 * Constructor.
	 *
	 * @param   array  $config  An optional associative array of configuration settings.
	 *
	 * @since   1.6
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
	 *
	 * @since   1.6
	 */
	protected function populateState($ordering = 'a.title', $direction = 'asc')
	{
    $app = JFactory::getApplication();
		$input = $app->input;
    $lang = JFactory::getLanguage();
    
    // List state information
		$limit = $app->getUserStateFromRequest('global.list.limit', 'limit', $app->getCfg('list_limit'), 'uint');
		$this->setState('list.limit', $limit);

		$limitstart = $app->input->get('limitstart', 0, 'uint');
		$this->setState('list.start', $limitstart);
    
    
    $filterState = $app->getUserStateFromRequest($this->context . '.filter', 'filter');
    
    //$campusID = $app->getUserStateFromRequest($this->context . '.filter.campus_id', 'filter_campus_id', '');
		$this->setState('filter.campus_id', $filterState['campus_id']);
    
    //$categoryID = $app->getUserStateFromRequest($this->context . '.filter.category_id', 'filter_category_id', '');
		$this->setState('filter.category_id', $filterState['category_id']);
    
		$this->setState('filter.frequency', $filterState['frequency']);
    
    //$sessionID = $app->getUserStateFromRequest($this->context . '.filter.session_id', 'filter_session_id', '');
		//$this->setState('filter.session_id', $filterState['session_id']);
    
    //$levelID = $app->getUserStateFromRequest($this->context . '.filter.level_id', 'filter_level_id', '');
		$this->setState('filter.level_id', $filterState['level_id']);
    
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
	 *
	 * @since   1.6
	 */
	protected function getStoreId($id = '')
	{
		// Compile the store id.
		$id .= ':' . $this->getState('filter.campus_id');
		$id .= ':' . $this->getState('filter.session_id');
		$id .= ':' . $this->getState('filter.category_id');
		$id .= ':' . $this->getState('filter.level_id');
    $id .= ':' . $this->getState('filter.frequency');
    
		return parent::getStoreId($id);
	}
  
  protected function getListQuery()
	{  
    $db = $this->getDbo();
    
    $query = parent::getListQuery();
    
    $query->select('a.typeid, a.catid, a.min_lvl, a.max_lvl, a.min_year, a.min_month, a.max_year, a.max_month, a.featured, a.image_ext')
      ->where('a.published = 1');
    
    
    
    // Join over the categories.
		$query->select('cat.title AS category_title')
			->join('LEFT', '#__categories AS cat ON cat.id = a.catid')
      ->where('cat.extension = "com_aftms.coursecategories"');
      
    // Join over the levels.
    $query->select('lvl1.title AS minimum_level, lvl2.title AS maximum_level');
		$query->join('LEFT', '#__categories AS lvl1 ON lvl1.id = a.min_lvl')
      ->where('lvl1.extension = "com_aftms.levels"');
    $query->join('LEFT', '#__categories AS lvl2 ON lvl2.id = a.max_lvl')
      ->where('lvl2.extension = "com_aftms.levels"');
      
    // Join over the associations.
    $query->select('COUNT(asso2.id)>1 as association')
      ->join('LEFT', '#__associations AS asso ON asso.id = a.id AND asso.context=' . $db->quote('com_aftms.coursegroup'))
      ->join('LEFT', '#__associations AS asso2 ON asso2.key = asso.key')
      ->group('a.id');
      
    // Join over the courses
    $query->join('LEFT', '#__aftms_courses AS co ON co.groupid = asso2.id');
    
    
    // Join over the classroom 
    $query->join('LEFT', '#__aftms_classrooms AS class ON class.id = co.classroomid');
    
    // Filter by the age range defined in the menu item
//    $min_year = $this->getState('age_range.min_year');
//    $min_month = $this->getState('age_range.min_month');
//    $max_year = $this->getState('age_range.max_year');
//    $max_month = $this->getState('age_range.max_month');
//    
//    if (is_numeric($min_year) && is_numeric($min_month) && is_numeric($max_year) && is_numeric($max_month))
//    {
//      $query->where('((a.min_year >=' . $min_year. ' AND a.min_year <= ' . $max_year. ') 
//          OR (a.max_year >= ' . $min_year . ' AND a.max_year <= ' . $max_year. ')
//          OR (a.min_year >= ' . $min_year . ' AND a.max_year <= ' . $max_year.')
//          OR (a.min_year <= ' . $min_year . ' AND a.max_year >= ' . $max_year.'))');
//        
//      if($min_year == 0)
//      {
//        $query->where('a.min_month <=' . $min_month);
//      }
//    }
    
    
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
    
    // Filter by level.
		$levelID = $this->getState('filter.level_id');
    
		if (is_numeric($levelID))
		{    
			$lvl_tbl = JTable::getInstance('Category', 'JTable');
			$lvl_tbl->load($levelID);
			$rgt = $lvl_tbl->rgt;
			$lft = $lvl_tbl->lft;
      
			$query->where('lvl1.lft <= ' . (int) $lft)
				->where('lvl2.rgt >= ' . (int) $rgt);
		}
    
    
    // Filter by campus.
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
    
    // Filter by frequency.
    $frequency = $this->getState('filter.frequency');
    
    if (is_numeric($frequency))
		{
      $query->where('( LENGTH(co.date_pattern) - LENGTH( REPLACE( co.date_pattern, "COM", "" ) ) ) / LENGTH("COM") = ' . $frequency );
    }
    
    // Filter by registration deadline
    $query->where('(DATEDIFF(co.end_date, co.start_date) / 2) > DATEDIFF(NOW(), co.start_date)') 
        ->where('co.end_date > CURRENT_DATE');
    
    // Filter by language
    $lang = JFactory::getLanguage();
    $query->where('(a.language = "' . $lang->getTag() . '" OR a.language = "*")');
    
    $query->order($this->getState('list.ordering', 'a.ordering') . ' ' . $this->getState('list.direction', 'ASC'));
    
    return $query;
  }
  
  public function getItems()
  {
    $items = parent::getItems();
    
    // Add courses list to each course group
    foreach($items as $index => &$item)
    {                  
      // manage image
      $params = JComponentHelper::getParams('com_aftms');
      $params->toArray();
      
      $IEWidth = $params['image_cg_width'];
      $IEHeight = $params['image_cg_height'];
      
      if($item->image_ext)
      {
        $imageURL = JURI::base().'images/aftms/courses/thumbs/'. $item->id .'_'. $IEWidth .'x'. $IEHeight .'.'. $item->image_ext;
        $imagePath = JPATH_ROOT.'/images/aftms/courses/thumbs/'. $item->id .'_'. $IEWidth .'x'. $IEHeight .'.'. $item->image_ext;
        
        if(JFile::exists($imagePath))
        { 
          $item->image = $imageURL;
        }
      }
    }
    
    return $items;
  }
}
