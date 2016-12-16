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
          'frequency' => '',
          'level_id' => '',
          'campus_id'=> ''
        )
      );
    }
    
    $this->getUserStateFromRequest($this->context . '.filter', 'filter', array());
    
    $categoryId = $app->getUserStateFromRequest($this->context . '.filter.category_id', 'filter_category_id');
		$this->setState('filter.category_id', $categoryId);
    
    $frequency = $app->getUserStateFromRequest($this->context . '.filter.frequency', 'filter_frequency');
		$this->setState('filter.frequency', $frequency);
    
    $levelId = $app->getUserStateFromRequest($this->context . '.filter.level_id', 'filter_level_id');
		$this->setState('filter.level_id', $levelId);
    
    $campusId = $app->getUserStateFromRequest($this->context . '.filter.campus_id', 'filter_campus_id');
		$this->setState('filter.campus_id', $campusId);
    
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
    
    $query->select('a.typeid, a.catid, a.min_lvl, a.max_lvl, a.simple_lvl, a.featured, a.image_ext')
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
    elseif (is_array($levelID) && (count($levelID) > 0))
    {
			JArrayHelper::toInteger($levelID);
			$levelID = implode(',', $levelID);

			if (!empty($levelID))
			{
				$query->where('a.catid IN (' . $levelID . ')');
			}
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
    //$query->where('(DATEDIFF(co.end_date, co.start_date) / 2) > DATEDIFF(NOW(), co.start_date)') 
    //    ->where('co.end_date > CURRENT_DATE');
    
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
      $item->simple_lvl_text = AFTMSHelper::getSimpleLevelLabel($item->simple_lvl);
    }
    
    return $items;
  }
}
