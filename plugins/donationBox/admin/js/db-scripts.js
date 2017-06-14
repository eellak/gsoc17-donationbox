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
    
//    jQuery('#db_preview_button').window.open( '/wp-content/plugins/donationBox/templates/template-portrait_mode.php',
//                                            'popUpWindow','height=900,width=1600,left=10,top=10,,scrollbars=yes,menubar=no') 
//                                            {return false;}
//                                            );


    jQuery('#db_preview_button').click( function (e)
    {
        var post_id = jQuery('#db_preview_button').attr('name');
        var status = jQuery('#db_project_state_field_a').attr('checked');
        var current_amount = jQuery('#db_project_current_amount_field').val();
        var target_amount = jQuery('#db_project_target_amount_field').val();


        if ( status === 'checked')
            status = 'Activate';
        else 
            status = 'Deactivate';

        

        var url = '/wp-content/plugins/donationBox/templates/template-portrait_mode.php?db_preview_id=';
        url += post_id;
        url += '&status=' + status;
        url += '&c_amount=' + current_amount;
        url += '&t_amount=' + target_amount;
        
        window.open( url ,
                    'popUpWindow',
                    'height=900,width=1600,left=10,top=10,,scrollbars=yes,menubar=no');


        e.preventDefault();
        return false;
    });

//jQuery('#db_preview_button').onclick( val, function(e){
//        
//                alert("hello ", val);
//        e.preventDefault();
//        return false;
//}
//        );

//    jQuery('#db_preview_button').onclick(
//            function (e)
//            {
//                window.open('/wp-content/plugins/donationBox/templates/template-portrait_mode.php',
//                                            'popUpWindow','height=900,width=1600,left=10,top=10,,scrollbars=yes,menubar=no');
//            e.preventDefault();
//            event.stopPropagation();
//            return false;
//            }
//            );

}
);


//function clicked(value)
//{
//    alert(value);
//}