<?php
/**
 * @package     Joomla.Administrator
 * @subpackage  com_aftms
 *
 * @copyright   Copyright (C) 2016 Alliance Francaise Toronto. All rights reserved.
 * @license     LTBD
 */

class AFTMSControllerImportCourses extends JControllerLegacy
{

  public function import()
  {
    $input = JFactory::getApplication()->input;
    
    $row = $input->get('row');
    
    try
    {
      $result = $this->getModel('ImportCourses', 'AFTMSModel')->importCourseFromFile($row);
 
      echo new JResponseJson($result);
    }
    catch(Exception $e)
    {
      echo new JResponseJson($e);
    }
  }
}