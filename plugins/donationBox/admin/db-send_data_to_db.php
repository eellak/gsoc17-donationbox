<?php

/**
 * This source file contains all the necessary functions that are responsible
 * for sending the data to the donation boxes database.
 * 
 */




/**
 * Function that informs the user if they can successfully communicate
 * with the database.
 * 
 * Attention! : This function does not just inform the user if everything went
 * well or not, but first of all (and its most important) is that check
 * whether the communication was done properly with the donation box database.
 * For this reason it returns true or false.
 * 
 * @param Array $response : What response we received when sending data to the database.
 * 
 * @param boolean $view : If true, it displays the message.
 * Because from now on, if he fails to communicate with the database for a
 * donation project we continue with the rest (if any) projects, and if any
 * of them fail to communicate, just simply a WordPress cron job is created.
 * That's way, we do not need to display an error message for each donation project.
 * If there is even a project that failed to communicate then, and only then,
 * just only one message will be displayed.
 * For this reason therefore, why need this parameter.
 * 
 * @retuern :
 *      - True : If there were *no* problems.
 *      - False : If there were problems.
 * 
 */

function db_check_and_print_response_message( $response, $view = true )
{
    $flag = TRUE;
    $current_url  = $_SERVER['HTTP_HOST'].$_SERVER['REQUEST_URI'];
    
    $script = '<script>';
    $script .= 'jQuery(document).ready(';
    $script .= 'function(){';
    $script .= "var pageTitle = jQuery('div #message');";    
    
    $warning_message = "pageTitle.after('<div class=\"warning notice notice-warning is-dismissible \"><p>";
    $warning_message .= 'But do not worry, you are very good boy. <br>';
    $warning_message .= 'Report this error to the system administrator to resolve it. <br>';
    $warning_message .= 'When this problem resolved, <u>the system will automatically resend</u> the donation project to the donation box database.';
    $warning_message .= '<br> So, be happy. ' .  convert_smilies(':)' . ' ' );
//    $warning_message .= ' <i> (If you want, <a id="try_again" href="http://'.$current_url.'">try once again to send the request to the donation box database.</a>)</i>' ;
    $warning_message .= "</p></div>');";
    
    if ( is_wp_error($response) )
    {
        $error_message = $response->get_error_message();
        
        $script .= $warning_message;
        
        $script .= "pageTitle.after('<div class=\"error notice notice-success is-dismissible \"><p>";
        $script .= "The donation project data could not be <b>sent</b> to the donation boxes database!<br>";
        $script .= $error_message;
        $script .= "</p></div>');";
        
        $flag = FALSE;
    }
    else if ( $response['response']['code'] == '200' ) // Add new or update a donation project.
    {
        $script .= "pageTitle.after('<div class=\"updated notice notice-success is-dismissible \"><p>";
        $script .= "The donation project data has been also sent it successfully in the donation boxes database.<br>";
        $script .= 'Donation boxes database : ' . trim($response['body']) ;
        $script .= "</p></div>');";
    }
    else if ( $response['response']['code'] == '455' ) // Invalid credentials
    {
        $script .= $warning_message;
        
        $script .= "pageTitle.after('<div class=\"error notice notice-success is-dismissible \"><p>";
        $script .= "Invalid user credentials! The donation project data could not be <b>saved</b> to the donation boxes database, ";
        $script .= "because you haven\'t provided the appropriate user credentials.<br>";
        $script .= $response['response']['code'] . ' [ Invalid credentials ] <br>';
        $script .= "</p></div>');";
        
        $flag = FALSE;
    }
    else if ( $response['response']['code'] == '460' ) // The donation project was deleted from donation box database.
    {
        $script .= "pageTitle.after('<div class=\"updated notice notice-success is-dismissible \"><p>";
        $script .= "The donation project was <b>deleted</b> successfully from donation box database.<br>";
        $script .= 'Donation boxes database : ' . trim($response['body']) ;
        $script .= "</p></div>');";
    }
    else // != 200 , != 455 , != 460 , Some other error...
    {
        $script .= $warning_message;
        
        $script .= "pageTitle.after('<div class=\"error notice notice-success is-dismissible \"><p>";
        $script .= "The donation project data could not be <b>saved</b> to the donation boxes database!<br>";
        $script .= $response['response']['code'] . ' [' . $response['response']['message'] . '] ';
        $script .= "</p></div>');";
        
        $flag = FALSE;
    }
    
    $script .= "}); </script>";
    
    if ( $view )
    {
        echo $script;
    }
    
    return $flag;
}





/**
 * This function are responsible to collect all project data.
 * 
 * Attention :
 * 1) Some data may not have been given by the user, for this reason they are made
 * the necessary checks.
 * 
 * 2) The current amount of money that has been collected for the donation
 * project is never taken from the WordPress database.
 * 
 * 
 * @paran string $project_id : The donation project id for which all data will
 * be collected from WordPress database.
 * 
 * @return Array $data : A array with all data of current project.
 * 
 */

function db_collect_all_data( $project_id )
{
    $image = get_post_meta( $project_id, '_db_project_image_file', true);
    if ( count($image) > 0  &&  is_array($image) )
    {
        $image = $image[0]['url'];
    }
    else 
    {
        $image = null;
    }
    
    $video = get_post_meta( $project_id, '_db_project_video_file', true);
    if ( count($video) > 0  &&  is_array($video) )
    {
        $video = $video[0]['url'];
    }
    else 
    {
        $video = null;
    }
    
    $css = get_post_meta( $project_id, '_db_project_stylesheet_file', true);
    if ( count($css) > 0  &&  is_array($css) )
    {
        $css = $css[0]['url'];
    }
    else 
    {
        $css = null;
    }
    
    $status = get_post_meta( $project_id, '_db_project_status', true );
    if ( $status == 1 )
    {
        $status = 'Activate';
    }
    else
    {
        $status = 'Deactivate';
    }
    
    $data = array(
        'username'              => get_option( 'db_username_field' ),
        'password'              => get_option( 'db_password_field' ),
        'id'                    => $project_id,
        'title'                 => get_the_title($project_id),
        'content'               => esc_sql( get_post_field('post_content', $project_id ) ),
        'image_url'             => $image,
        'video_url'             => $video,
        'stylesheet_file_url'   => $css,
        'status'                => $status,
        'organization'          => get_the_terms( $project_id, 'organization' )[0]->name,
        'target_amount'         => get_post_meta( $project_id, '_db_project_target_amount', true), 
    );
    
    return $data;
}





/**
 * This function called when data is saved in the WordpPress database.
 * Every successful update of a donation project, the donation box database
 * should be updated.
 * 
 * So, attention : This function called when a *new* donation project is saved
 * in WordPress database, or when an *already existing* donation project is
 * being *updated*.
 * 
 * Important :
 * 1) The current amount of money collected will never not be sent to the
 * donation boxes database!
 * 
 * 2) They are not sent to the donation projects database, projects that are
 * not to be published in the WordPress database.
 * 
 * @paran string $donation_project_id : The donation project id for which it 
 * will gather the stored data, and will send them all together to the database.
 * 
 * Attention! : If a project fails to be sent to the database, a WordPress cron
 * job is created in order, whenever it can, send the data automatically.
 * 
 * Note : If there is already a WordPress cron job for this donation project
 * action, deleted and the position he takes on a new. 
 * 
 */

function db_send_data_to_donationBox_database( $donation_project_id )
{
    if (  ( ! db_post_status_is_draft ) || ( ! db_post_type_is_donationboxes($donation_project_id) ) )
    {
        return; // Don't sent data to the donation boxes database.
    }
   
    $body = db_collect_all_data($donation_project_id);

    $args = array(
        'body' => $body,
        'timeout' => '5',
        'redirection' => '5',
        'httpversion' => '1.0',
        'blocking' => true,
        'headers' => array(),
        'cookies' => array()
    );

    $response = wp_remote_post( get_option( 'database_url_field '), $args );
    
    if ( ! db_check_and_print_response_message($response) ) 
    {
        $next_time = time() + ( 60 ) ; // Just one hour.

        $next_time_string = date('Y/m/d H:i:s' , $next_time) . ' ' . date_default_timezone_get();
        
        if ( db_cron_exists($donation_project_id) )
        {
            db_delete_cron_job($donation_project_id);
        }
        
        wp_schedule_single_event( $next_time, 'db_cron_hook_insert_update', array( $donation_project_id, TRUE, $next_time_string ) );
    }
    
    
}

add_action( 'db_cron_hook_insert_update', 'db_send_data_to_donationBox_database', 10, 3 );





/**
 * This function called when a donation project must be deleted from the 
 * donation box database. It is called when a donation project from
 * Wordpress it was successfully transferred to the recycle bin ( Trash ).
 * 
 * @param string $donation_projects_ids : The donation projects id(s) for
 * which a delete request will be sent to the database.
 * 
 * Note : They may be given together, more than one project for deletion.
 * (for example: ids=349,332,330 )
 * 
 */

function db_delete_data_from_donationBox_database( $donation_projects_ids )
{
    // Ξεχωρίζω ΑΝ τυχών έχει δώσει πάνω από ένα projects για μεταφορά στον κάδο ανακύκλωσης.
    $ids = explode(",", strval( $donation_projects_ids ) );

    $view = TRUE;
    foreach ($ids as  & $id)
    {
        $body = array(
            'username'  => get_option( 'db_username_field' ),
            'password'  => get_option( 'db_password_field' ),
            'id'        => $id,
            'delete'    => 1,
        );

        $args = array(
            'body'          => $body,
            'timeout'       => '5',
            'redirection'   => '5',
            'httpversion'   => '1.0',
            'blocking'      => true,
            'headers'       => array(),
            'cookies'       => array()
        );

        $response = wp_remote_post( get_option( 'database_url_field '), $args );

        if ( ! db_check_and_print_response_message($response , $view) )  // if we haven't send it to database... start a cron job
        {
            $next_time = time() + ( 60 ) ;

            $next_time_string = date('Y/m/d H:i:s' , $next_time) . ' ' . date_default_timezone_get();

            if ( db_cron_exists($id) )
            {
                db_delete_cron_job($id);
            }

            wp_schedule_single_event( $next_time, 'db_cron_hook_delete', array( $id, FALSE, $next_time_string ) );

            $view = FALSE;
        }

        
    }
}

add_action( 'db_cron_hook_delete', 'db_delete_data_from_donationBox_database', 10, 3 );
s




/**
 * The following php function, will be executed upon request via AJAX for 
 * action "db_check_credentials_request".
 * Send the user credentials to the machine where the database of donation boxes
 * is located and returns the response he receives. * 
 * 
 * Note: Because it uses the WordPress capability to execute code from WordPress
 * AJAX actions, probably this way isn't the most efficient way because checks
 * every time on which page it is.
 * 
 */

function db_check_credentials()
{
    $body = array(
        'username'  => get_option( 'db_username_field' ),
        'password'  => get_option( 'db_password_field' ),
        'check'    => 1,
    );

    $args = array(
        'body'          => $body,
        'timeout'       => '5',
        'redirection'   => '5',
        'httpversion'   => '1.0',
        'blocking'      => true,
        'headers'       => array(),
        'cookies'       => array()
    );

    $response = wp_remote_post( get_option( 'database_url_field '), $args );
    echo trim( $response['body'] );
    wp_die();
}

add_action('wp_ajax_db_check_credentials_request', 'db_check_credentials');
add_action('wp_ajax_nopriv_db_check_credentials_request', 'db_check_credentials');





/**
 * Function that asks from donation boxe database, for a donation project the
 * current amount collected.
 * 
 * @param string $donation_projects_id : Τhe id of donation project for which
 * the current amount is requested.
 * 
 * @return string : The current amount from the donation box database,
 * for the requested donation project.
 * 
 */

function db_get_current_amount_from_db( $donation_projects_id )
{
    $body = array(
        'username'          => get_option( 'db_username_field' ),
        'password'          => get_option( 'db_password_field' ),
        'id'                => $donation_projects_id,
        'current_amount'    => 1,
    );

    $args = array(
        'body'          => $body,
        'timeout'       => '5',
        'redirection'   => '5',
        'httpversion'   => '1.0',
        'blocking'      => true,
        'headers'       => array(),
        'cookies'       => array()
    );

    $response = wp_remote_post( get_option( 'database_url_field '), $args );

    if ( ! is_wp_error($response) &&  intval( trim( $response['body'] ) ) )
    {
        return trim( $response['body'] );       
    }
    else
    {
        return '';
    }

}


