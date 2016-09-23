<?php
/**
 * @package     Joomla.Site
 * @subpackage  com_aftms
 *
 * @copyright   Copyright (C) 2016 Alliance Francaise Toronto. All rights reserved.
 * @license     LTBD
 */

defined('_JEXEC') or die;

require_once JPATH_COMPONENT . '/helpers/route.php';

if(!defined('DS'))
{
	define('DS', DIRECTORY_SEPARATOR);
}

require_once JPATH_COMPONENT.'/helpers/route.php';
JLoader::registerPrefix('Drad', JPATH_LIBRARIES . '/drad');
// JLoader::register('AFTMSHelper', __DIR__ . '/helpers/aftms.php');

$controller = JControllerLegacy::getInstance('AFTMS');
$controller->execute(JFactory::getApplication()->input->get('task'));
$controller->redirect();
