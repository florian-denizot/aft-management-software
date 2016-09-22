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
 * Component Controller
 *
 * @package     Joomla.Administrator
 * @subpackage  com_aftms
 */
class AFTMSController extends JControllerLegacy
{
	/**
	 * @var		string	The default view.
	 */
	protected $default_view = 'courses';

	/**
	 * Method to display a view.
	 *
	 * @param   boolean  $cachable   If true, the view output will be cached
	 * @param   array    $urlparams  An array of safe url parameters and their variable types, for valid values see {@link JFilterInput::clean()}.
	 *
	 * @return  JController		This object to support chaining.
	 */
	public function display($cachable = false, $urlparams = false)
	{
		$view   = $this->input->get('view', $this->default_view);
		$layout = $this->input->get('layout', 'default');
		$id     = $this->input->getInt('id');

		// Check for edit form.
		if ($view == 'course' && $layout == 'edit' && !$this->checkEditId('com_aftms.edit.course', $id))
		{
			// Somehow the person just went to a form - we don't allow that.
			$this->setError(JText::sprintf('JLIB_APPLICATION_ERROR_UNHELD_ID', $id));
			$this->setMessage($this->getError(), 'error');
			$this->setRedirect(JRoute::_('index.php?option=com_aftms&view=courses', false));

			return false;
		}

		return parent::display();
	}
}
