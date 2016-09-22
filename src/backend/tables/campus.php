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
 * Campus table
 */
class AFTMSTableCampus extends DradAdminTableTable
{
	/**
	 * Constructor
	 *
	 * @param   JDatabaseDriver  $db  A database connector object
	 */
	public function __construct($db)
	{
    $this->text_prefix = "COM_AFTMS_CAMPUS";
    $this->drad_element = "campus";
    
		parent::__construct($db);
	}
}
