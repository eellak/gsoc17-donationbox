<?php

/* 
 * Modifying Responses from Built-In WordPress REST API.
 * Add JSON fields about donation project informations to built-in REST API responses.
 * The following new fields is added :
 *                                      - project_status
 *                                      - project_current_amount
 *                                      - project_target_amount
 *                                      - project_organizations
 *                                      - project_stylesheet_file
 * The user either via : 
 *  > For all donation projects :                   http://localhost:8000/wp-json/wp/v2/donationboxes
 *  > For specific range of last modified date :    http://localhost:8000/wp-json/wp/v2/donationboxes/updated/2017-06-18/18:38:33
 *  > For specific project via id :                 http://localhost:8000/wp-json/wp/v2/donationboxes/108
 * 
 * He will receive all the informations about the donation project.
 * 
 */


// Project status :
function db_add_rest_status()
{
    register_rest_field( 'donationboxes', 'project_status', array(
            'get_callback' => 'get_status',
            'update_callback' => null,
            'schema' => null,
            )
        );

    function get_status( $post )
    {
        return get_post_meta( $post['id'], '_db_project_status',true) == 1 ? 'Activate' : 'Deactivate';
    }
    
}

add_action( 'rest_api_init', 'db_add_rest_status' );





// Project Current amount :
function db_add_rest_current_amount()
{
    register_rest_field( 'donationboxes', 'project_current_amount', array(
            'get_callback' => 'get_current_amount',        
            'update_callback' => null,
            'schema' => null,
            )
        );
        
    function get_current_amount( $post )
    {
        return get_post_meta( $post['id'], '_db_project_current_amount' , true);
    }
    
}

add_action( 'rest_api_init', 'db_add_rest_current_amount' );





// Project Target amount :
function db_add_rest_target_amount()
{
    register_rest_field( 'donationboxes', 'project_target_amount', array(
            'get_callback' => 'get_target_amount',   
            'update_callback' => null,
            'schema' => null,
            )
        );
    
    function get_target_amount( $post )
    {
        return get_post_meta( $post['id'], '_db_project_target_amount' , true);
    }
    
}

add_action( 'rest_api_init', 'db_add_rest_target_amount' );





// Project Start date :
function db_add_rest_start_date()
{
    register_rest_field( 'donationboxes', 'project_start_date', array(
            'get_callback' => 'get_start_date',   
            'update_callback' => null,
            'schema' => null,
            )
        );
    
    function get_start_date( $post )
    {
        return get_post_meta( $post['id'], '_db_project_start_date' , true);
    }
    
}

add_action( 'rest_api_init', 'db_add_rest_start_date' );





// Project end date :
function db_add_rest_end_date()
{
    register_rest_field( 'donationboxes', 'project_end_date', array(
            'get_callback' => 'get_end_date',   
            'update_callback' => null,
            'schema' => null,
            )
        );
    
    function get_end_date( $post )
    {
        return get_post_meta( $post['id'], '_db_project_end_date' , true);
    }
    
}

add_action( 'rest_api_init', 'db_add_rest_end_date' );





// Project Orgaanization(s) :
function db_add_rest_organizations()
{
    register_rest_field('donationboxes', 'project_organizations' , array(
            'get_callback'      =>  'get_organizations',
            'update_callback'   => null,
            'schema'            => null,
            )
        );
    
    function get_organizations( $post )
    {
        $organizations = get_the_terms( $post['id'], 'organization' );
        $pure_organizations_name = array();
        
        foreach($organizations as $term)
        {
            array_push($pure_organizations_name, $term->name);
        }
        
        return $pure_organizations_name;        
    }
}

add_action('rest_api_init' , 'db_add_rest_organizations' );





// Project stelysheet file :
function db_add_rest_stylesheet_file()
{
    register_rest_field('donationboxes', 'project_stylesheet_file' , array(
            'get_callback'      =>  'get_stylesheet_file',
            'update_callback'   => null,
            'schema'            => null,
            )
        );
    
    function get_stylesheet_file( $post )
    {
        $project_css_file = get_post_meta( $post['id'], '_db_project_stylesheet_file', true );

        if ( count($project_css_file) > 0  &&  is_array($project_css_file) )
        {
            return utf8_uri_encode( $project_css_file[0]['url'] );
        }
        else
        {
            return get_site_url() . '/wp-content/themes/influence-child/css/style.css';
        }
        
    }
}

add_action('rest_api_init' , 'db_add_rest_stylesheet_file' );





// Project video file :
function db_add_rest_video_file()
{
    register_rest_field('donationboxes', 'project_video_file' , array(
            'get_callback'      =>  'get_video_file',
            'update_callback'   => null,
            'schema'            => null,
            )
        );
    
    function get_video_file( $post )
    {
        $project_video_file = get_post_meta( $post['id'], '_db_project_video_file', true );

        if ( count($project_video_file) > 0  &&  is_array($project_video_file) )
        {
            return utf8_uri_encode( $project_video_file[0]['url'] );
        }
        else
        {
            return '';
        }
        
    }
}

add_action('rest_api_init' , 'db_add_rest_video_file' );





// Project image file :
function db_add_rest_image_file()
{
    register_rest_field('donationboxes', 'project_image_file' , array(
            'get_callback'      =>  'get_image_file',
            'update_callback'   => null,
            'schema'            => null,
            )
        );
    
    function get_image_file( $post )
    {
        $project_image_file = get_post_meta( $post['id'], '_db_project_image_file', true );

        if ( count($project_image_file) > 0  &&  is_array($project_image_file) )
        {
            return utf8_uri_encode( $project_image_file[0]['url'] );
        }
        else
        {
            return '';
        }
        
    }
}

add_action('rest_api_init' , 'db_add_rest_image_file' );



