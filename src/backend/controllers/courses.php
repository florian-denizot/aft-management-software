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
 * Course List Controller
 */
class AFTMSControllerCourses extends DradAdminControllerList
{
	/**
	 * Proxy for getModel
	 *
	 * @param   string  $name    The model name. Optional.
	 * @param   string  $prefix  The class prefix. Optional.
	 * @param   array   $config  The array of possible config values. Optional.
	 *
	 * @return  object  The model.
	 */
	public function getModel($name = 'Course', $prefix = 'AFTMSModel', $config = array('ignore_request' => true))
	{
		$model = parent::getModel($name, $prefix, $config);

		return $model;
	}
	
}
