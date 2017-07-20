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
        // If elemet not exists ( other post type posts ) :
        if ( ! jQuery('ul#organizationchecklist li').length )
        {
            return true; // No problem.
        }
        
        var how_many_times = 0;

        jQuery('ul#organizationchecklist li').each(
        function()
        {
            if ( jQuery(this).find('input').is(':checked') )
            {
                how_many_times++;
            }

        }
        );

        if ( how_many_times == 0 || how_many_times > 1 )
        {
            // I make all fields red.
            jQuery('ul#organizationchecklist li').each(
            function()
            {
                this.style.color = "red";
            }
            );

            // I alert the user.

            if ( how_many_times == 0 )
            {
                var message = 'It is necessary to choose an organization.\n';
                message += 'Select only one organization.';
                alert(message);
                return false; // Stop submitting.
            }

            if ( how_many_times > 1 )
            {
                var message = 'Sorry, you can choose *only one* organization for each donation project.\nYou choose ';
                message += how_many_times;
                message += ' organizations!\nFix it and then go ahead with saving of the donation project. :)';
                alert(message);
                return false; // Stop submitting.
            }

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
    
    
    
    
    
    /*
     * Function that is triggered when the user click the "Donation Box Preview"
     * button. It is targeted to display a window with specific dimensions,
     * which resembles with the screen of the final donation box.
     * In this (popup) window will display all project data.
     */
    
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





    /*
     * Removing from user's (which is the group "project_creator") field of view 
     * the fields that enable them to : 
     *      - trash a donation project
     *      - untrash a donation project
     *      - delete permanently a donation project
     *
     * Secure note : In this way, they are removed only from our field of view.
     * So it is not removed here, really the ability of the user to take these
     * actions. They just do not see these options.
     * But in PHP code, i blocked/remove completely these user features.
     * Check the PHP Functions ( in db-validations.php ) : 
     *      - db_delete_donationboxes_post_type()
     *      - db_untrash_donationboxes_post_type()
     * 
     */

    var data = 
    {
        action: 'db_is_project_creator'
    };

    jQuery.post(ajaxurl, data, function(response)
    {
        if( response )
        {
            jQuery('ul.subsubsub li.trash').remove(); // Trash folder link.
            jQuery('span.untrash').remove();         // Restore link.
            jQuery('span.delete').remove();         // Delete Permanently link.
            
            // "Undo" link.
            if ( jQuery('div#message p:contains(post moved to the Trash)').length )
            { 
                jQuery('div#message p a').remove();
            }
        }
    });
    
    
    
    
    
    /**
     * Code which first of all, check if the user is on the page
     * "Donation Boxes Settings" ('/wp-admin/edit.php?post_type=donationboxes&page=db-settings-menu')
     * and then uses the ability of WordPress, to execute PHP code via AJAX Wordpress actions.
     * 
     * It call the action 'db_check_credentials_request', which in turn calls the
     * PHP function 'db_check_credentials' and returns if the user has given it correctly
     * credentials or not.
     * 
     * It shows the user in a very nice and discreet way if he has given the correct
     * credentials or not for the donation box database.
     * 
     * Note: Because it uses the WordPress capability to execute code from WordPress
     * AJAX actions, probably this way isn't the most efficient way because checks
     * every time on which page it is.
     * 
     */
    
    var url = window.location.href.replace( document.location.origin, '' );
    
    if ( url === '/wp-admin/edit.php?post_type=donationboxes&page=db-settings-menu')
    {
        var action_check_credential = 
        {
            action: 'db_check_credentials_request'
        };

        jQuery.post(ajaxurl, action_check_credential, function(response)
        {
            if ( response === '1')
            {
                jQuery('#check_username').html('<span class="glyphicon glyphicon-ok-circle text-success" aria-hidden="true"> </span>').fadeIn(70000);
                jQuery('#check_password').html('<span class="glyphicon glyphicon-ok-circle text-success" aria-hidden="true"> </span>').fadeIn(70000);
            }
            else
            {
                jQuery('#check_username').html('<span class="glyphicon glyphicon-remove-circle text-danger" aria-hidden="true"> </span>').fadeIn(70000);
                jQuery('#check_password').html('<span class="glyphicon glyphicon-remove-circle text-danger" aria-hidden="true"> </span>').fadeIn(70000);
            }
        });
    
    }

    



}
);