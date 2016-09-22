<?php

defined('_JEXEC') or die;

/**
 * AFT Events Event model class
 *
 * @package     Joomla.Site
 * @subpackage  com_aftevents
 */
class AFTMSModelCoursegroup extends JModelItem
{
  protected $tableName = '#__aftms_course_groups';
  
  /**
	 * Constructor.
	 *
	 * @param   array  $config  An optional associative array of configuration settings.
	 *
	 * @since   1.6
	 * @see     JController
	 */
	public function __construct($config = array())
	{
		if (empty($config['filter_fields']))
		{
			$config['filter_fields'] = array(
       
			);
		}
    
		parent::__construct($config);
	}

	/**
	 * Method to auto-populate the model state.
	 *
	 * Note. Calling getState in this method will result in recursion.
	 *
	 * @param   string  $ordering   An optional ordering field.
	 * @param   string  $direction  An optional direction (asc|desc).
	 *
	 * @return  void
	 */
	protected function populateState($ordering = 'a.title', $direction = 'asc')
	{
    $app = JFactory::getApplication();
		$jinput = $app->input;
    
    $this->setState('request.id', $jinput->get('id', null));
    
		parent::populateState($ordering, $direction);
	}
  
  /**
	 * Method to get an item
	 *
	 * @return  mixed  An item on success, false on failure.
	 */
	public function getItem()
	{  
    $lang = JFactory::getLanguage();
    
    try
    {
      $db = $this->getDbo();
      $query = $db->getQuery(true);
      
      $id = $this->getState('request.id', null);
      
      $query->select('a.id, a.title, a.description, a.image_ext, a.params, a.metadata, a.metadesc, a.metakey')
          ->from('#__aftms_course_groups AS a')
          ->where('a.id = '. $id);
          
          
      // Join over the categories.
      $query->select('cat.title AS category_title, cat.params AS cat_params')
        ->join('LEFT', '#__categories AS cat ON cat.id = a.catid')
        ->where('cat.extension = "com_aftms.coursecategories"');
        
      // Join over the associations.
      $query->select('COUNT(asso2.id)>1 as association')
        ->join('LEFT', '#__associations AS asso ON asso.id = a.id AND asso.context=' . $db->quote('com_aftms.coursegroups'))
        ->join('LEFT', '#__associations AS asso2 ON asso2.key = asso.key')
        ->group('a.id');
        
      // Join on user table.
      $query->select('u.name AS author')
        ->join('LEFT', '#__users AS u on u.id = a.created_by');
        
      $db->setQuery($query);
      $item = $db->loadObject();
      
      if (empty($item))
      {
        return JError::raiseError(404, JText::_('COM_AFTMS_ERROR_EVENT_NOT_FOUND'));
      }
      
      $registry = new JRegistry;
      $registry->loadString($item->metadata);
      $item->metadata = $registry;
      
      
      // manage images
      if($item->image_ext)
      {
        $params = JComponentHelper::getParams('com_aftms');
        $params->toArray();
        
        $IEWidth = $params['image_coursegroup_width'];
        $IEHeight = $params['image_coursegroup_height'];
      
        $imageURL = JURI::base().'images/aftms/thumbs/'. $item->id .'_'. $IEWidth .'x'. $IEHeight .'.'. $item->image_ext;
        $imagePath = JPATH_ROOT.'/images/aftms/thumbs/'. $item->id .'_'. $IEWidth .'x'. $IEHeight .'.'. $item->image_ext;
        
        if(JFile::exists($imagePath))
        { 
          $item->image = $imageURL;
        }
      }
      
      //Get associated list of courses
      $query->clear();
      $query->select('b.id, b.title, b.start_date, b.end_date, b.date_pattern, b.classroomid, b.sessionid, b.price_override, b.url')
        ->from('#__aftms_courses AS b')
        ->where('b.published = 1')
        ->where('b.end_date > CURRENT_DATE')
        ->order('b.start_date');
      
      // Select courses from current course group and its language associations
      $groupAssocs = JLanguageAssociations::getAssociations('com_aftms', '#__aftms_course_groups', 'com_aftms.coursegroup', $item->id, 'id', 'alias', null); 
      foreach ($groupAssocs as $tag => $groupAssoc)
      {
        $slug = explode(':', $groupAssoc->id);
        $item->associations[$tag] = $slug[0];
      }
      
      $groups = $item->id;
      if(isset($item->associations) && count($item->associations))
      {
        $groups .= ',' . implode(',', $item->associations);
      } 
      $query->where('b.groupid IN (' . $groups . ')'); 
        
      $db->setQuery($query);
      
      $item->courses = $db->loadObjectList();
      
      if(count($item->courses))
      {
        foreach($item->courses as &$course)
        {
          // Transform string dates to JDate objects 
          $course->start_date = new JDate($course->start_date);
          $course->end_date = new JDate($course->end_date);
          
          //load date pattern field as an array
          $datePattern = new JRegistry;
          $datePattern->loadString($course->date_pattern);
          $course->date_pattern = $datePattern->toArray();
          
          $course->days_times = AFTMSHelper::displayDatePatterns($course->date_pattern);
          
          // Get language associations of the course classroom
          $roomAssocs = JLanguageAssociations::getAssociations('com_aftms', '#__aftms_classrooms', 'com_aftms.classroom', $course->classroomid, 'id', 'alias', null); 
          
          foreach ($roomAssocs as $tag => $roomAssoc)
          {
            $slug = explode(':', $roomAssoc->id);
            $roomAssocArray[$tag] = $slug[0];
          }
          
          // Select the current language association
          if(isset($roomAssocArray[$lang->getTag()]))
          {
            $course->classroomid = $roomAssocArray[$lang->getTag()];
          }
          elseif(isset($roomAssocArray['*']))
          {
            $course->classroomid = $roomAssocArray['*'];
          }
          
          $classroom = JTable::getInstance('Classrooms', 'AFTMSTable');
          $classroom->load($course->classroomid);
          
          $course->classroom_name = $classroom->title;
          
          $centre = JTable::getInstance('Centres', 'AFTMSTable');
          $centre->load($classroom->campusid);
          
          $course->centre_name = $centre->title;
        }
      }

    }
    catch (Exception $e)
    {
      if ($e->getCode() == 404)
      {
        // Need to go thru the error handler to allow Redirect to work.
        JError::raiseError(404, $e->getMessage());
      }
      else
      {
        $this->setError($e);
        $this->_item[$pk] = false;
      }
    }
    
    return $item;
	}
}
