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
 * Classroom list Model
 */
class AFTMSModelClassrooms extends DradAdminModelList
{
  
  /*
   * The name of the config section to load in the drad config file.
   * 
   * @var   string 
   */
  protected $drad_element = 'classroom';
  
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
        'ca.title', 'campus_name', 'campus_id'
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
   $id .= ':' . $this->getState('filter.campus_id');

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
    
    // Join over the campuses
		$query->select('ca.title AS campus_name')
			->join('LEFT', '#__aftms_campuses AS ca ON ca.id=a.campusid');

    // Filter over the campus.
		if ($campus = $this->getState('filter.campus_id'))
		{
			$query->where('a.campusid = ' . $db->quote($campus));
		}

		return $query;
	}
}
