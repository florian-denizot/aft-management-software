<?php
/**
 * @package     Joomla.Administrator
 * @subpackage  com_aftms
 *
 * @copyright   Copyright (C) 2005 - 2014 Open Source Matters, Inc. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */

defined('JPATH_BASE') or die;

JFormHelper::loadFieldClass('list');

/**
 * Bannerclient Field class for the Joomla Framework.
 *
 * @package     Joomla.Administrator
 * @subpackage  com_aftnl
 * @since       1.6
 */
class JFormFieldMinutes extends JFormFieldList
{
	/**
	 * The form field type.
	 *
	 * @var		string
	 * @since   1.6
	 */
	protected $type = 'Minutes';

	/**
	 * Method to get the field options.
	 *
	 * @return  array  The field option objects.
	 *
	 * @since   1.6
	 */
	public function getOptions()
	{
    for($i = 0; $i < 60 ; $i++)
    {
      $value = (string)$i;
      if( $i < 10 )
      {
        $value = '0' . $value;
      }
      
      $options[] = array('value' => $value, 'text' => $value); 
    }
		return array_merge(parent::getOptions(), $options);
	}
}
