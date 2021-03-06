<?php

/* 
 * For code structure reasons, in this file are contained all
 * validations functions for uploading data.
 * 
 * Verification & validation functions.
 */





/*
 * Validations for stylesheet file field.
 */

function db_css_file_validations()
{
    global $db_error;
    
    $input_field            = 'db_project_css_file_field';
    $valid_file_extension   = '.css';
    $file_type              = 'text/css';
    
    $message = '<h1>Only <em><b>'.$valid_file_extension.'</b> text files</em> are allowed to upload.</h1><br>';
    $message .= 'Dear <b>';
    $message .= get_user_ip() . ',<br>' . $_SERVER['HTTP_USER_AGENT'] . '</b> <br><br>' ;
    $message .= 'Try uploading the file: "<b>' . $_FILES[$input_field]['name'] . '</b>"<br>' ;
    $message .= 'Be very careful, because your activity may be misunderstood...<br>';
    $message .= 'Each of your activities are recorded.';
    
    if ( isset($_POST['db_upload_stylesheet_file_meta_box_nonce'])
            && ( ! empty( $_FILES[$input_field]['name'] ) )
            && ( wp_verify_nonce( $_POST['db_upload_stylesheet_file_meta_box_nonce'], 'db_save_stylesheet_file' ) )
            && ! isset( $_POST['remove_css'] ) )
    {
        $uploaded_temp_file = $_FILES[$input_field]['name'];
        $extension = pathinfo( $uploaded_temp_file, PATHINFO_EXTENSION );

        if ( ! isset( $_FILES[$input_field]['error'] ) || is_array( $_FILES[$input_field]['error'] ) )
        {
            wp_die( $message , "Security issue!");
        }

        switch ( $_FILES[$input_field]['error'] )
        {
            case UPLOAD_ERR_OK:
                break;
            case UPLOAD_ERR_NO_FILE:
                $db_error['have'] = TRUE;
                $db_error['message_code'] = '101';
                return FALSE;
            case UPLOAD_ERR_INI_SIZE:
            case UPLOAD_ERR_FORM_SIZE:
                $db_error['have'] = TRUE;
                $db_error['message_code'] = '102';
                return FALSE;
            default:
                $db_error['have'] = TRUE;
                $db_error['message_code'] = '103';
                return FALSE;
        }

        if ( $extension != 'css')
        {
            wp_die( $message , "Security issue!");
        }

        if( $_FILES[$input_field]['type'] != $file_type )
        {
            wp_die( $message , "Security issue!");
        }

        if ( $pure_name_length = strrpos($_FILES[$input_field]['name'], $valid_file_extension ) ) 
        {
            if (  $pure_name_length != ( strlen($_FILES[$input_field]['name']) - strlen($valid_file_extension) ) )
            {
                wp_die( $message , "Security issue!");
            }
        }

        if ( substr_count( $_FILES[$input_field]['name'], '.') > 1 )
        {
            wp_die( $message , "Security issue!");
        }

        // Max limit for css file is 150 KB ( bootstrap css file has 146 KB ).
        if ($_FILES[$input_field]["size"] > 150 * kB )
        {
            wp_die( $message , "Sorry, your file is too large.");
        }

        return TRUE;
    }
    return FALSE;
}





/*
 * Validations for video file field.
 */

function db_video_file_validations()
{
    global $db_error;
    
    $input_field            = 'db_project_video_field';
    $valid_file_extension   = '.mp4';
    $file_type              = 'video/mp4';
    
    $message = '<h1>Only <em><b>'.$valid_file_extension.'</b> video files</em> are allowed to upload.</h1><br>';
    $message .= 'Dear <b>';
    $message .= get_user_ip() . ',<br>' . $_SERVER['HTTP_USER_AGENT'] . '</b> <br><br>' ;
    $message .= 'Try uploading the file: "<b>' . $_FILES[$input_field]['name'] . '</b>"<br>' ;
    $message .= 'Be very careful, because your activity may be misunderstood...<br>';
    $message .= 'Each of your activities are recorded.';

    if ( isset( $_POST['db_upload_video_file_meta_box_nonce'] )
            && ( ! empty( $_FILES[$input_field]['name'] ) )
            && wp_verify_nonce( $_POST['db_upload_video_file_meta_box_nonce'], 'db_save_video_file' )
            && ! isset( $_POST['rm_video'] ) )
    {
        if ( ! isset( $_FILES[$input_field]['error'] ) || is_array( $_FILES[$input_field]['error'] ) )
        {
            wp_die( $message , "Security issue!");
        }

        switch ( $_FILES[$input_field]['error'] )
        {
            case UPLOAD_ERR_OK:
                break;
            case UPLOAD_ERR_NO_FILE:
                $db_error['have'] = TRUE;
                $db_error['message_code'] = '201';
                return FALSE;
            case UPLOAD_ERR_INI_SIZE:
            case UPLOAD_ERR_FORM_SIZE:
                $db_error['have'] = TRUE;
                $db_error['message_code'] = '202';
                return FALSE;
            default:
                $db_error['have'] = TRUE;
                $db_error['message_code'] = '203';
                return FALSE;
        }

        $uploaded_temp_file = $_FILES[$input_field]['name'];
        $extension = pathinfo( $uploaded_temp_file, PATHINFO_EXTENSION );

        if ( $extension != 'mp4')
        {
            wp_die( $message , "Security issue!");
        }

        if( $_FILES[$input_field]['type'] != $file_type )
        {
            wp_die( $message , "Security issue!");
        }

        if ( $pure_name_length = strrpos($_FILES[$input_field]['name'], $valid_file_extension) ) 
        {
            if (  $pure_name_length != ( strlen($_FILES[$input_field]['name']) - strlen($valid_file_extension) ) )
            {
                wp_die( $message , "Security issue!");
            }
        }

        if ( substr_count( $_FILES[$input_field]['name'], '.') > 1 )
        {
            wp_die( $message , "Security issue!");
        }

        // Max limit for video is 200 MB.
        if ($_FILES[$input_field]["size"] > 200 * MB )
        {
            wp_die( $message , "Sorry, your file is too large.");
        }

        return TRUE;
    }
    return FALSE;
}





/*
 * Validations for image file field.
 */

function db_image_file_validations()
{
    global $db_error;
    
    $input_field                = 'db_project_image_field';
    $valid_file_extension_jpg   = '.jpg';
    $valid_file_extension_png   = '.png';
    $file_type_jpeg             = 'image/jpeg';
    $file_type_png              = 'image/png';
    
    $message = '<h1>Only <em><b>'.$valid_file_extension.'</b> image files</em> are allowed to upload.</h1><br>';
    $message .= 'Dear <b>';
    $message .= get_user_ip() . ',<br>' . $_SERVER['HTTP_USER_AGENT'] . '</b> <br><br>' ;
    $message .= 'Try uploading the file: "<b>' . $_FILES[$input_field]['name'] . '</b>"<br>' ;
    $message .= 'Be very careful, because your activity may be misunderstood...<br>';
    $message .= 'Each of your activities are recorded.';


    if ( isset( $_POST['db_upload_image_file_meta_box_nonce'] )
            && ! empty( $_FILES[$input_field]['name'] ) 
            &&  wp_verify_nonce( $_POST['db_upload_image_file_meta_box_nonce'], 'db_save_image_file' ) 
            &&  ! isset( $_POST['rm_image'] ) )
    {
        if ( ! isset( $_FILES[$input_field]['error'] ) || is_array( $_FILES[$input_field]['error'] ) )
        {
            wp_die( $message , "Security issue!");
        }


        switch ( $_FILES[$input_field]['error'] )
        {
            case UPLOAD_ERR_OK:
                break; // No error, all good
            case UPLOAD_ERR_NO_FILE:
                $db_error['have'] = TRUE;
                $db_error['message_code'] = '301';
                return FALSE;
            case UPLOAD_ERR_INI_SIZE:
            case UPLOAD_ERR_FORM_SIZE:
                $db_error['have'] = TRUE;
                $db_error['message_code'] = '302';
                return FALSE;
            default:
                $db_error['have'] = TRUE;
                $db_error['message_code'] = '303';
                return FALSE;
        }

        $uploaded_temp_file = $_FILES[$input_field]['name'];
        $extension = pathinfo( $uploaded_temp_file, PATHINFO_EXTENSION );

        if ( $extension != 'jpg' && $extension != 'png')
        {
            wp_die( $message , "Security issue!");
        }

        // File type verification & validation.
        if ( $_FILES[$input_field]['type'] != $file_type_jpeg && $_FILES[$input_field]['type'] != $file_type_png  )
        {
            wp_die( $message , "Security issue!");
        }

        if ( $extension == 'jpg' )
        {
            if ( $pure_name_length = strrpos($_FILES[$input_field]['name'], $valid_file_extension_jpg) ) 
            {
                if (  $pure_name_length != ( strlen($_FILES[$input_field]['name']) - strlen($valid_file_extension_jpg) ) )
                {
                    wp_die( $message , "Security issue!");
                }
            }
        }

        if ( $extension == 'png' )
        {
            if ( $pure_name_length = strrpos($_FILES[$input_field]['name'], $valid_file_extension_png) ) 
            {
                if (  $pure_name_length != ( strlen($_FILES[$input_field]['name']) - strlen($valid_file_extension_png) ) )
                {
                    wp_die( $message , "Security issue!");
                }
            }
        }

        if ( ( exif_imagetype($_FILES[$input_field]['tmp_name']) != IMAGETYPE_JPEG ) && ( exif_imagetype($_FILES[$input_field]['tmp_name']) != IMAGETYPE_PNG ) )
        {
            wp_die( $message , "The picture is not valid.");
        }

        if ( substr_count( $_FILES[$input_field]['name'], '.') > 1 )
        {
            wp_die( $message , "Security issue!");
        }

        $verifyimg = getimagesize($_FILES[$input_field]['tmp_name']);
        if ( $verifyimg['mime'] != $file_type_jpeg && $verifyimg['mime'] != $file_type_png )
        {
            wp_die( $message , "Security issue!");
        }

        // Check file size, Max limit for image is 3 MB.
        if ($_FILES[$input_field]["size"] > 3 * MB )
        {
            wp_die( $message , "Sorry, your file is too large.");
        }

        return TRUE;
    }
    return FALSE;
}





/*
 * Validations for project status field.
 */

function db_status_validations()
{
    global $db_error;
    
    if ( ! isset( $_POST['db_status_meta_box_nonce'] ) )
    {
        $db_error['have'] = TRUE;
        $db_error['message_code'] = 'Problem with status';
        return FALSE;
    }
    
    // Validate that the contents of the form request came from the current site and not somewhere else.
    if ( !wp_verify_nonce( $_POST['db_status_meta_box_nonce'], 'db_save_project_status' ) )
    {
        $db_error['have'] = TRUE;
        $db_error['message_code'] = 'Problem with status';
        return FALSE;
    }
    
    if ( ! isset( $_POST['db_project_state_field'] ) )
    {
        $db_error['have'] = TRUE;
        $db_error['message_code'] = 'Problem with status';
        return FALSE;
    }
    
    return TRUE;
}





/*
 * Validations for project target amount field.
 */

function db_target_amount_validations()
{
    global $db_error;
    
    if ( ! isset( $_POST['db_target_amount_meta_box_nonce'] ) )
    {
        $db_error['have'] = TRUE;
        $db_error['message_code'] = 'Problem with target amount';
        return FALSE;
    }

    if ( !wp_verify_nonce( $_POST['db_target_amount_meta_box_nonce'] , 'db_save_target_amount' ) )
    {
        $db_error['have'] = TRUE;
        $db_error['message_code'] = 'Problem with target amount';
        return FALSE;
    }

    if ( ! isset( $_POST['db_project_target_amount_field'] ) )
    {
        $db_error['have'] = TRUE;
        $db_error['message_code'] = 'Problem with target amount';
        return FALSE;
    }

    return TRUE;
}





/*
 * Validations for start project date field.
 */

function db_start_project_date_validations()
{
    global $db_error;
    
    if ( ! isset( $_POST['db_start_date_meta_box_nonce'] ) )
    {
        $db_error['have'] = TRUE;
        $db_error['message_code'] = '401';
        return FALSE;
    }

    if ( !wp_verify_nonce( $_POST['db_start_date_meta_box_nonce'] , 'db_save_start_project_date' ) )
    {
        $db_error['have'] = TRUE;
        $db_error['message_code'] = '401';
        return FALSE;
    }

    if ( ! isset( $_POST['db_start_datepicker_field'] ) )
    {
        $db_error['have'] = TRUE;
        $db_error['message_code'] = '401';
        return FALSE;
    }
    
    if ( ! validateDate( $_POST['db_start_datepicker_field'] ) )
    {
        $db_error['have'] = TRUE;
        $db_error['message_code'] = '401';
        return FALSE;
    }

    return TRUE;
}





/*
 * Validations for end project date field.
 */

function db_end_project_date_validations()
{
    global $db_error;
    
    if ( ! isset( $_POST['db_end_date_meta_box_nonce'] ) )
    {
        $db_error['have'] = TRUE;
        $db_error['message_code'] = '501';
        return FALSE;
    }

    if ( !wp_verify_nonce( $_POST['db_end_date_meta_box_nonce'] , 'db_save_end_project_date' ) )
    {
        $db_error['have'] = TRUE;
        $db_error['message_code'] = '501';
        return FALSE;
    }

    if ( ! isset( $_POST['db_end_datepicker_field'] ) )
    {
        $db_error['have'] = TRUE;
        $db_error['message_code'] = '501';
        return FALSE;
    }
    
    if ( ! validateDate( $_POST['db_end_datepicker_field'] ) )
    {
        $db_error['have'] = TRUE;
        $db_error['message_code'] = '501';
        return FALSE;
    }

    return TRUE;
}





/*
 * Function that checks whether a 'project_creator' user attempts to enter the page :
 * http://localhost:8000/wp-admin/edit.php?post_status=trash&post_type=donationboxes
 * 
 * If he try to access this page, his request is rejected.
 * This function is executed each time when the page "wp-admin/edit.php" is loaded.
 * 
 * Reference : https://codex.wordpress.org/Plugin_API/Action_Reference/load-(page)
 * 
 * 
 *          ΠΡΕΠΕΙ ΝΑ ΤΟ ΕΝΗΜΕΡΩΣΩ ΕΔΏ!
 */

function db_load_trash_folder()
{
    if ( current_user_can('project_creator') )
    {
        if ( isset( $_GET['post_type'] ) && $_GET['post_type'] == 'donationboxes' )
        {
            if ( isset( $_GET['post_status'] ) && ( $_GET['post_status'] == 'trash' ) )
            {
                $message = '<h1>Access denied.</h1><br>';
                $message .= 'Dear <b>';
                $message .= get_user_ip() . ',<br>' . $_SERVER['HTTP_USER_AGENT'] . '</b> <br><br>' ;
                $message .= 'You are trying to access a page that is not allowed.<br>' ;
                $message .= 'Be very careful, because your activity may be misunderstood...<br>';
                $message .= 'Each of your activities are recorded.';                
                wp_die($message, 'Access denied.');
            }
        }
    }
}

add_action( 'load-edit.php', 'db_load_trash_folder' );





/*
 * ......
 */

function db_untrash_donationboxes_post_type( $post_id )
{
    global $db_error;
    global $untrashed_posts;


    if ( db_post_type_is_donationboxes($post_id) )
    {
        if ( current_user_can('project_creator') )
        {
            $message = '<h1>Access denied.</h1><br>';
            $message .= 'Dear <b>';
            $message .= get_user_ip() . ',<br>' . $_SERVER['HTTP_USER_AGENT'] . '</b> <br><br>' ;
            $message .= 'Trying to take an action where you do not have access to.<br>' ;
            $message .= 'Be very careful, because your activity may be misunderstood...<br>';
            $message .= 'Each of your activities are recorded.';                

            wp_die($message, 'Access denied.');
        }
        else if ( is_super_admin() )
        {
            $message = '<h1>Access denied.</h1><br>';
            $message .= 'Dear <b>';
            $message .= get_user_ip() . ',<br>' . $_SERVER['HTTP_USER_AGENT'] . '</b> <br><br>' ;
            $message .= 'Trying to take an action where you do not have access to.<br>' ;
            $message .= 'Be very careful, because your activity may be misunderstood...<br>';
            $message .= 'Each of your activities are recorded.';

            wp_die($message, 'Access denied.');
        }

    }
    
}


add_action('untrash_post' , 'db_untrash_donationboxes_post_type');








global $untrashed_posts;
$untrashed_posts = array();

function wpse_handle_untrash($new_status, $old_status, $post)
{
    global $untrashed_posts;
    
    // if the post was in the trash, but now is notS
    if( $old_status == 'trash' )
    {
        // if you want, you can do something only for a certain post type
        if($post->post_type == 'donationboxes')
        {
            array_push($untrashed_posts, $post->ID );
        }
    }
}

add_action('transition_post_status', 'wpse_handle_untrash', 10, 3 );





/*
 * 
 */

function db_delete_donationboxes_post_type( $post_id )
{
//    $trash_url = 'edit.php?post_status=trash&post_type=donationboxes&fail_remote_delete=461';

    if ( db_post_type_is_donationboxes($post_id) )
    {
        if ( current_user_can('project_creator') )
        {
            $message = '<h1>Access denied.</h1><br>';
            $message .= 'Dear <b>';
            $message .= get_user_ip() . ',<br>' . $_SERVER['HTTP_USER_AGENT'] . '</b> <br><br>' ;
            $message .= 'Trying to take an action where you do not have access to.<br>' ;
            $message .= 'Be very careful, because your activity may be misunderstood...<br>';
            $message .= 'Each of your activities are recorded.';                
            wp_die($message, 'Access denied.');
        }
        else if ( is_super_admin() )
        {
            // Delete from WordPress Database.
            db_delete_css_file($post_id);
            db_delete_video_file($post_id);
            db_delete_image_file($post_id);
        }

    }
    
}

add_action('before_delete_post' , 'db_delete_donationboxes_post_type');











/*
 * 
 * Run actions when a post, page, or custom post type is about to be trashed.
 * https://codex.wordpress.org/Plugin_API/Action_Reference/trash_post
 * https://wordpress.stackexchange.com/a/100640/121651
 * 
 */
