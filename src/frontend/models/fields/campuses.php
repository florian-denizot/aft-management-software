<?php
/**
 * @package     Joomla.Administrator
 * @subpackage  com_banners
 *
 * @copyright   Copyright (C) 2005 - 2014 Open Source Matters, Inc. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */

defined('JPATH_BASE') or die;

JFormHelper::loadFieldClass('checkboxes');

require_once __DIR__ . '/../../helpers/aftms.php';

class JFormFieldCampuses extends JFormFieldCheckboxes
{
	protected $type = 'Campuses';

	/**
	 * Method to get the field options.
	 *
	 * @return  array  The field option objects.
	 *
	 * @since   1.6
	 */
	public function getOptions()
	{    
    $lang = $this->getAttribute('language', false);
    
		$options = AFTMSHelper::getCampusOptions($lang);    
    
		return array_merge(parent::getOptions(), $options);
	}
}
