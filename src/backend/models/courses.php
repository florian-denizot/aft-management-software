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
 * Course list Model
 */
class AFTMSModelCourses extends DradAdminModelList
{
  /*
   * The name of the config section to load in the drad config file.
   * 
   * @var   string 
   */
  protected $drad_element = 'course';
 
  
	/**
	 * Constructor.
	 *
	 * @param   array  $config  An optional associative array of configuration settings.
	 */
	public function __construct($config = array())
	{
		if (empty($config['filter_fields']))
		{
			$config['filter_fields'] = array(
        'catid', 'cc.catid', 'category_id',
				'typeid', 't.id', 'type_id',
        'sessionid', 's.id', 'session_id',
        'campusid', 'c.id', 'campus_id', 'campus_name',
        'start_date', 'a.start_date'
			);
		}
    
		parent::__construct($config);
	}

  /*
	 * Method to auto-populate the model state.
	 *
	 * Note. Calling getState in this method will result in recursion.
	 *
	 * @param   string  $ordering   An optional ordering field.
	 * @param   string  $direction  An optional direction (asc|desc).
	 *
	 * @return  void
	 */
	protected function populateState($ordering = null, $direction = null)
	{    
    $app = JFactory::getApplication();
    
    $campusId = $this->getUserStateFromRequest($this->context . '.filter.campus_id', 'filter_campus_id');
		$this->setState('filter.campus_id', $campusId);
    
    $typeId = $this->getUserStateFromRequest($this->context . '.filter.type_id', 'filter_type_id');
		$this->setState('filter.type_id', $typeId);
    
    $categoryId = $this->getUserStateFromRequest($this->context . '.filter.category_id', 'filter_category_id');
		$this->setState('filter.category_id', $categoryId);
    
    $sessionId = $this->getUserStateFromRequest($this->context . '.filter.session_id', 'filter_session_id');
		$this->setState('filter.session_id', $sessionId);
    
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
    $id .= ':' . $this->getState('filter.type_id');
    $id .= ':' . $this->getState('filter.campus_id');
    $id .= ':' . $this->getState('filter.session_id');
    $id .= ':' . $this->getState('filter.category_id');

		return parent::getStoreId($id);
	}

	/**
	 * Build an SQL query to load the list data.
	 *
	 * @return  JDatabaseQuery
	 */
	protected function getListQuery()
	{
    $db = $this->getDbo();
    $query = parent::getListQuery();

		// Select the required fields from the table.
		$query->select(
			$this->getState(
				'list.select',
				'a.start_date, a.end_date'
			)
		);
    
    // Join over the sessions.
		$query->select('s.title AS session_title')
			->join('LEFT', '#__categories AS s ON s.id = a.sessionid')
      ->where('s.extension = "com_aftms.sessions"');
      
    // Join over the campus.
		$query->select('ca.title AS campus_name')
			->join('LEFT', '#__aftms_campuses AS ca ON ca.id = a.campusid');
      
      
      
    // Join over the coursegroup.
		$query->select('cg.title AS coursegroup_title')
			->join('LEFT', '#__aftms_course_groups AS cg ON a.groupid = cg.id');
      
    // Join over the types.
		$query->select('t.title AS type_title')
			->join('LEFT', '#__categories AS t ON cg.typeid = t.id AND t.extension = "com_aftms.coursetypes"');
      //->where('t.extension = "com_aftms.coursetypes"');
      
    // Join over the categories.
		$query->select('cat.title AS category_title')
			->join('LEFT', '#__categories AS cat ON cg.catid = cat.id AND cat.extension = "com_aftms.coursecategories"');
			//->where('cat.extension = "com_aftms.coursecategories"');
    

    // Filter by a single or group of category.
		$categoryId = $this->getState('filter.category_id');

		if (is_numeric($categoryId))
		{
			$cat_tbl = JTable::getInstance('Category', 'JTable');
			$cat_tbl->load($categoryId);
			$rgt = $cat_tbl->rgt;
			$lft = $cat_tbl->lft;
			$query->where('cat.lft >= ' . (int) $lft)
				->where('cat.rgt <= ' . (int) $rgt);
		}
		elseif (is_array($categoryId))
		{
			JArrayHelper::toInteger($categoryId);
			$categoryId = implode(',', $categoryId);
			$query->where('cg.catid IN (' . $categoryId . ')');
		}
    
    // Filter by a single or group of type.
		$typeId = $this->getState('filter.type_id');

		if (is_numeric($typeId))
		{
			$type_tbl = JTable::getInstance('Category', 'JTable');
			$type_tbl->load($typeId);
			$rgt = $type_tbl->rgt;
			$lft = $type_tbl->lft;
			$query->where('t.lft >= ' . (int) $lft)
				->where('t.rgt <= ' . (int) $rgt);
		}
		elseif (is_array($typeId))
		{
			JArrayHelper::toInteger($typeId);
			$typeId = implode(',', $typeId);
			$query->where('cg.typeid IN (' . $typeId . ')');
		}
    
    // Filter by a single or group of session.
		$sessionId = $this->getState('filter.session_id');

		if (is_numeric($sessionId))
		{
			$session_tbl = JTable::getInstance('Category', 'JTable');
			$session_tbl->load($sessionId);
			$rgt = $session_tbl->rgt;
			$lft = $session_tbl->lft;
			$query->where('s.lft >= ' . (int) $lft)
				->where('s.rgt <= ' . (int) $rgt);
		}
		elseif (is_array($sessionId))
		{
			JArrayHelper::toInteger($sessionId);
			$sessionId = implode(',', $sessionId);
			$query->where('a.sessionid IN (' . $sessionId . ')');
		}
    
    // Filter by campus
    $campusId = $this->getState('filter.campus_id');
            
		if (is_numeric($campusId))
		{
			$query->where('a.campusid = ' . $campusId);
		}
    
		// Add the list ordering clause.
		$orderCol = $this->state->get('list.ordering', 'a.start_date');
		$orderDirn = $this->state->get('list.direction', 'asc');

    $query->clear('order');
		$query->order($db->escape($orderCol . ' ' . $orderDirn));

		return $query;
	}
}
