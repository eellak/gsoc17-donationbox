<?php

/* 
 * For code structure reasons, in this file are contained all
 * validations functions for uploading data.
 * 
 * Verification & validation functions.
 */

define('KB', 1024);
define('MB', 1048576);
define('GB', 1073741824);


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
                    $db_error['have'] = true;
                    $db_error['message'] = 'Problem with css file [UPLOAD_ERR_NO_FILE]';
                    return false;
                case UPLOAD_ERR_INI_SIZE:
                case UPLOAD_ERR_FORM_SIZE:
                    $db_error['have'] = true;
                    $db_error['message'] = 'Problem with css file [UPLOAD_ERR_INI_SIZE] || [UPLOAD_ERR_FORM_SIZE]';
                    return false;
                default:
                    $db_error['have'] = true;
                    $db_error['message'] = 'Problem with css file [Something went wrong]';
                    return false;
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
            if ($_FILES[$input_field]["size"] > 150 * KB )
            {
                wp_die( $message , "Sorry, your file is too large.");
            }
            
            return true;
    }
    return false;
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
                    $db_error['have'] = true;
                    $db_error['message'] = 'Problem with video file [UPLOAD_ERR_NO_FILE] ';
                    return false;
                case UPLOAD_ERR_INI_SIZE:
                case UPLOAD_ERR_FORM_SIZE:
                    $db_error['have'] = true;
                    $db_error['message'] = 'Problem with video file [UPLOAD_ERR_INI_SIZE] || [UPLOAD_ERR_FORM_SIZE] ';
                    return false;
                default:
                    $db_error['have'] = true;
                    $db_error['message'] = 'Problem with video file [Something went wrong]';
                    return false;
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
            
            // Max limit for video is 10 MB.
            if ($_FILES[$input_field]["size"] > 200 * MB )
            {
                wp_die( $message , "Sorry, your file is too large.");
            }
            
            return true;
    }
    return false;
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
            &&  wp_verify_nonce( $_POST['db_upload_image_file_meta_box_nonce'], 'db_save_image_file' ) 
            && (! empty( $_FILES[$input_field]['name'] ) ) )
    {

        if (  ! isset( $_POST['rm_image'] ) )
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
                    $db_error['have'] = true;
                    $db_error['message'] = 'Problem with image file [UPLOAD_ERR_NO_FILE]';
                    return false;
                case UPLOAD_ERR_INI_SIZE:
                case UPLOAD_ERR_FORM_SIZE:
                    $db_error['have'] = true;
                    $db_error['message'] = 'Problem with image file [UPLOAD_ERR_INI_SIZE] || [UPLOAD_ERR_FORM_SIZE]';
                    return false;
                default:
                    $db_error['have'] = true;
                    $db_error['message'] = 'Problem with iamge file [Something went wrong]';
                    return false;
            }

            $uploaded_temp_file = $_FILES[$input_field]['name'];
            $extension = pathinfo( $uploaded_temp_file, PATHINFO_EXTENSION );

            if ( $extension != 'jpg' && $extension != 'png')
            {
                wp_die( $message , "Security issue!");
            }

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
            
            return true;    // Πέρασε επιτυχώς όλους του ελέγχους.
        }
        return false;   // Δεν έχει πατίσει όμως να διαγράψει το αρχείο.
    }
    return false;   // Δεν πάτησε κάν ώστε να προσθέσει ένα αρχείο σε αυτό το πεδίο.
}





/*
 * Validations for project status field.
 */

function db_status_validations()
{
    global $db_error;
    
    
    if ( ! isset( $_POST['db_status_meta_box_nonce'] ) )
    {
        $db_error['have'] = true;
        $db_error['message'] = 'Problem with status';
        return false;
    }
    
    if ( !wp_verify_nonce( $_POST['db_status_meta_box_nonce'], 'db_save_project_status' ) )
    {
        $db_error['have'] = true;
        $db_error['message'] = 'Problem with status';
        return false;
    }
    
    if ( ! isset( $_POST['db_project_state_field'] ) )
    {
        $db_error['have'] = true;
        $db_error['message'] = 'Problem with status';
        return false;
    }
    
    return true;
}





/*
 * Validations for project target amount field.
 */

function db_target_amount_validations()
{
    global $db_error;
    
    
    if ( ! isset( $_POST['db_target_amount_meta_box_nonce'] ) )
    {
        $db_error['have'] = true;
        $db_error['message'] = 'Problem with target amount';
        return false;
    }

    if ( !wp_verify_nonce( $_POST['db_target_amount_meta_box_nonce'] , 'db_save_target_amount' ) )
    {
        $db_error['have'] = true;
        $db_error['message'] = 'Problem with target amount';
        return false;
    }

    if ( ! isset( $_POST['db_project_target_amount_field'] ) )
    {
        $db_error['have'] = true;
        $db_error['message'] = 'Problem with target amount';
        return false;
    }

    return true;
}





/*
 * A function with which i find the user ip address.
 * @return : The user ip address.
 */

function get_user_ip()
{
    if ( ! empty( $_SERVER['HTTP_CLIENT_IP'] ) )
    {
        $ip = $_SERVER['HTTP_CLIENT_IP'];
    }
    elseif ( ! empty( $_SERVER['HTTP_X_FORWARDED_FOR'] ) ) 
    {
        $ip = $_SERVER['HTTP_X_FORWARDED_FOR'];
    }
    else
    {
        $ip = $_SERVER['REMOTE_ADDR'];
    }
    return $ip;
}







