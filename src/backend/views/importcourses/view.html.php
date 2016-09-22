<?php
/**
 * @package     Joomla.Administrator
 * @subpackage  com_aftms
 *
 * @copyright   Copyright (C) 2005 - 2014 Open Source Matters, Inc. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */

defined('_JEXEC') or die;


/**
 * View to import courses
 *
 * @package     Joomla.Administrator
 * @subpackage  com_aftms
 * @since       1.6
 */
class AFTMSViewImportCourses extends JViewLegacy
{
  protected $form;

  protected $state;
  
  protected $controllerFormName = 'importcourses';

	/**
	 * Display the view
	 */
	public function display($tpl = null)
	{
		$this->form		= $this->get('Form');
		$this->state	= $this->get('State');
    $this->model = $this->getModel();
    
		// Check for errors.
		if (count($errors = $this->get('Errors')))
		{
			JError::raiseError(500, implode("\n", $errors));
			return false;
		}
    
    JToolbarHelper::title(JText::_('COM_AFTMS_IMPORT_COURSES_TITLE'), 'cogs');
    
    if($this->getLayout() == 'result')
    {
      $this->data = $this->model->loadInputData();
      JFactory::getApplication()->input->set('hidemainmenu', true);
    }
    else
    {
      JToolbarHelper::custom($this->controllerFormName . '.import', 'cogs', 'cogs', JText::_('COM_AFTMS_IMPORT_COURSES'), false);
      AFTMSHelper::addSubmenu('importcourses');
      $this->sidebar = JHtmlSidebar::render();
      
    }
    
    
		parent::display($tpl);
	}
}
