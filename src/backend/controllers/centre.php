<?php
/**
 * @package     Joomla.Administrator
 * @subpackage  com_aftms
 */

defined('_JEXEC') or die;

require_once(JPATH_COMPONENT_ADMINISTRATOR.DS.'lib'.DS.'controllers'.DS.'controllerform.php');

/**
 * Centre administration controller class.
 * 
 * @package     Joomla.Administrator
 * @subpackage  com_aftms
 * @since       1.6
 */
class AFTMSControllerCentre extends DefaultControllerForm
{
  protected $modelName = 'Centre';
  protected $viewName = 'centres';
}
