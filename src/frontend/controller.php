<?php

defined('_JEXEC') or die;

/**
 * AFTMS Component Controller
 *
 * @package     Joomla.Site
 * @subpackage  com_aftms
 */
class AFTMSController extends JControllerLegacy
{

	/**
	 * Method to display a view.
	 *
	 * @param   boolean			If true, the view output will be cached
	 * @param   array  An array of safe url parameters and their variable types, for valid values see {@link JFilterInput::clean()}.
	 *
	 * @return  JController		This object to support chaining.
	 */
	public function display($cachable = false, $urlparams = false)
	{
		$cachable = true;

		// Set the default view name and format from the Request.
		// Note we are using a_id to avoid collisions with the router and the return page.
		// Frontend is a bit messier than the backend.
		$id    = $this->input->getInt('a_id');
		$vName = $this->input->getCmd('view', 'courses');
		$this->input->set('view', $vName);

		$user = JFactory::getUser();

		/*if ($user->get('id') || ($this->input->getMethod() == 'POST' ))
		{
			$cachable = false;
		}*/
    $cachable = false;

		$safeurlparams = array(
			'id'				        => 'INT',
			'limit'				      => 'UINT',
			'limitstart'		    => 'UINT',
			'filter_order'		  => 'CMD',
			'filter_order_Dir'	=> 'CMD',
			'lang'				      => 'CMD'
		);

		parent::display($cachable, $safeurlparams);

		return $this;
	}
}
