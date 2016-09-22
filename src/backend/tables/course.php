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
 * Course table
 */
class AFTMSTableCourse extends DradAdminTableTable
{
	/**
	 * Constructor
	 *
	 * @param   JDatabaseDriver  $db  A database connector object
	 */
	public function __construct($db)
	{
    $this->text_prefix = "COM_AFTMS_COURSE";
    $this->drad_element = "course";
    
		parent::__construct($db);
	}
}