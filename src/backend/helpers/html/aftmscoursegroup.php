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
 * AFTMS Coursegroup HTML helper
 */
abstract class JHtmlAFTMSCoursegroup
{
	/**
	 * Render the list of associated items
	 *
	 * @param   int  $coursegroupid  The coursegroup item id
	 *
	 * @return  string  The language HTML
	 */
	public static function association($coursegroupid)
	{
		// Defaults
		$html = '';

		// Get the associations
		if ($associations = JLanguageAssociations::getAssociations('com_aftms', '#__aftms_course_groups', 'com_aftms.coursegroup', $coursegroupid, 'id', 'alias', null))
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
				->from('#__aftms_course_groups as c')
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
					$url = JRoute::_('index.php?option=com_aftms&task=coursegroup.edit&id=' . (int) $item->id);
					$tooltipParts = array(
						JHtml::_('image', 'mod_languages/' . $item->image . '.gif',
							$item->language_title,
							array('title' => $item->language_title),
							true
						),
						$item->title
					);
					$item->link = JHtml::_(
            'tooltip', 
            implode(' ', $tooltipParts), 
            null, 
            null, 
            $text, 
            $url, 
            null, 
            'hasTooltip label label-association label-' . $item->lang_sef
          );
				}
			}

			$html = JLayoutHelper::render('joomla.content.associations', $items);
		}

		return $html;
	}
}
