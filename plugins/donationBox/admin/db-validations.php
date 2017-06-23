<?php

/* 
 * For code structure reasons, in this file are contained all
 * validations functions for uploading data.
 * 
 * Verification & validation functions.
 */


/*
 * Validations for stylesheet file.
 */

function db_css_file_validations()
{
    global $error;
    
    $message = '<h1>Only <em><b>.css</b> text files</em> are allowed to upload.</h1><br>';
    $message .= 'Dear, <b>';
    $message .= get_user_ip() . '<br>' . $_SERVER['HTTP_USER_AGENT'] . '</b> <br><br>' ;
    $message .= 'Try uploading the file: "<b>' . $_FILES['db_project_css_file_field']['name'] . '</b>"<br>' ;
    $message .= 'Be very careful, because your activity may be misunderstood...<br>';
    $message .= 'Each of your activities are recorded.';
    
      
    // Αν έχει θέσει κάτι στο meta box αυτό..
    if( isset($_POST['db_upload_stylesheet_file_meta_box_nonce']) )
    {
        if( ! wp_verify_nonce( $_POST['db_upload_stylesheet_file_meta_box_nonce'], 'db_save_stylesheet_file' ) )
        {
            $error['have'] = true;
            $error['message'] = 'Problem with css file';
            return false;  
        }

        // Αν ΔΕΝ έχει πατήσει να διαγράψει το αρχείο...
        if (  ! isset( $_POST['remove_css'] ) )
        {
            $uploaded_temp_file = $_FILES['db_project_css_file_field']['name'];
            $extension = pathinfo( $uploaded_temp_file, PATHINFO_EXTENSION );

            if ( ! isset( $_FILES['db_project_css_file_field']['error'] ) || is_array( $_FILES['db_project_css_file_field']['error'] ) )
            {
                wp_die( $message , "Ts..ts..ts! [Level 8]");
            }

            // Αν έχουμε οποιοδήποτε error κατά το ανέβασμα :
            // Check $_FILES['upfile']['error'] value.
            switch ( $_FILES['db_project_css_file_field']['error'] )
            {
                case UPLOAD_ERR_OK:
                    break;
                case UPLOAD_ERR_NO_FILE:
                    $error['have'] = true;
                    $error['message'] = 'Problem with css file [UPLOAD_ERR_NO_FILE]';
                    return false;
                case UPLOAD_ERR_INI_SIZE:
                case UPLOAD_ERR_FORM_SIZE:
                    $error['have'] = true;
                    $error['message'] = 'Problem with css file [UPLOAD_ERR_INI_SIZE] || [UPLOAD_ERR_FORM_SIZE]';
                    return false;
                default:
                    $error['have'] = true;
                    $error['message'] = 'Problem with css file [Something went wrong]';
                    return false;
            }


            if ( $extension != 'css')
            {
                wp_die( $message , "Ts..ts..ts! [Level 10]"); // Level for game with hacker :P
            }

            // File type verification & validation.
            if( $_FILES['db_project_css_file_field']['type'] != 'text/css' )
            {

                wp_die( $message , "Ts..ts..ts! [Level 9]");
            }
            
            // Οπότε πρώτα να βρει στο τέλος την επέκτταση αυτή στο τέλος του αρχείου : 
            if ( $pure_name_length = strrpos($_FILES['db_project_css_file_field']['name'], '.css') ) 
            {
                // Αλλά και να τη βρει, πρέπει το όνομα του αρχείου - τις επέκτασης του, να είναι ίδιο με το παραπάνω μήκως ( που υποτίθετε είναι το καθαρό όνομα του αρχείου )
                if (  $pure_name_length != ( strlen($_FILES['db_project_css_file_field']['name']) - strlen('.css') ) )
                {
                    wp_die( $message , "Ts..ts..ts! [Level 7]");
                }
            }
            if ( substr_count( $_FILES['db_project_css_file_field']['name'], '.') > 1 )
            {
                wp_die( $message , "Ts..ts..ts! [Level 6]");
            }
            return true;    // Πέρασε επιτυχώς όλους του ελέγχους.
        }
        return false; // Δεν έχει πατίσει όμως να διαγράψει το αρχείο.
    }
    return false;   // Δεν πάτησε κάν ώστε να προσθέσει ένα αρχείο σε αυτό το πεδίο.
}





/*
 * Validations for video file.
 */

function db_video_file_validations()
{
    global $error;
    
    $input_field = 'db_project_video_field';
    $valid_file_extension = '.mp4';
    
    $message = '<h1>Only <em><b>'.$valid_file_extension.'</b> video files</em> are allowed to upload.</h1><br>';
    $message .= 'Dear, <b>';
    $message .= get_user_ip() . '<br>' . $_SERVER['HTTP_USER_AGENT'] . '</b> <br><br>' ;
    $message .= 'Try uploading the file: "<b>' . $_FILES['db_project_video_field']['name'] . '</b>"<br>' ;
    $message .= 'Be very careful, because your activity may be misunderstood...<br>';
    $message .= 'Each of your activities are recorded.';

    
          
    // Αν έχει θέσει κάτι στο meta box αυτό..
    if ( isset( $_POST['db_upload_video_file_meta_box_nonce'] ) )
    {
        var_dump($_FILES[$input_field]);
        echo '<br><br>';
        var_dump($_POST['db_upload_video_file_meta_box_nonce']);
        echo '<br><br>';
        
        if ( ! wp_verify_nonce( $_POST['db_upload_video_file_meta_box_nonce'], 'db_save_video_file' ) )
        {
            $error['have'] = true;
            $error['message'] = 'Problem with video file 1';
            return false;
        }

        // Αν ΔΕΝ έχει πατήσει να διαγράψει το αρχείο...
        if (  ! isset( $_POST['rm_video'] ) )
        {

            if ( ! isset( $_FILES[$input_field]['error'] ) || is_array( $_FILES[$input_field]['error'] ) )
            {
                wp_die( $message , "Ts..ts..ts! [Level 8]");
            }

            // Αν έχουμε οποιοδήποτε error κατά το ανέβασμα :
            switch ( $_FILES[$input_field]['error'] )
            {
                case UPLOAD_ERR_OK:
                    break;
                case UPLOAD_ERR_NO_FILE:
                    $error['have'] = true;
                    $error['message'] = 'Problem with video file [UPLOAD_ERR_NO_FILE] ';
                    return false;
                case UPLOAD_ERR_INI_SIZE:
                case UPLOAD_ERR_FORM_SIZE:
                    $error['have'] = true;
                    $error['message'] = 'Problem with video file [UPLOAD_ERR_INI_SIZE] || [UPLOAD_ERR_FORM_SIZE] ';
                    return false;
                default:
                    $error['have'] = true;
                    $error['message'] = 'Problem with video file [Something went wrong]';
                    return false;
            }

            $uploaded_temp_file = $_FILES[$input_field]['name'];
            $extension = pathinfo( $uploaded_temp_file, PATHINFO_EXTENSION );

            if ( $extension != 'mp4')
            {
                wp_die( $message , "Ts..ts..ts! [Level 10]");
            }

            // File type verification & validation.
            if( $_FILES[$input_field]['type'] != 'video/mp4' )
            {
                wp_die( $message , "Ts..ts..ts! [Level 9]");
            }
            
            // Οπότε πρώτα να βρει στο τέλος την επέκτταση αυτή στο τέλος του αρχείου : 
            if ( $pure_name_length = strrpos($_FILES[$input_field]['name'], $valid_file_extension) ) 
            {
                // Αλλά και να τη βρει, πρέπει το όνομα του αρχείου - τις επέκτασης του, να είναι ίδιο με το παραπάνω μήκως ( που υποτίθετε είναι το καθαρό όνομα του αρχείου )
                if (  $pure_name_length != ( strlen($_FILES[$input_field]['name']) - strlen($valid_file_extension) ) )
                {
                    wp_die( $message , "Ts..ts..ts! [Level 7]");
                }
            }
            if ( substr_count( $_FILES[$input_field]['name'], '.') > 1 )
            {
                wp_die( $message , "Ts..ts..ts! [Level 6]");
            }
            return true;
        }
        return false;
    }
    return false;
}





/*
 * Validations for project status.
 */

function db_status_validations()
{
    global $error;
    
    if ( ! isset( $_POST['db_status_meta_box_nonce'] ) )
    {
        $error['have'] = true;
        $error['message'] = 'Problem with status';
        return false;
    }
    
    if ( !wp_verify_nonce( $_POST['db_status_meta_box_nonce'], 'db_save_project_status' ) )
    {
        $error['have'] = true;
        $error['message'] = 'Problem with status';
        return false;
    }
    
    // Τέλος ελέγχο το ID από το field που θα πάρω τα δεδομένα.
    if ( ! isset( $_POST['db_project_state_field'] ) )
    {
        $error['have'] = true;
        $error['message'] = 'Problem with status';
        return false;
    }
    
    return true;
}





/*
 * Validations for project target amount.
 */

function db_target_amount_validations()
{
    global $error;
    
    // Αν δεν έχει τιμή!
    if ( ! isset( $_POST['db_target_amount_meta_box_nonce'] ) )
    {
        $error['have'] = true;
        $error['message'] = 'Problem with target amount';
        return false;
    }

    if ( !wp_verify_nonce( $_POST['db_target_amount_meta_box_nonce'] , 'db_save_target_amount' ) )
    {
        $error['have'] = true;
        $error['message'] = 'Problem with target amount';
        return false;
    }

    // Τέλος ελέγχο το ID από το field που θα πάρω τα δεδομένα.
    if ( ! isset( $_POST['db_project_target_amount_field'] ) )
    {
        $error['have'] = true;
        $error['message'] = 'Problem with target amount';
        return false;
    }
    
    return true;
}






function get_user_ip()
{
    if ( ! empty( $_SERVER['HTTP_CLIENT_IP'] ) )
    {
        //check ip from share internet
        $ip = $_SERVER['HTTP_CLIENT_IP'];
    }
    elseif ( ! empty( $_SERVER['HTTP_X_FORWARDED_FOR'] ) ) 
    {
        //to check ip is pass from proxy
        $ip = $_SERVER['HTTP_X_FORWARDED_FOR'];
    }
    else
    {
        $ip = $_SERVER['REMOTE_ADDR'];
    }
    return $ip;
}







