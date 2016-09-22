<?php
/**
 * @package     Joomla.Administrator
 * @subpackage  com_aftms
 *
 * @copyright   Copyright (C) 2016 Alliance Francaise Toronto. All rights reserved.
 * @license     LTBD
 */

defined('JPATH_BASE') or die;

JFormHelper::loadFieldClass('list');

require_once __DIR__ . '/../../helpers/aftms.php';

/**
 * Campus Field class for the AFTMS component.
 */
class JFormFieldCampuses extends JFormFieldList
{
	/**
	 * The form field type.
	 *
	 * @var		string
	 */
	protected $type = 'Campuses';

	/**
	 * Method to get the field options.
	 *
	 * @return  array  The field option objects.
	 */
	public function getOptions()
	{    
    $lang = $this->getAttribute('language', false);
    
		$options = AFTMSHelper::getCampusOptions($lang);    
    
		return array_merge(parent::getOptions(), $options);
	}
}
