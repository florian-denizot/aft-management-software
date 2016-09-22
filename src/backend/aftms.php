<?php
/**
 * @package     Joomla.Administrator
 * @subpackage  com_aftms
 *
 * @copyright   Copyright (C) 2016 Alliance Francaise Toronto. All rights reserved.
 * @license     LTBD
 */

defined('_JEXEC') or die;
JHtml::_('behavior.tabstate');

if(!defined('DS'))
{
	define('DS', DIRECTORY_SEPARATOR);
}

if (!JFactory::getUser()->authorise('core.manage', 'com_aftms'))
{
	return JError::raiseWarning(404, JText::_('JERROR_ALERTNOAUTHOR'));
}

JLoader::registerPrefix('Drad', JPATH_LIBRARIES . '/drad');
JLoader::register('AFTMSHelper', __DIR__ . '/helpers/aftms.php');

$controller = JControllerLegacy::getInstance('AFTMS');
$controller->execute(JFactory::getApplication()->input->get('task'));
$controller->redirect();
