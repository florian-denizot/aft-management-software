jQuery.noConflict();

jQuery(document).ready(function($) 
{
  // activate Twitter bootstrap popovers
  $('[rel=popover]').popover();
  
  $('div[id^="courses_"]').each(function() {
  
    $(this).on('show.bs.collapse', function () {
      var id = this.id.substr(8);
     
      $('#course_group_'+id).removeClass('col-md-3');
      $('#course_group_'+id).removeClass('col-sm-4');
      $('#course_group_'+id).addClass('col-md-12');
      $('#course_group_'+id).addClass('col-sm-12');      
    });
    
    $(this).on('shown.bs.collapse', function () {
      var id = this.id.substr(8);
     
      $('#course_group_'+id+' .coursegroup_collapse_btn a>i').removeClass('fa-plus');
      $('#course_group_'+id+' .coursegroup_collapse_btn a>i').addClass('fa-minus');
      
      var targetOffset = $('#course_group_'+id).offset().top;
      $('html,body').animate({scrollTop: targetOffset}, 500);
    });
    
    $(this).on('hide.bs.collapse', function (){
      var id = this.id.substr(8);
      
      $('#course_group_'+id).addClass('col-md-3');
      $('#course_group_'+id).addClass('col-sm-4');
      $('#course_group_'+id).removeClass('col-md-12');
      $('#course_group_'+id).removeClass('col-sm-12');
    });
    
    $(this).on('hidden.bs.collapse', function () {
      var id = this.id.substr(8);
      
      $('#course_group_'+id+' .coursegroup_collapse_btn a>i').removeClass('fa-minus');
      $('#course_group_'+id+' .coursegroup_collapse_btn a>i').addClass('fa-plus');
      var targetOffset = $('#course_group_'+id).offset().top;
      $('html,body').animate({scrollTop: targetOffset}, 500);
    });
    
  });
  
  
  // Setup table sorter
  $.tablesorter.addParser({
    // set a unique id
    id: 'date',
    is: function(s) {
      return false;
    },
    format: function(s, table, cell, cellIndex) {
      return $(cell).data('date') || s;
    },
    // flag for filter widget (true = ALWAYS search parsed values; false = search cell text)
    parsed: false,
    // set type, either numeric or text
    type: 'text'
  });
  
  $('.table-sorter').tablesorter(); 
  
  // add the correct course name to input in the form
  $('a.modalbutton').each(function() {
    $(this).click(function() {
      $('#course_name').val($(this).attr('course_name')); 
      return true;
    });
  });
  
  //send email
  if($('#letmeknowform'))
  {
    $('#letmeknowform').submit(function(event){
			
      event.preventDefault();
      var $btn = $('#sendData').button('loading');
      $('#success-message').addClass('hidden');
      $('#error-message').addClass('hidden');

      $.ajax({
        method: 'POST',
        url: 'index.php?format=raw&option=com_aftms&task=coursegroup.sendmail',
        data: $('#letmeknowform').serialize(),
        dataType: 'json'
      })
      .done(function(data) {
        
       if(data.error)
        {
          $('#error-message').append( data.error );
          $('#error-message').removeClass('hidden');
        }
        else
        {
          if(data.success)
          {
            $('#success-message').append( data.success );
            $('#success-message').removeClass('hidden');
          }
        }
      })
      .always(function() {
        grecaptcha.reset();
        $btn.button('reset');
      });
    });
  }
  
});