<?php
/**
 * @package     Joomla.Administrator
 * @subpackage  com_aftms
 */

defined('_JEXEC') or die;

require_once(JPATH_COMPONENT_ADMINISTRATOR.DS.'lib'.DS.'controllers'.DS.'controlleradminchildren.php');

/**
 * Fees list controller class.
 *
 * @package     Joomla.Administrator
 * @subpackage  com_aftms
 * @since       1.6
 */
class AFTMSControllerFees extends DefaultControllerAdminChildren
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
	public function getModel($name = 'Fee', $prefix = 'AFTMSModel', $config = array('ignore_request' => true))
	{
		$model = parent::getModel($name, $prefix, $config);

		return $model;
	}
  
  /**
	 * Method to close a children list interface.
	 *
	 * @since   12.2
	 */
	public function editcourse()
	{
		JSession::checkToken() or jexit(JText::_('JINVALID_TOKEN'));
    
		$this->setRedirect(
			JRoute::_('index.php?option=com_aftms&task=course.edit&id=' . $this->parent_id, false)
		);

		return true;
	}
  
  public function tocourses()
	{
		JSession::checkToken() or jexit(JText::_('JINVALID_TOKEN'));
    
		$this->setRedirect(
			JRoute::_('index.php?option=com_aftms&view=courses', false)
		);

		return true;
	}
}
