<?php
/**
 * @package     Joomla.Site
 * @subpackage  com_content
 *
 * @copyright   Copyright (C) 2005 - 2014 Open Source Matters, Inc. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */

defined('_JEXEC') or die;

/**
 * Content Component Category Tree
 *
 * @package     Joomla.Site
 * @subpackage  com_content
 * @since       1.6
 */
class AFTMSCategories extends JCategories
{
	public function __construct($options = array())
	{
		$options['table'] = '#__aftms_course_groups';
		$options['extension'] = 'com_aftms';

		parent::__construct($options);
	}
}
