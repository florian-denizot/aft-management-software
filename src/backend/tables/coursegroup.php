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
 * Coursegroup table
 */
class AFTMSTableCoursegroup extends DradAdminTableTable
{
  /**
	 * Constructor
	 *
	 * @param   JDatabaseDriver  $db  A database connector object
	 */
	public function __construct($db)
	{
    $this->text_prefix = "COM_AFTMS_COURSEGROUP";
    $this->drad_element = "coursegroup";
    
		parent::__construct($db);
	}
  
  /**
	 * Method to perform sanity checks on the JTable instance properties to ensure
	 * they are safe to store in the database.  Child classes should override this
	 * method to make sure the data they are storing in the database is safe and
	 * as expected before storage.
	 *
	 * @return  boolean  True if the instance is sane and able to be stored in the database.
	 */
  public function check()
  {  
    // Check the publish down date is not earlier than publish up.
		if ($this->publish_down > $this->_db->getNullDate() && $this->publish_down < $this->publish_up)
		{
			// Swap the dates.
			$temp = $this->publish_up;
			$this->publish_up = $this->publish_down;
			$this->publish_down = $temp;
		}
    
    // Check the max year is greater than the min year
		if ($this->min_year > $this->max_year)
		{
			// Swap the dates.
			$temp = $this->min_year;
			$this->min_year = $this->max_year;
			$this->max_year = $temp;
		}
    
    // Check the max month is greater than the min month
		if ($this->min_month > $this->max_month && $this->max_year == $this->min_year)
		{
			// Swap the dates.
			$temp = $this->min_month;
			$this->min_month = $this->max_month;
			$this->max_month = $temp;
		}
    
    return parent::check();
  }
}
