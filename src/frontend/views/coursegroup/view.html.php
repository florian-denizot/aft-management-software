<?php

defined('_JEXEC') or die;

require_once(JPATH_COMPONENT.DS.'lib'.DS.'views'.DS.'viewlist.php');

/**
 * HTML View class for the AFT Coursegroups component
 *
 * @package     Joomla.Site
 * @subpackage  com_aftms
 */
class AFTMSViewCoursegroup extends JViewLegacy
{

  /**
	 * Execute and display a template script.
	 *
	 * @param   string  $tpl  The name of the template file to parse; automatically searches through the template paths.
	 *
	 * @return  mixed  A string if successful, otherwise a Error object.
	 */
  public function display($tpl = null)
	{
    $app		      = JFactory::getApplication('site');
    $this->state	= $this->get('State');
    $this->item		= $this->get('Item');
    $active       = $app->getMenu()->getActive();
    
    
    // Check for errors.
		if (count($errors = $this->get('Errors')))
		{
			JError::raiseWarning(500, implode("\n", $errors));
			return false;
		}

		// Create a shortcut for $item.
		$item = $this->item;
    
    
    // Load the component parameters.
    $this->params = $app->getParams();
    
		$active = $app->getMenu()->getActive();
		$temp = clone ($this->params);
    
    
    // Check to see which parameters should take priority
		/*if ($active)
		{
			$currentLink = $active->link;

			// If the current view is the active item and an coursegroup view for this coursegroup, then the menu item params take priority
			if (strpos($currentLink, 'view=coursegroup') && (strpos($currentLink, '&id='.(string) $item->id)))
			{
				// $item->params are the coursegroup params, $temp are the menu item params
				// Merge so that the menu item params take priority
        //var_dump($item->params);
				//$item->params->merge($temp);
			}
			else
			{
				// Current view is not a single coursegroup, so the coursegroup params take priority here
				// Merge the menu item params with the coursegroup params so that the coursegroup params take priority
				$temp->merge($item->params);
				$item->params = $temp;
			}
		}
		else
		{
			// Merge so that coursegroup params take priority
			$temp->merge($item->params);
			$item->params = $temp;
		}*/
    
    $this->_prepareDocument();
    
    return parent::display($tpl);
  }
  
  /**
	 * Prepares the document
	 */
	protected function _prepareDocument()
	{
		$app		= JFactory::getApplication();
		$menus		= $app->getMenu();
		$pathway	= $app->getPathway();
		$title		= null;

		// Because the application sets a default page title,
		// we need to get it from the menu item itself
		$menu = $menus->getActive();

		if ($menu)
		{
			$this->params->def('page_heading', $this->params->get('page_title', $menu->title));
		}
		else
		{
			$this->params->def('page_heading', JText::_('JGLOBAL_ARTICLES'));
		}

		$title = $this->params->get('page_title', '');

		$id = (int) @$menu->query['id'];

		// if the menu item does not concern this article
		if ($menu && ($menu->query['option'] != 'com_aftms' || $menu->query['view'] != 'coursegroup' || $id != $this->item->id))
		{
			// If this is not a single article menu item, set the page title to the article title
			if ($this->item->title)
			{
				$title = $this->item->title;
			}
			$path = array(array('title' => $this->item->title, 'link' => ''));
			$category = JCategories::getInstance('AFTMS')->get($this->item->catid);

			while ($category && ($menu->query['option'] != 'com_aftms' || $menu->query['view'] == 'coursegroup' || $id != $category->id) && $category->id > 1)
			{
				$path[] = array('title' => $category->title, 'link' => AFTMSHelperRoute::getCategoryRoute($category->id));
				$category = $category->getParent();
			}
			$path = array_reverse($path);

			foreach ($path as $item)
			{
				$pathway->addItem($item['title'], $item['link']);
			}
		}

		// Check for empty title and add site name if param is set
		if (empty($title))
		{
			$title = $app->getCfg('sitename');
		}
		elseif ($app->getCfg('sitename_pagetitles', 0) == 1)
		{
			$title = JText::sprintf('JPAGETITLE', $app->getCfg('sitename'), $title);
		}
		elseif ($app->getCfg('sitename_pagetitles', 0) == 2)
		{
			$title = JText::sprintf('JPAGETITLE', $title, $app->getCfg('sitename'));
		}

		if (empty($title))
		{
			$title = $this->item->title;
		}
		$this->document->setTitle($title);

		if ($this->item->metadesc)
		{
			$this->document->setDescription($this->item->metadesc);
		}
		elseif (!$this->item->metadesc && $this->params->get('menu-meta_description'))
		{
			$this->document->setDescription($this->params->get('menu-meta_description'));
		}

		if ($this->item->metakey)
		{
			$this->document->setMetadata('keywords', $this->item->metakey);
		}
		elseif (!$this->item->metakey && $this->params->get('menu-meta_keywords'))
		{
			$this->document->setMetadata('keywords', $this->params->get('menu-meta_keywords'));
		}

		if ($this->params->get('robots'))
		{
			$this->document->setMetadata('robots', $this->params->get('robots'));
		}

		if ($app->getCfg('MetaAuthor') == '1')
		{
			$this->document->setMetaData('author', $this->item->author);
		}

		$mdata = $this->item->metadata->toArray();

		foreach ($mdata as $k => $v)
		{
			if ($v)
			{
				$this->document->setMetadata($k, $v);
			}
		}
	}
}
