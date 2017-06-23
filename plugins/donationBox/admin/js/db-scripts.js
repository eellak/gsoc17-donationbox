jQuery(document).ready(
function() 
{
    
    /*
     * This function checked how many organizations the user selected.
     * If he has chosen more than one, return false , else return true.
     * 
     * @return {boolean}
     */
    function organizations_check()
    {
    var how_many_times = 0;

    jQuery('ul#organizationchecklist li').each(
    function()
    {
        if ( jQuery(this).find('input').is(':checked') )
        {
            this.style.color = "red";
            how_many_times++;
        }
        else
        {
            this.style.color = '';
        }

    }
    );

    if ( how_many_times > 1 )
    {
        var message = 'Sorry, you can choose *only one* organization for each donation project.\nYou choose ';
        message += how_many_times;
        message += ' organizations!\nFix it and then go ahead with saving of the donation project. :)';
        alert(message);
        return false; // Stop submitting.
    }

    return true; // Continue to submitting.
    }
    
    
    
    
    /*
     * Function that is triggered when the user attempts to delete the uploaded css file.
     * Notifies user about what is going to happen.
     * Everything is done with a nice smoothly effect.
     */
    jQuery('#rm_css').click(
    function()
    {
        jQuery('#current_css_file').fadeOut(500 , 
        function() 
        {
            var html_code = '<div class="alert alert-success alert-dismissible" role="alert">';
            html_code += '<button type="button" class="close" data-dismiss="alert" aria-label="Close">';
            html_code += '<span aria-hidden="true">&times;</span>';
            html_code += '</button>';
            html_code += '<strong>Success!</strong> The style sheet file, after update/save this post, will be <u>permanently</u> removed.';
            html_code += '</div>';
            html_code += '<input type="hidden" name="remove_css" id="remove_css" value="true" />';
            
            jQuery('#current_css_file').html(html_code).fadeIn(500);
        }
        );

    }
    );
    
    
    
    
    
    /*
     * Function that is triggered when the user attempts to delete the uploaded video file.
     * Notifies user about what is going to happen.
     * Everything is done with a nice smoothly effect.
     */
    jQuery('#rm_video').click(
    function()
    {
        jQuery('#current_video_file').fadeOut(500 , 
        function() 
        {
            var html_code = '<div class="alert alert-success alert-dismissible" role="alert">';
            html_code += '<button type="button" class="close" data-dismiss="alert" aria-label="Close">';
            html_code += '<span aria-hidden="true">&times;</span>';
            html_code += '</button>';
            html_code += '<strong>Success!</strong> The video file, after update/save this post, will be <u>permanently</u> removed.';
            html_code += '</div>';
            html_code += '<input type="hidden" name="remove_video" id="remove_video" value="true" />';
            
            jQuery('#current_video_file').html(html_code).fadeIn(500);
        }
        );

    }
    );
    
    
    
    
    
    /*
     * Function that is triggered when the user attempts to delete the uploaded image file.
     * Notifies user about what is going to happen.
     * Everything is done with a nice smoothly effect.
     */
    jQuery('#rm_image').click(
    function()
    {
        jQuery('#current_image_file').fadeOut(500 , 
        function() 
        {
            var html_code = '<div class="alert alert-success alert-dismissible" role="alert">';
            html_code += '<button type="button" class="close" data-dismiss="alert" aria-label="Close">';
            html_code += '<span aria-hidden="true">&times;</span>';
            html_code += '</button>';
            html_code += '<strong>Success!</strong> The image file, after update/save this post, will be <u>permanently</u> removed.';
            html_code += '</div>';
            html_code += '<input type="hidden" name="remove_image" id="remove_image" value="true" />';
            
            jQuery('#current_image_file').html(html_code).fadeIn(500);
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
            if ( organizations_check() )
                jQuery('#save-post').click(); // Save first as Draft
            else
                return false;
        } 
        
        window.open( url ,
                    'popUpWindow',
                    'height=900,width=1600,left=10,top=10,,scrollbars=yes,menubar=no');


        event.preventDefault();
        event.stopPropagation();
        return false;
    });
    
    
    
    
    
    /*
     * Forcing the user to select only one organization
     * 
     * If the user clicks on submitting the form, i check if it has selected
     * more than one organization, if it has chosen more than one then i stop
     * submitting the form and I display an appropriate message.
     */
    
    jQuery('#publish').click(
    function()
    {
        return organizations_check();
    }
    );













}
);

