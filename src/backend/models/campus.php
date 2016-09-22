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
 * Campus Model
 */
class AFTMSModelCampus extends DradAdminModelAdmin
{
  /*
   * The name of the config section to load in the drad config file.
   * 
   * @var   string 
   */
  protected $drad_element = 'campus';
  
	/**
	 * Method to get a table object, load it if necessary.
	 *
	 * @param   string  $type    The table name. Optional.
	 * @param   string  $prefix  The class prefix. Optional.
	 * @param   array   $config  Configuration array for model. Optional.
	 *
	 * @return  JTable  A JTable object
	 */
	public function getTable($type = 'Campus', $prefix = 'AFTMSTable', $config = array())
	{
		return JTable::getInstance($type, $prefix, $config);
	}
}