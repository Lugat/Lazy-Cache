jQuery(function($) {
    
  $('a[href^="#"]', '#lazy-cache').click(function() {   
    $($(this).attr('href'), '#lazy-cache').show().siblings('div').hide();
  });
  
});