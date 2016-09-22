<?php
/**
 * @package     Joomla.Site
 * @subpackage  com_content
 *
 * @copyright   Copyright (C) 2005 - 2014 Open Source Matters, Inc. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */

defined('_JEXEC') or die;

if(!defined('DS'))
{
	define('DS', DIRECTORY_SEPARATOR);
}

JHtml::_('behavior.tabstate');

require_once JPATH_COMPONENT.'/helpers/route.php';
JLoader::register('AFTMSHelper', __DIR__ . '/helpers/aftms.php');

$controller = JControllerLegacy::getInstance('AFTMS');
$controller->execute(JFactory::getApplication()->input->get('task'));
$controller->redirect();
