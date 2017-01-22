<script>
  $(function() {
  
  /** validate comments form
   *  don't allow add empty comment
   */
   
    $("#fields_form").validate({
      ignore:'', 
      errorClass:'error-none',
      rules:{
      
        //boefre validate update ckeditor element
        description: {          
          normalizer: function( value ) {            
            CKEDITOR_holders["description"].updateElement();
          }
        },
        
        //custom check for attachments field
        comments_attachments: {          
          normalizer: function( value ) {            
            if($('#uploadifive_attachments_list_attachments .attachments-form-list').length)
            {           
              $('#comments_attachments').val('1');
            }
          }
        },
      },
      
      invalidHandler: function(e, validator) {
  			var errors = validator.numberOfInvalids();
  			if (errors) {
  				var message = '<?php echo TEXT_ERROR_COMMENTS_FORM_GENERAL ?>';
  				$("div#form-error-container").html('<div class="alert alert-danger">'+message+'</div>');
  				$("div#form-error-container").show();
          $("div#form-error-container").delay(5000).fadeOut();                              				
  			} 
		  },
      
      errorPlacement: function(error, element) {
        //don't add erros near fields 
      }
      
    });
    
    //auto add class "required_group" for all form fields   
    $(".form-control").each(function(){
      $(this).addClass("required_group");
    }) 
      
    //custom validate method    
    jQuery.validator.addMethod("required_group", function(val, el) { 
       return $("#fields_form").find('.required_group:filled').length; 
    });   
    
    //custom validate rule
    jQuery.validator.addClassRules('required_group', { 'required_group' : true });
                                                                            
  });
    
</script>