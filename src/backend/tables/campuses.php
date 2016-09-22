<?php
/**
 * @package     Joomla.Administrator
 * @subpackage  com_aftms
 */

defined('_JEXEC') or die;

require_once(JPATH_COMPONENT_ADMINISTRATOR.DS.'lib'.DS.'tables'.DS.'table.php');

/**
 * @package     Joomla.Administrator
 * @subpackage  com_aftms
 */
class AFTMSTableCentres extends DefaultTable
{
  protected $name = 'Centres';
  
	/**
	 * @param   JDatabaseDriver  A database connector object
	 */
	public function __construct(&$db)
	{
		parent::__construct('#__aftms_campuses', 'id', $db);
	}
}
