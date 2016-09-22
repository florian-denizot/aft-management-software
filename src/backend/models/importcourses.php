<?php
/**
 * @package     Joomla.Administrator
 * @subpackage  com_aftms
 *
 * @copyright   Copyright (C) 2016 Alliance Francaise Toronto. All rights reserved.
 * @license     LTBD
 */

defined('_JEXEC') or die;

require_once(JPATH_COMPONENT_ADMINISTRATOR.DS.'models'.DS.'course.php');
require_once(JPATH_COMPONENT_ADMINISTRATOR.DS.'tables'.DS.'coursegroup.php');
require_once(JPATH_COMPONENT_ADMINISTRATOR.DS.'tables'.DS.'course.php');
require_once(JPATH_COMPONENT_ADMINISTRATOR.DS.'lib'.DS.'PHPExcel'.DS.'IOFactory.php');

/**
 * Import courses model class
 */
class AFTMSModelImportCourses extends JModelForm
{
  /*
   * Import experts from an excel file
   * 
   * @param   array   one file information as given by JInput
   *
   * @return  int     number of imported experts 
   */
  public function importCourseFromFile($row)
  {   
    
    $session = JFactory::getSession();
    $db = $this->getDbo();
    
    $sheet       = $session->get('importcourses.sheet', null);
    $classroom  = $session->get('importcourses.classroom', 0);
    $csession    = $session->get('importcourses.session', 0);
    $update     = $session->get('importcourses.update', 0);
    
    $updated = 0;
    
    $sheet = unserialize($sheet);
    
    if($sheet && $classroom && $csession)
    {
      // Setup first values
      $data['title'] = $sheet->getCellByColumnAndRow(0, $row)->getValue();
      $data['alias'] = str_replace('.', '', $sheet->getCellByColumnAndRow(1, $row)->getValue());
      $data['published'] = 0;
      $data['classroomid'] = $classroom;
      $data['sessionid'] = $csession;
      $data['published'] = 0;
      $data['price_override'] = $sheet->getCellByColumnAndRow(11, $row)->getFormattedValue();
      
      if(empty($data['title']) || empty($data['alias']))
      {
        throw new Exception(JText::sprintf('COM_AFTMS_IMPORT_COURSES_WARNING_EMTPY_CELL', $row), 2005);
      }
      
      
      try
      {
        $startDateTime = new JDate($sheet->getCellByColumnAndRow(2, $row)->getFormattedValue());
      }
      catch(Exception $e)
      {
        throw new Exception(JText::sprintf("COM_AFTMS_IMPORT_COURSES_WARNING_START_DATE_PROBLEM", $row, $e->getMessage()), 2001);
      }


      if($sheet->getCellByColumnAndRow(12, $row)->getFormattedValue())
      {
        try
        {
          $endDateTime = new JDate($sheet->getCellByColumnAndRow(12, $row)->getFormattedValue());
        }
        catch(Exception $e)
        {
          throw new Exception(JText::sprintf("COM_AFTMS_IMPORT_COURSES_WARNING_END_DATE_PROBLEM", $row, $e->getMessage()), 2002);
        }
      }
      else   
      {
        $endDateTime = null;
      }

      $days = explode(' ', $sheet->getCellByColumnAndRow(4, $row)->getFormattedValue());

      $week = array(
        'M'=>'COM_AFTMS_MON',
        'Tu'=>'COM_AFTMS_TUE',
        'W'=>'COM_AFTMS_WED',
        'Th'=>'COM_AFTMS_THU',
        'F'=>'COM_AFTMS_FRI',
        'Sa'=>'COM_AFTMS_SAT',
        'Su'=>'COM_AFTMS_SUN');


      // Date pattern
      $datePattern = new JRegistry();
      $weekday = array();
      $start_hour = array();
      $end_hour = array();
      $start_min = array();
      $end_min = array();

      foreach($days as $day)
      {
        $weekday[] = $week[$day];
        //$start_hour[] = $startTime[0];
        $start_hour[] = $startDateTime->format('G');
        //$start_min[] = $startTime[1];
        $start_min[] = $startDateTime->format('i');
        if($endDateTime)
        {
          $end_hour[] = $endDateTime->format('G');
          $end_min[] = $endDateTime->format('i');
        }
        else
        {
          $end_hour[] = '0';
          $end_min[] = '00';
        }
      }

      $datePattern->set('weekday', $weekday);
      $datePattern->set('start_hour', $start_hour);
      $datePattern->set('end_hour', $end_hour);
      $datePattern->set('start_min', $start_min);
      $datePattern->set('end_min', $end_min);
      $data['date_pattern'] = $datePattern->toString();


      // start and end dates
      //$startDate = new DateTime($startDateTime[0]);
      $endDate = new DateTime($sheet->getCellByColumnAndRow(3, $row)->getFormattedValue());

      $data['start_date'] = $startDateTime->format('Y-m-d'). ' 12:00:00';
      $data['end_date'] = $endDate->format('Y-m-d'). ' 12:00:00';


      // Registration URL
      if($sheet->getCellByColumnAndRow(0, $row)->getHyperlink()->getUrl())  
      {
        $ref=array();
        $ref = explode('activity_id=', $sheet->getCellByColumnAndRow(0, $row)->getHyperlink()->getUrl());
        $tmp=$ref[1];
        $ref=explode('&sdireqauth', $tmp);
        $data['url'] = 'https://ca.apm.activecommunities.com/aftoronto/Activity_Search?activity_id=' . $ref[0];
      }

      // Course group ID
      unset($data['groupid']);
      $cgID = $sheet->getCellByColumnAndRow(13, $row)->getOldCalculatedValue();

      // Check the Course Group ID exist
      if($cgID && is_numeric($cgID))
      {
        $cg = new AFTMSTableCourseGroups($db);
        try
        {
          if($cg->load($cgID))
          {
            $data['groupid'] = $cgID;
          }
          else
          {
            throw new Exception(JText::sprintf('COM_AFTMS_IMPORT_COURSES_WARNING_NO_COURSEGROUP_ID', $sheet->getCellByColumnAndRow(13, $row)->getOldCalculatedValue()), 2003);
          }
        }
        catch(Exception $e)
        {
          throw new Exception($e->getMessage(), 2004);
        }
      }


      if($update)
      {
        $alias = JApplication::stringURLSafe(trim($data['alias']));

        $courseRecord = new AFTMSTableCourses($db);
        
        if($courseRecord->load(array('alias' => $alias)))
        {
          $data['id'] = $courseRecord->id;
          $updated = 1;
        }

        if($sheet->getCellByColumnAndRow(9, $row)->getValue() == 'Open')
        {
          $data['published'] = 1;
        }
      }


      $course = new AFTMSModelCourse();
      
      if($course->save($data))
      {        
        if($updated)
        {
          $data['message'] = JText::sprintf('COM_AFTMS_IMPORT_COURSES_COURSE_UPDATED', $row, $data['title'], $data['alias']);
        }
        else 
        {
          $data['message'] = JText::sprintf('COM_AFTMS_IMPORT_COURSES_COURSE_IMPORTED', $row, $data['title'], $data['alias']);
        }
        return $data;
      }   
      else
      {
        throw new Exception(JText::sprintf('COM_AFTMS_IMPORT_COURSES_WARNING_SAVE_FAILED', $row ), 2006);
      } 
    }
    else 
    {
      throw new Exception(JText::_('COM_AFTMS_IMPORT_COURSE_ERROR_SESSION_DATA'), 2007);
    }
  }
  
  
  
  public function loadInputData()
  {
    JSession::checkToken() or jexit(JText::_('JINVALID_TOKEN'));
    
    $session = JFactory::getSession();
    
		$app = JFactory::getApplication();
    $input = $app->input;
    
    $formData = new JRegistry($input->get('jform', '', 'array')); 
    jimport('joomla.filesystem.file');
    
    $files = $input->files->get('jform');
    $file = $files['file'];
    
    $data=array();
    
    $session->set('importcourses.session', $formData->get('sessionid', 0));
    $session->set('importcourses.classroom', $formData->get('classroomid', 0));
    $session->set('importcourses.update', $formData->get('update', 0));
    
    
    if(is_array($file))
    {    
      $inputFileName = $file['tmp_name'];

      //  Read the Excel workbook
      try 
      {
        $objPHPExcel = PHPExcel_IOFactory::load($inputFileName);
      } 
      catch(Exception $e) 
      {
        $this->setError($e->getMessage());
        return false;
      }

      //  Get worksheet dimensions
      $sheet = $objPHPExcel->getSheet(0); 
      $data['course_nb'] = $sheet->getHighestRow(); 
	  
      $data['file'] = $file['tmp_name'];
      
      $session->set('importcourses.course_nb', $data['course_nb']);
      $session->set('importcourses.sheet', serialize($sheet));
    }
    else
    {
      $this->setError('COM_AFTMS_IMPORT_COURSES_ERROR_INVALID_DATA');
      return false;
    }
    
    return $data;
  }
  
	public function getForm($data = array(), $loadData = true)
	{
		// Get the form.
		$form = $this->loadForm('com_aftms.importcourses', 'importcourses', array('control' => 'jform', 'load_data' => $loadData));
		if (empty($form))
		{
			return false;
		}

		return $form;
	}

	/**
	 * Method to get the data that should be injected in the form.
	 *
	 * @return  array  The default data is an empty array.
	 * @since   1.6
	 */
	protected function loadFormData()
	{
		// Check the session for previously entered form data.
		$app  = JFactory::getApplication();
		$data = $app->getUserState('com_aftms.importcourses.form.data', array());
    
		$this->preprocessData('com_aftms.importcourses', $data);

		return $data;
	}

	/**
	 * Method to auto-populate the model state.
	 *
	 * Note. Calling getState in this method will result in recursion.
	 *
	 * @since   1.6
	 */
	protected function populateState()
	{
		// Get the application object.
		//$params	= JFactory::getApplication()->getParams('com_aftms');

		// Load the parameters.
		//$this->setState('params', $params);
	}

	/**
	 * Override JModelAdmin::preprocessForm to ensure the correct plugin group is loaded.
	 *
	 * @param   JForm   $form   A JForm object.
	 * @param   mixed   $data   The data expected for the form.
	 * @param   string  $group  The name of the plugin group to import (defaults to "content").
	 *
	 * @return  void
	 *
	 * @since   1.6
	 * @throws  Exception if there is an error in the form event.
	 */
	protected function preprocessForm(JForm $form, $data, $group = 'content')
	{
		parent::preprocessForm($form, $data, $group);
	}
}
