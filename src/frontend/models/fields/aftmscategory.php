<?php
/**
 * @package     Joomla.Legacy
 * @subpackage  Form
 *
 * @copyright   Copyright (C) 2005 - 2014 Open Source Matters, Inc. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE
 */

defined('JPATH_PLATFORM') or die;

JFormHelper::loadFieldClass('checkboxes');

/**
 * Form Field class for the Joomla Platform.
 * Supports an HTML select list of categories
 *
 * @package     Joomla.Legacy
 * @subpackage  Form
 * @since       11.1
 */
class JFormFieldAFTMSCategory extends JFormFieldCheckboxes
{
	/**
	 * The form field type.
	 *
	 * @var    string
	 * @since  11.1
	 */
	public $type = 'AFTMSCategory';

	/**
	 * Method to get the field options for category
	 * Use the extension attribute in a form to specify the specific extension for
	 * which categories should be displayed.
	 * Use the show_root attribute to specify whether to show the global category root in the list.
	 *
	 * @return  array    The field option objects.
	 */
	protected function getOptions()
	{    
		$extension = $this->element['extension'] ? (string) $this->element['extension'] : (string) $this->element['scope'];
		$published = (string) $this->element['published'];
		$language = (string) $this->element['language'];
    $currentLang = JFactory::getLanguage();

    $config = array();
    if ($published)
    {
      $config['filter.published'] = explode(',', $published);
    } 
    
    if ($language)
    {
      $config['filter.language'] = array($currentLang->getTag(), '*');
    }
    
		// Load the category options for a given extension.
		if (!empty($extension))
		{
			$options = JHtml::_('category.options', $extension, $config);
      
			// Verify permissions.  If the action attribute is set, then we scan the options.
			if ((string) $this->element['action'])
			{
				// Get the current user object.
				$user = JFactory::getUser();

				foreach ($options as $i => $option)
				{          
          /*
					 * To take save or create in a category you need to have create rights for that category
					 * unless the item is already in that category.
					 * Unset the option if the user isn't authorised for it. In this field assets are always categories.
					 */
					if ($user->authorise('core.create', $extension . '.category.' . $option->value) != true)
					{
						unset($options[$i]);
					}
				}
        
			}
		}
		else
		{
			JLog::add(JText::_('JLIB_FORM_ERROR_FIELDS_CATEGORY_ERROR_EXTENSION_EMPTY'), JLog::WARNING, 'jerror');
		}

		// Merge any additional options in the XML definition.
		$options = array_merge(parent::getOptions(), $options);

		return $options;
	}
}
