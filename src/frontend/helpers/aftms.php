<?php defined('_JEXEC') or die; 

/**
 * AFTMS component helper.
 *
 * @package     Joomla.Site
 * @subpackage  com_aftms
 */
 
class AFTMSHelper extends JHelperContent
{

  public static function getFullDayName($shortDay)
  {
    $dayArray = array(
      'COM_AFTMS_MON' => jText::_('COM_AFTMS_MONDAY'),
      'COM_AFTMS_TUE' => jText::_('COM_AFTMS_TUESDAY'),
      'COM_AFTMS_WED' => jText::_('COM_AFTMS_WEDNESDAY'),
      'COM_AFTMS_THU' => jText::_('COM_AFTMS_THURSDAY'),
      'COM_AFTMS_FRI' => jText::_('COM_AFTMS_FRIDAY'),
      'COM_AFTMS_SAT' => jText::_('COM_AFTMS_SATURDAY'),
      'COM_AFTMS_SUN' => jText::_('COM_AFTMS_SUNDAY')
    );
    
    if(array_key_exists($shortDay ,$dayArray ))
    {
      return $dayArray[$shortDay];
    }
    else
    {
      return false;
    }
  }
  
  public static function getSimpleLevelLabel($id)
  {
    $levelArray = array(
      1 => jText::_('COM_AFTMS_BEGINNER'),
      2 => jText::_('COM_AFTMS_INTERMEDIATE'),
      3 => jText::_('COM_AFTMS_ADVANCED')
    );
    
    return $levelArray[$id];
  }
  
  /**
    * Generate a readable string representing the age range for a coursegroup
    *
    * @param int $minAgeMonth
    * @param int $minAgeYear
    * @param int $maxAgeMonth
    * @param int $maxAgeYear
    * @return String
    */
  public static function getAgeRangeLabel($minAgeMonth, $minAgeYear, $maxAgeMonth, $maxAgeYear)
  {
    if((int)$minAgeYear >= 18)
    {
      return jText::_('COM_AFTMS_ADULTS');
    }
    else
    {
      $ageRangeString = '';
      
      $minAgeMonthText = '';
      $minAndText = '';
      $minAgeYearText = '';
      $maxAgeMonthText = '';
      $maxAndText = "";
      $maxAgeMonthText = '';
      
      if((int)$minAgeMonth)
      {
        $minMonthText = jText::sprintf('COM_AFTMS_AGE_RANGE_MONTHS', $minAgeMonth);
      }
      
      if((int)$minAgeYear)
      {
        $minYearText = $minAgeYear;
      }
      
      if((int)$minAgeMonth && (int)$minAgeYear)
      {
        $minAndText = ' and ';
        $minYearText = jText::sprintf('COM_AFTMS_AGE_RANGE_YEARS', $minAgeYear);
      }
      
      if((int)$maxAgeMonth)
      {
        $maxMonthText = jText::sprintf('COM_AFTMS_AGE_RANGE_MONTHS', (int)$maxAgeMonth);
      }
      
      if((int)$maxAgeYear)
      {
        $maxYearText = $maxAgeYear;
      }
      
      if((int)$maxAgeMonth && (int)$maxAgeYear)
      {
        $maxAndText = ' and ';
        $maxYearText = jText::sprintf('COM_AFTMS_AGE_RANGE_YEARS', (int)$maxAgeYear);
      }
      
      $ageRangeString = $minYearText . $minAndText . $minMonthText . ' - ' . $maxYearText . $maxAndText . $maxMonthText;
      
      return $ageRangeString;
    }
  }

  public static function displayDatePatterns($date_patterns = array())
  {
    $result = '';
    $organizer = array();
  
    if(is_array($date_patterns) &&
      count($date_patterns) && 
      is_array($date_patterns['weekday']) && 
      is_array($date_patterns['start_hour']) &&
      is_array($date_patterns['start_min']) && 
      is_array($date_patterns['end_hour']) && 
      is_array($date_patterns['end_min']))
    {
      for($i=0 ; $i < count($date_patterns['weekday']) ; $i++)
      {
        $day = $date_patterns['weekday'][$i];
        
        // format the times
        $start_time = new JDate('0000-00-00 ' . $date_patterns['start_hour'][$i] . ':' . $date_patterns['start_min'][$i] . ':00');
        $end_time = new JDate('0000-00-00 ' . $date_patterns['end_hour'][$i] . ':' . $date_patterns['end_min'][$i] . ':00');

        $times = JText::_('COM_AFTMS_FROM') .' '. $start_time->format(JText::_('COM_AFTMS_TIME_FORMAT_1')) .' '. 
          JText::_('COM_AFTMS_TO') .' '. $end_time->format(JText::_('COM_AFTMS_TIME_FORMAT_1'));
        
        // if fill the organizer first line
        if(!count($organizer))
        {
          $organizer[] = array('days' => array($day), 'times' => array( $times ));
        }
        else
        {
          $found = false;
          foreach($organizer as $block)
          {
            // if weekday in organizer just add the times
            if(in_array($day, $block['days']))
            {
              $block['times'][] = $times;
              $found = true;
              break;
            }
          }
          
          // if weekday not in organizer add a full new line
          if(!$found)
          {
            $organizer[] = array('days' =>array($day), 'times' => array( $times ));
          }
        }
      }
      
      for($i = 0; $i < count($organizer); $i++)
      {
        for($j = $i+1; $j < count($organizer); $j++)
        {
          if(!count(array_diff($organizer[$i]['times'], $organizer[$j]['times'])))
          {
            $organizer[$i]['days'] = array_merge($organizer[$i]['days'], $organizer[$j]['days']);
            unset($organizer[$j]);
            $organizer = array_values($organizer);
            $j--;
          }
        }
      }
      
      for($b=0; $b < count($organizer); $b++)
      {
        for($d=0; $d < count($organizer[$b]['days']); $d++)
        {
          $result .= AFTMSHelper::getFullDayName($organizer[$b]['days'][$d]);
          
          if($d+2 == count($organizer[$b]['days']))
          {
            $result .= ' ' . JText::_('COM_AFTMS_AND') . ' ';
          }
          elseif($d+1 == count($organizer[$b]['days']))
          {
            $result .= ' ';
          }
          else
          {
            $result .= ', ';
          }
        }
        
        for($t=0; $t < count($organizer[$b]['times']); $t++)
        {
          $result .= $organizer[$b]['times'][$t];
          
          if($t+2 == count($organizer[$b]['times']))
          {
             $result .= ' ' . JText::_('COM_AFTMS_AND') . ' ';
          }
          elseif($t+1 == count($organizer[$b]['times']))
          {
            $result .= ' ';
          }
          else
          {
            $result .= ', ';
          }
        }
        $result .= '<br/>';
      }
    }
    
    return $result;
  } 
  
  
  /**
	 * Get centre list in text/value format for a select field
	 *
	 * @return  array
	 */
	public static function getCampusOptions($lang = '')
	{ 
		$options = array();

		$db = JFactory::getDbo();
		$query = $db->getQuery(true)
			->select('id As value, title As text')
			->from('#__aftms_campuses AS a')
			->order('a.title');
      
    if($lang)
    {
      $language = JFactory::getLanguage();
      $query->where('a.language IN ("'.$language->getTag().'", "*")');
    }

		// Get the options.
		$db->setQuery($query);

		try
		{
			$options = $db->loadObjectList();
		}
		catch (RuntimeException $e)
		{
			JError::raiseWarning(500, $e->getMessage());
		}

		return $options;
	}
}