jQuery(document).ready(function($) 
{
  var courseNb = jQuery('#course_nb').val(); 
  
  var success = 0;
  var error = 0;
  var warning = 0;
  var i = 1;
  
  if(courseNb > 0)
  {
    importcourse();
  }
  
  function importcourse()
  {
    update= 0;
    
    jQuery.ajax({
        url: 'index.php?option=com_aftms&task=importcourses.import&format=json',
        data: [{ name: "row", value: i }],
        
        error: function(jqXHR, textStatus, errorThrown){
          console.log('error # ' + textStatus + ': ' + errorThrown);

          jQuery('#messages').append(
                  '<div class="alert alert-error"><span class="icon-remove"></span> ' + 
                    Joomla.JText._('COM_AFTMS_IMPORT_COURSES_IMPORT_FAILED') + 
                    '<br/>' + textStatus + ' ' + errorThrown + 
                  '</div>');

          error++; 
          $('.bar.bar-danger').width(String((100 / courseNb) * error) + '%');

        },
        
        success: function(data, textStatus, jqXHR)
        {
          console.log(JSON.stringify(data, null, '\t'));

          if(data['success'] != true)
          {
            jQuery('#messages').append(
                    '<div class="alert alert-warning"><span class="icon-warning"></span> ' + 
                      data['message'] +
                    '</div>');

            warning++; 
            $('.bar.bar-warning').width(String((100 / courseNb) * warning) + '%');
          }
          else
          {
            jQuery('#messages').append(
                    '<div class="alert alert-success"><span class="icon-ok"></span> ' + 
                      data['data']['message'] +
                    '</div>');
            success++; 
            $('.bar.bar-success').width(String((100 / courseNb) * success) + '%');
          }
        },
        
        complete: function(jqXHR, textStatus)
        {
          if(success + error + warning == courseNb)
          {
            $('.progress.progress-striped').removeClass('active');

            $('#current-status').html('<h3>' + Joomla.JText._('COM_AFTMS_IMPORT_COURSES_IMPORT_FINISHED') + '</h3>');
            if(success <= 0)
            {
              $('#current-status').append('<p>' + Joomla.JText._('COM_AFTMS_IMPORT_COURSES_NO_COURSE_IMPORTED') + '</p>');
            }
            else
            {
              $('#current-status').append('<p>' + success + ' ' + Joomla.JText._('COM_AFTMS_IMPORT_COURSES_X_COURSE_IMPORTED') + '</p>');
            }

            if(error + warning > 0)
            {
              $('#current-status').append('<p><strong>' + String(error + warning) + ' ' + Joomla.JText._('COM_AFTMS_IMPORT_COURSES_X_COURSE_ERROR') + '</strong></p>');
            }

            $('#current-status').append('<p><a href="index.php?option=com_aftms&view=importcourses" class="btn btn-primary">' + 
                      Joomla.JText._('COM_AFTMS_IMPORT_COURSES_GOT_IT') + 
                    '</a></p>'); 
          }
          else
          {
            i++;
            importcourse();
          }
        }
      });
  }
  
});
