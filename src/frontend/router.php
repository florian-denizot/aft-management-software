<?php
/**
 * @package     Joomla.Site
 * @subpackage  com_aftms
 *
 * @copyright   Copyright (C) 2005 - 2013 Open Source Matters, Inc. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */

defined('_JEXEC') or die;


/**
 * Routing class from com_aftms
 *
 * @package     Joomla.Site
 * @subpackage  com_aftms
 * @since       3.3
 */
class AFTMSRouter extends JComponentRouterBase
{

/**
	 * Build the route for the com_aftms component
	 *
	 * @param   array  &$query  An array of URL arguments
	 *
	 * @return  array  The URL arguments to use to assemble the subsequent URL.
	 *
	 * @since   3.3
	 */
  function build(&$query)
  {
    $app = JFactory::getApplication();
    $menu = $app->getMenu();
    $params = JComponentHelper::getParams('com_content');
    $advanced = $params->get('sef_advanced_link', 0);
    $segments = array();
    
    // We need a menu item.  Either the one specified in the query, or the current active one if none specified   
    if (empty($query['Itemid']))
    {
      $menuItem = $menu->getActive();
      $menuItemGiven = false;
    }
    else
    {
      $menuItem = $menu->getItem($query['Itemid']);
      $menuItemGiven = true;
    }

    // Check again
    if ($menuItemGiven && isset($menuItem) && $menuItem->component != 'com_aftms')
    {
      $menuItemGiven = false;
      unset($query['Itemid']);
    }
    
    if (isset($query['view']))
    {
      $view = $query['view'];
    }
    else
    {
      // We need to have a view in the query or it is an invalid URL
      return $segments;
    }
    
    // Are we dealing with an coursegroup list that is attached to a menu item?
		if (($menuItem instanceof stdClass) 
      && $menuItem->query['view'] == $query['view'] 
      && isset($query['min_year']) 
      && isset($query['min_month']) 
      && isset($query['max_year']) 
      && isset($query['max_month']) 
      && $menuItem->query['min_year'] == (int) $query['min_year']
      && $menuItem->query['min_month'] == (int) $query['min_month']
      && $menuItem->query['max_year'] == (int) $query['max_year']
      && $menuItem->query['max_month'] == (int) $query['max_month']
      )
		{
			unset($query['view']);

			if (isset($query['layout']))
			{
				unset($query['layout']);
			}

			unset($query['min_year']);
			unset($query['min_month']);
			unset($query['max_year']);
			unset($query['max_month']);

			return $segments;
		}
    
    if ($view == 'coursegroups')
    {
      if (!$menuItemGiven)
      {
        $segments[] = $view;
        unset($query['view']);
      }

      if(isset($query['min_year']))
      {
        $segments[] = $query['min_year'];
        unset( $query['min_year'] );
      };
      
      if(isset($query['min_month']))
      {
        $segments[] = $query['min_month'];
        unset( $query['min_month'] );
      };
      
      if(isset($query['max_year']))
      {
        $segments[] = $query['max_year'];
        unset( $query['max_year'] );
      };
      
      if(isset($query['max_month']))
      {
        $segments[] = $query['max_month'];
        unset( $query['max_month'] );
      }
    }
    
    
    
    
    /*
     * If the layout is specified and it is the same as the layout in the menu item, we
     * unset it so it doesn't go into the query string.
     */
    if (isset($query['layout']))
    {
      if ($menuItemGiven && isset($menuItem->query['layout']))
      {
        if ($query['layout'] == $menuItem->query['layout'])
        {
          unset($query['layout']);
        }
      }
      else
      {
        if ($query['layout'] == 'default')
        {
          unset($query['layout']);
        }
      }
    }
    
    return $segments;
  }

/**
	 * Parse the segments of a URL.
	 *
	 * @param   array  &$segments  The segments of the URL to parse.
	 *
	 * @return  array  The URL attributes to be used by the application.
	 *
	 * @since   3.3
	 */
  function parse(&$segments)
  {
    $total = count($segments);
    $vars = array();
    
    for ($i = 0; $i < $total; $i++)
    {
      $segments[$i] = preg_replace('/-/', ':', $segments[$i], 1);
    }

    // Get the active menu item.
    $app = JFactory::getApplication();
    $menu = $app->getMenu();
    $item = $menu->getActive();
    $params = JComponentHelper::getParams('com_content');
    $advanced = $params->get('sef_advanced_link', 0);
    
    /*
     * Standard routing.  If we don't pick up an Itemid then we get the view from the segments
     * the first segment is the view and the last segment is the id of the coursegroup or the course.
     */
    if (!isset($item))
    {
      $vars['view'] = $segments[0];
      $vars['id'] = $segments[$count - 1];

      return $vars;
    }

    
    switch($segments[0])
    {
      case 'coursegroups':
      
        $vars['view'] = 'coursegroups';
        
        $min_year = explode( ':', $segments[1] );
        $min_month = explode( ':', $segments[2] );
        $max_year = explode( ':', $segments[3] );
        $max_month = explode( ':', $segments[4] );
        
        $vars['min_year'] = (int) $min_year[0];
        $vars['min_month'] = (int) $min_month[0];
        $vars['max_year'] = (int) $max_year[0];
        $vars['max_month'] = (int) $max_month[0];
      break;
      
    case 'coursegroup':
      $vars['view'] = 'coursegroup';
      $id = explode( ':', $segments[1] );
      $vars['id'] = (int) $id[0];
     break;
   }
   
   return $vars;
  }
}

/**
 * AFTMS router functions
 *
 * These functions are proxys for the new router interface
 * for old SEF extensions.
 *
 * @deprecated  4.0  Use Class based routers instead
 */
function AFTMSBuildRoute(&$query)
{
	$router = new AFTMSRouter;

	return $router->build($query);
}

function AFTMSParseRoute($segments)
{
	$router = new AFTMSRouter;

	return $router->parse($segments);
}