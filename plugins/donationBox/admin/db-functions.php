<?php

/* 
 * Useful functions used by the plugin-in.
 */


define('KB', 1024);
define('MB', 1048576);
define('GB', 1073741824);





/*
 * A function with which i find the user ip address.
 * @return : The user ip address.
 */

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





/*
 * Function that is targeted to delete the stylesheet file.
 * Deletes from a specific post, the stylesheet file, if it exists.
 * 
 * @post_id : The donation project for which to delete (if exist) the stylesheet
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





/*
 * Function that is targeted to delete the video file.
 * Deletes from a specific post, the video file, if it exists.
 * 
 * @post_id : The donation project for which to delete (if exist) the video
 * file.
 * 
 */

function db_delete_video_file( $post_id )
{
    $video = get_post_meta($post_id, '_db_project_video_file', true);
    if ( count($video) > 0  &&  is_array($video) )
    {
        wp_delete_file( $video[0]['file'] );
        delete_post_meta($post_id, '_db_project_video_file');
    }
}





/*
 * Function that is targeted to delete the image file.
 * Deletes from a specific post, the image file, if it exists.
 * 
 * @post_id : The donation project for which to delete (if exist) the image
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





/*
 * Function returning if the post is 'donationboxes' post type.
 * 
 * @post_id : The donation project id for which it will check if it is post type
 * 'donationboxes'.
 * 
 * @return : True if it is, or false if its not.
 * 
 */

function db_post_type_is_donationboxes( $post_id )
{
    return get_post_type($post_id) === "donationboxes";
}





/*
 * Function returning if the post status is 'draft' or no.
 * 
 * @post_id : The donation project id for which it will check if it is draft.
 * 
 * @return : True if it is, or false if its not.
 * 
 */

function db_post_status_is_draft( $post_id )
{
    return get_post_status( $post_id ) == 'auto-draft' || get_post_status( $post_id ) == 'draft';
}





/*
 * Function that is responsible for displaying the error code ($code)
 * and the error message to the user in the right area.
 */

function db_print_user_error( $code , $message )
{
    $class = 'notice notice-error is-dismissible';
    $message_title = __( 'Failed saving/updating.' , 'sample-text-domain' );

    echo '<div class="'.$class.'"><p><b>Failed!</b> '.$message_title.'<br>'.$code.': '.$message.' </p></div>';
}