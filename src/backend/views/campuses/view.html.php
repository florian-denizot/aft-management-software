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
 * Campus list view class
 */
class AFTMSViewCampuses extends DradAdminViewList
{

  protected function addSubmenu()
  {    
    AFTMSHelper::addSubmenu('campuses');
  }	
}
