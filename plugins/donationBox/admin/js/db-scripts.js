jQuery(document).ready(
        function() 
    {
        
        
        
        jQuery('#rm_css').click(
                function()
        {
                        jQuery('#current_css_file').fadeOut(500 , function() 
                        {
                        jQuery('#current_css_file').html(
                                '<div class="alert alert-success alert-dismissible" role="alert"> <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>  <strong>Success!</strong> The style sheet file, after update/save this post, will be <u>permanently</u> removed.</div><input type="hidden" name="remove_css" id="remove_css" value="true" />').fadeIn(500);
                        }
                    );

        }
                );


    }
);