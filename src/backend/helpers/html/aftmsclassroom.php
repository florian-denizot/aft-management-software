<?php
/**
 * @package     Joomla.Administrator
 * @subpackage  com_aftms
 *
 * @copyright   Copyright (C) 2016 Alliance Francaise Toronto. All rights reserved.
 * @license     LTBD
 */

defined('_JEXEC') or die;

JLoader::register('AFTMSHelper', JPATH_ADMINISTRATOR . '/components/com_aftms/helpers/aftms.php');

/**
 * AFTMS Classrom HTML helper
 */
abstract class JHtmlAFTMSClassroom
{
	/**
	 * Render the list of associated items
	 *
	 * @param   int  $classroomid  The classroom item id
	 *
	 * @return  string  The language HTML
	 */
	public static function association($classroomid)
	{
		// Defaults
		$html = '';
    
		// Get the associations
		if ($associations = JLanguageAssociations::getAssociations('com_aftms', '#__aftms_classrooms', 'com_aftms.classroom', $classroomid, 'id', 'alias', null))
		{
			foreach ($associations as $tag => $associated)
			{
				$associations[$tag] = (int) $associated->id;
			}

			// Get the associated menu items
			$db = JFactory::getDbo();
			$query = $db->getQuery(true)
				->select('c.*')
				->select('l.sef as lang_sef')
				->from('#__aftms_classrooms as c')
        ->select('ca.title as campus_title')
				->join('LEFT', '#__aftms_campuses as ca ON ca.id=c.campusid')
				->where('c.id IN (' . implode(',', array_values($associations)) . ')')
				->join('LEFT', '#__languages as l ON c.language=l.lang_code')
				->select('l.image')
				->select('l.title as language_title');
			$db->setQuery($query);

			try
			{
				$items = $db->loadObjectList('id');
			}
			catch (RuntimeException $e)
			{
				throw new Exception($e->getMessage(), 500);
			}

			if ($items)
			{
				foreach ($items as &$item)
				{
					$text = strtoupper($item->lang_sef);
					$url = JRoute::_('index.php?option=com_aftms&task=classroom.edit&id=' . (int) $item->id);
					$tooltipParts = array(
						JHtml::_('image', 'mod_languages/' . $item->image . '.gif',
							$item->language_title,
							array('title' => $item->language_title),
							true
						),
						$item->title,
            '(' . $item->campus_title . ')'
					);
					$item->link = JHtml::_(
            'tooltip', 
            implode(' ', $tooltipParts), 
            null, 
            null, 
            $text, 
            $url, 
            null, 
            'hasTooltip label label-association label-' . $item->lang_sef);
				}
			}

			$html = JLayoutHelper::render('joomla.content.associations', $items);
		}

		return $html;
	}
}
