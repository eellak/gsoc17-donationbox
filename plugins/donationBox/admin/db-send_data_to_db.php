<?php

/* 
 * This source file contains all the necessary functions that are responsible
 * for sending the data to the donation boxes database.
 */




/*
 * 
 */

function db_print_response_message( $response )
{
    $script = '<script>';
    $script .= 'jQuery(document).ready(';
    $script .= 'function(){';
    $script .= "var pageTitle = jQuery('div #message');";    
    
    if ( is_wp_error($response) )
    {
        $error_message = $response->get_error_message();
        
        $script .= "pageTitle.after('<div class=\"error notice notice-success is-dismissible \"><p>";
        $script .= "The donation project data could not be <b>sent</b> to the donation boxes database!<br>";
        $script .= $error_message;
    }
    elseif ( $response['response']['code'] == '200' ) // Add new or update a donation project.
    {
        $script .= "pageTitle.after('<div class=\"updated notice notice-success is-dismissible \"><p>";
        $script .= "The donation project data has been also sent it successfully in the donation boxes database.<br>";
        $script .= 'Donation boxes database : ' . trim($response['body']) ;
    }
    else if ( $response['response']['code'] == '455' ) // Invalid credentials
    {
        $script .= "pageTitle.after('<div class=\"error notice notice-success is-dismissible \"><p>";
        $script .= "Invalid user credentials! The donation project data could not be <b>saved</b> to the donation boxes database, ";
        $script .= "because you haven\'t provided the appropriate user credentials.<br>";
        $script .= $response['response']['code'] . ' [ Invalid credentials ] ';
    }
    else if ( $response['response']['code'] == '460' ) // The donation project was deleted from donation box database.
    {
        $script .= "pageTitle.after('<div class=\"updated notice notice-success is-dismissible \"><p>";
        $script .= "The donation project was <b>deleted</b> successfully from donation box database.<br>";
        $script .= 'Donation boxes database : ' . trim($response['body']) ;
    }
    else // != 200 , != 455 , != 460
    {
        $script .= "pageTitle.after('<div class=\"error notice notice-success is-dismissible \"><p>";
        $script .= "The donation project data could not be <b>saved</b> to the donation boxes database!<br>";
        $script .= $response['response']['code'] . ' [' . $response['response']['message'] . '] ';
    }

    $script .= "</p></div>'); }); </script>";
    
    echo $script;
    return $response['response']['code'];
}





/*
 * This function are responsible to collect all project data.
 * 
 * Attention :
 * 1) Some data may not have been given by the user, for this reason they are made
 * the necessary checks.
 * 2) The current amount of money that has been collected for the donation
 * project is never taken from the WordPress database.
 * 
 * @project_id : The donation project id for which all data will be collected
 *               from WordPress database.
 * 
 * @return : A array with all data of current project.
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





/*
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
 */

function db_send_data_to_donationBox_database( $donation_project_id )
{
    if (  ( ! db_post_status_is_draft ) || ( ! db_post_type_is_donationboxes($donation_project_id) ) )
    {
        return; // No data is sent to the donation boxes database.
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
    
    db_print_response_message($response);
}





/*
 * This function called when a donation project must be deleted from the 
 * donation box database.
 * 
 * @donation_project_id : The donation project id for which a delete request
 * will be sent to the database.
 * 
 */

function db_delete_data_from_donationBox_database( $donation_project_id )
{
    $body = array(
        'username'              => get_option( 'db_username_field' ),
        'password'              => get_option( 'db_password_field' ),
        'id'                    => $donation_project_id,
        'delete'                => 1,
    );

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
    
    return db_print_response_message($response);
}
