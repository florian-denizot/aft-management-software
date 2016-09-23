<?php
/**
 * @package     Joomla.Site
 * @subpackage  com_aftms
 *
 * @copyright   Copyright (C) 2016 Alliance Francaise Toronto. All rights reserved.
 * @license     LTBD
 */

defined('_JEXEC') or die;

/**
 * AFTMS Component Controller
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
		// Set the default view name and format from the Request.
		// Note we are using a_id to avoid collisions with the router and the return page.
		// Frontend is a bit messier than the backend.
		$id    = $this->input->getInt('a_id');
		$vName = $this->input->getCmd('view', 'coursegroups');
		$this->input->set('view', $vName);

		$user = JFactory::getUser();	

		$safeurlparams = array(
			'id'				        => 'INT',
			'limit'				      => 'UINT',
			'limitstart'		    => 'UINT',
      'filter'            => 'STRING',
			'filter_order'		  => 'CMD',
			'filter_order_Dir'	=> 'CMD',
      'filter-search'     => 'STRING',
			'lang'				      => 'CMD',
      
		);

		parent::display($cachable, $safeurlparams);

		return $this;
	}
}
