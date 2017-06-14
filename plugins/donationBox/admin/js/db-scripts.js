jQuery(document).ready(
function() 
{
    jQuery('#rm_css').click(
    function()
    {
        jQuery('#current_css_file').fadeOut(500 , 
        function() 
        {
            jQuery('#current_css_file').html(
                '<div class="alert alert-success alert-dismissible" role="alert"> <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>  <strong>Success!</strong> The style sheet file, after update/save this post, will be <u>permanently</u> removed.</div><input type="hidden" name="remove_css" id="remove_css" value="true" />').fadeIn(500);
        }
        );

    }
    );
    

    jQuery('#db_preview_button').click( function ( event )
    {
        var post_id = jQuery('#db_preview_button').attr('name');

        var url = '/wp-content/themes/influence-child/templates/template-portrait_mode.php?db_preview_id=';
        url += post_id;
        
         
        if ( jQuery('#save-post').length ) // If "Save Draft" button exists!
        {
            jQuery('#save-post').click(); // Save first as Draft
        } 
        
        window.open( url ,
                    'popUpWindow',
                    'height=900,width=1600,left=10,top=10,,scrollbars=yes,menubar=no');


        event.preventDefault();
        event.stopPropagation();
        return false;
    });

}
);

