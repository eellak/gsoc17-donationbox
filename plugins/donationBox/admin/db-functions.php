<?php

/**
 * Useful functions used from the plugin-in.
 * 
 */


define('kB', 1024);
define('MB', 1024 * 1024);
define('GB', 1024 * 1024 * 1024);





/**
 * Function which return the user's IP address..
 * 
 * @return : The user ip address.
 * 
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





/**
 * Function that is targeted to delete the stylesheet file.
 * Deletes from a specific post, the stylesheet file, if it exists.
 * 
 * @param post_id : The donation project for which to delete (if exist) the stylesheet
 * file.
 * 
 */

function db_delete_css_file( $post_id )
{
    $css = get_post_meta($post_id, '_db_project_stylesheet_file', true );
    if ( count($css) > 0  &&  is_array($css) )
    {
        wp_delete_file( $css[0]['file'] );
        delete_post_meta($post_id, '_db_project_stylesheet_file');
    }
}





/**
 * Function that is targeted to delete the video file.
 * Deletes from a specific post, the video file, if it exists.
 * 
 * @param post_id : The donation project for which to delete (if exist) the video
 * file.
 * 
 */

function db_delete_video_file( $post_id )
{
    $video = get_post_meta($post_id, '_db_project_video_file', true);
    if ( count($video) > 0 && is_array($video) )
    {
        wp_delete_file( $video[0]['file'] );
        delete_post_meta($post_id, '_db_project_video_file');
    }
}





/**
 * Function that is targeted to delete the image file.
 * Deletes from a specific post, the image file, if it exists.
 * 
 * @param post_id : The donation project for which to delete (if exist) the image
 * file.
 * 
 */

function db_delete_image_file( $post_id )
{
    $image = get_post_meta($post_id, '_db_project_image_file', true);
    if ( count($image) > 0  &&  is_array($image) )
    {
        wp_delete_file( $image[0]['file'] );
        delete_post_meta($post_id, '_db_project_image_file');
    }
}





/**
 * Function returning if the post is 'donationboxes' post type.
 * 
 * @param string $post_id : The donation project id for which it will check if
 * it is post type 'donationboxes'.
 * 
 * @return boolean : TRUE if it is, or FALSE if its not.
 * 
 */

function db_post_type_is_donationboxes( $post_id )
{
    return get_post_type($post_id) == "donationboxes";
}





/**
 * Function returning if the post status is 'draft' or not.
 * 
 * @param string $post_id : The donation project id for which it will check if it is draft.
 * 
 * @return boolean : TRUE if it is, or FALSE if its not.
 * 
 */

function db_post_status_is_draft( $post_id )
{
    return get_post_status( $post_id ) == 'auto-draft' || get_post_status( $post_id ) == 'draft';
}





/**
 * Function that is responsible for displaying the error code ($code)
 * and the error message to the user in the right area.
 * 
 * @param string $code : The message code.
 * @param string $message : The message.
 * 
 * @return string : Displays in the appropriate way the code and the message
 * to the user as a WordPress notice.
 * 
 * Reference : https://codex.wordpress.org/Plugin_API/Action_Reference/admin_notices
 * 
 */

function db_print_user_error( $code , $message )
{
    $class = 'notice notice-error is-dismissible';
    $message_title = __( 'Failed saving/updating.' , 'sample-text-domain' );

    echo '<div class="'.$class.'"><p><b>Failed!</b> '.$message_title.'<br>'.$code.': '.$message.' </p></div>';
}





/**
 * The following php function, will be executed upon request via AJAX for 
 * action "db_is_project_creator".
 * 
 * Its purpose to check two things:
 *      - If the user belongs to the category (has the role) "project_creator"
 *      - If it is a page which has post_type "donationboxes".
 * 
 * @return : If it meets the above requirements, true or false if he doesn't.
 * 
 */

function db_ajax_remove_trash_and_remove()
{
    $url = wp_get_referer();
    $parts = parse_url($url);
    parse_str($parts['query'], $query);

    if ( current_user_can('project_creator') && $query['post_type'] == 'donationboxes' )
    {
        echo TRUE;
    }
    else
    {
        echo FALSE;
    }

    wp_die();
}

add_action('wp_ajax_db_is_project_creator', 'db_ajax_remove_trash_and_remove');
add_action('wp_ajax_nopriv_db_is_project_creator', 'db_ajax_remove_trash_and_remove');





/**
 * Function which searches if there is a WordPress cron job for a specific
 * donation project.
 * 
 * Attention : In my implementation, happens to create processes ( WordPress
 * cron jobs ) with only two specific names 'db_cron_hook_insert_update'
 * and 'db_cron_hook_delete'. So, then, the way to distinguish them is from seeing
 * for what donation project ( from $id ) they are working on.
 * I acknowledge the donation project from the id.
 * Also, each process have three parameters, one of them, is the donation project
 * id for which they work. Because of this parameter, i distinguish the processes
 * between them.
 * 
 * @param string $id : The donation project id.
 * 
 * @return boolean : TRUE if it find process with the parameter id, otherwise
 * it returns FALSE.
 * 
 */

function db_cron_exists( $id )
{
    $all_cron_jobs = _get_cron_array();

    foreach ($all_cron_jobs as $cron_job)
    {
        if ( $cron_job['db_cron_hook_insert_update'] || $cron_job['db_cron_hook_delete'])
        {
            foreach ($cron_job as $value)
            {
                foreach ($value as $temp )
                {
                    if ( $temp['args'][0] == $id )
                    {
                        return TRUE;
                    }
                }
            }
        }
    }
    return FALSE;
}





/**
 * Function where delete the WordPress cron job if it finds it.
 * 
 * Attention : In my implementation, happens to create processes ( WordPress
 * cron jobs ) with only two specific names 'db_cron_hook_insert_update'
 * and 'db_cron_hook_delete'. So, then, the way to distinguish them is from seeing
 * for what donation project ( from $id ) they are working on.
 * I acknowledge the donation project from the id.
 * Also, each process have three parameters, one of them, is the donation project
 * id for which they work. Because of this parameter, i distinguish the processes
 * between them.
 * 
 * @param string $id : The donation project id for which he will look for what
 * WordPress cron job he have it.
 * 
 * @return Array : The WordPress built-in array with all cron jobs with out the
 * WordPress cron job who found it (if find) with the id that receives as parameter.
 * 
 */

function db_delete_cron_job( $id )
{
    $crons = _get_cron_array();

    foreach ( $crons as $key => $job )
    {

        if ( ( key($job) != 'db_cron_hook_insert_update' ) && ( key($job) != 'db_cron_hook_delete' ) )
        {
            continue;
        }

        foreach ($job as $sub_key => $cron_job )
        {
            if ( $cron_job[key($cron_job)]['args'][0] == $id )
            {
                unset( $crons[$key][$sub_key] );
            }
            
        }
    }
    
    _set_cron_array($crons);
    
}





