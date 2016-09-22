<?php
/**
 * @package     Joomla.Administrator
 * @subpackage  com_aftms
 */

defined('_JEXEC') or die;

require_once(JPATH_COMPONENT_ADMINISTRATOR.DS.'lib'.DS.'controllers'.DS.'controllerformchildren.php');

/**
 * Fee administration controller class.
 * 
 * @package     Joomla.Administrator
 * @subpackage  com_aftms
 * @since       1.6
 */
class AFTMSControllerFee extends DefaultControllerFormChildren
{
  protected $modelName = 'Fee';
  protected $viewName = 'fees';
}
