<?php
/**
 * @package     Joomla.Administrator
 * @subpackage  com_aftms
 */

defined('_JEXEC') or die;

require_once(JPATH_COMPONENT_ADMINISTRATOR.DS.'lib'.DS.'controllers'.DS.'controlleradmin.php');

/**
 * Centres list controller class.
 *
 * @package     Joomla.Administrator
 * @subpackage  com_aftms
 * @since       1.6
 */
class AFTMSControllerCentres extends DefaultControllerAdmin
{
  /**
	 * Proxy for getModel.
	 *
	 * @param   string	$name	The name of the model.
	 * @param   string	$prefix	The prefix for the PHP class name.
	 *
	 * @return  JModel
	 * @since   1.6
	 */
	public function getModel($name = 'Centre', $prefix = 'AFTMSModel', $config = array('ignore_request' => true))
	{
		$model = parent::getModel($name, $prefix, $config);

		return $model;
	}
}
