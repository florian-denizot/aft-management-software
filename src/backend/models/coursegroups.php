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
 * Coursegroup list Model
 */
class AFTMSModelCourseGroups extends DradAdminModelList
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
	 */
	public function __construct($config = array())
	{
		if (empty($config['filter_fields']))
		{
			$config['filter_fields'] = array(
        'catid', 'a.catid', 'category_title', 'category_id',
				'typeid', 'a.typeid', 'type_title', 'type_id',
        'min_level_title', 'min_level_title', 'level_id',                
        'min_year', 'a.min_year',
				'featured', 'a.featured',
				'publish_up', 'a.publish_up',
				'publish_down', 'a.publish_down'
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
	protected function populateState($ordering = null, $direction = null)
	{
		$app = JFactory::getApplication();

		$categoryId = $this->getUserStateFromRequest($this->context . '.filter.category_id', 'filter_category_id');
		$this->setState('filter.category_id', $categoryId);
    
    $typeId = $this->getUserStateFromRequest($this->context . '.filter.type_id', 'filter_type_id');
		$this->setState('filter.type_id', $typeId);
    
    $levelId = $this->getUserStateFromRequest($this->context . '.filter.level_id', 'filter_level_id');
		$this->setState('filter.level_id', $levelId);

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
		$id .= ':' . $this->getState('filter.level_id');
		$id .= ':' . $this->getState('filter.type_id');
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
  
		$user = JFactory::getUser();
		$app = JFactory::getApplication();

		// Select the required fields from the table.
		$query->select(
			$this->getState(
				'list.select',
				'a.typeid, a.acronym, a.catid, a.min_lvl, a.max_lvl, a.featured, a.publish_up, a.publish_down, a.min_year, a.max_year'
			)
		);

		// Join over the categories.
		$query->select('c.title AS category_title')
			->join('LEFT', '#__categories AS c ON c.id = a.catid')
			->where('c.extension = "com_aftms.coursecategories"');
      
    // Join over the types.
		$query->select('t.title AS type_title')
			->join('LEFT', '#__categories AS t ON t.id = a.typeid')
      ->where('t.extension = "com_aftms.coursetypes"');
      
      
    // Join over the course levels.
		$query->select('lv.title AS min_level_title')
			->join('LEFT', '#__categories AS lv ON lv.id = a.min_lvl')
      ->where('lv.extension = "com_aftms.levels"');
      
    // Join over the course levels.
		$query->select('lv2.title AS max_level_title')
			->join('LEFT', '#__categories AS lv2 ON lv2.id = a.max_lvl')
      ->where('lv2.extension = "com_aftms.levels"');

		// Filter by a single or group of categories.
		$categoryId = $this->getState('filter.category_id');

		if (is_numeric($categoryId))
		{
			$cat_tbl = JTable::getInstance('Category', 'JTable');
			$cat_tbl->load($categoryId);
			$rgt = $cat_tbl->rgt;
			$lft = $cat_tbl->lft;
			$query->where('c.lft >= ' . (int) $lft)
				->where('c.rgt <= ' . (int) $rgt);
		}
		elseif (is_array($categoryId))
		{
			JArrayHelper::toInteger($categoryId);
			$categoryId = implode(',', $categoryId);
			$query->where('a.catid IN (' . $categoryId . ')');
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
			$query->where('a.typeid IN (' . $typeId . ')');
		}

    // Filter on the course level
		if ($level = $this->getState('filter.level_id'))
		{
			$query->where('a.min_lvl >= ' . $db->quote($level));
			$query->where('a.max_lvl <= ' . $db->quote($level));
		}

		// Add the list ordering clause.
		$orderCol = $this->state->get('list.ordering', 'a.title');
		$orderDirn = $this->state->get('list.direction', 'asc');

		if ($orderCol == 'language')
		{
			$orderCol = 'l.title';
		}

    $query->clear('order');
		$query->order($db->escape($orderCol . ' ' . $orderDirn));

		return $query;
	}
}
