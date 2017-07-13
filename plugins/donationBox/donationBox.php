<?php
/**
 * @package donationBox
 */
/*
Plugin Name: DonationBox
Plugin URI: https://github.com/eellak/gsoc17-donationbox/
Description: Πρόκειται για ένα αρχικό testing plugin..
Version: 0.1
Author: Tas-sos
Author URI: https://github.com/eellak/gsoc17-donationbox/
License: GPLv2
Text Domain: donationBox
*/

/*
This program is free software; you can redistribute it and/or
modify it under the terms of the GNU General Public License
as published by the Free Software Foundation; either version 2
of the License, or (at your option) any later version.

This program is distributed in the hope that it will be useful,
but WITHOUT ANY WARRANTY; without even the implied warranty of
MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
GNU General Public License for more details.

You should have received a copy of the GNU General Public License
along with this program; if not, write to the Free Software
Foundation, Inc., 51 Franklin Street, Fifth Floor, Boston, MA  02110-1301, USA.

Copyleft 2017 ~ Google Summer of Code! :)
*/

/* For Security Reasons. */
if ( !defined( 'ABSPATH' ) )
{
    exit;
}

define('WP_DEBUG', true);
define('WP_DEBUG_LOG', true);
define('WP_DEBUG_DISPLAY', true);

require_once( plugin_dir_path(__FILE__) . 'admin/db-functions.php');
require_once( plugin_dir_path(__FILE__) . 'admin/db-validations.php');



/*
 * Definition of Global Variables for handling errors.
 */

global $db_error;

$db_error = array(
            'have'  => false,
            'message_code'  => '0'
                );


/*
 * Plugin Activation.
 * 
 * What to do when the plugin activated.
 */

register_activation_hook( __FILE__, 'db_create_role_for_users');


function db_create_role_for_users()
{

    $capabilities = array(
        'read'                      => true,
        'edit_posts'                => true,
        'edit_others_posts'         => true,
        'edit_private_posts'        => true,
        'edit_published_posts'      => true,
        'delete_posts'              => true,
        'delete_others_posts'       => true,
        'delete_published_posts'    => false,
        'publish_posts'             => true

    );
    
    add_role('project_creator', 'Project Creator', $capabilities);
}





/*
 * Plugin Deactivation.
 * 
 * What to do when the plugin deactivated.
 */

register_deactivation_hook( __FILE__, 'db_delete_role_for_users');
function db_delete_role_for_users()
{
    remove_role('project_creator');
}


//register_unistall_hook() : 




// Create plugin menu.
function db_create_plugin_menu()
{
    require_once( plugin_dir_path(__FILE__) . 'admin/db-donation-boxes-menu.php' );
}

add_action('plugins_loaded', 'db_create_plugin_menu');





//Add my custom endpoints to the REST WordPress API.
/**
 * Grab latest post title by an author!
 *
 * @param array $request all options - arguments from the request.
 * @return string|null Post title for the latest,  * or null if none.
 * 
 */

function db_create_endpoint_REST_API( $request ) 
{
    
    $date_param = $request->get_param( 'date' );
    $time_param = $request->get_param( 'time' );
    
    // Search args :
    $args = array(
                'post_type' => 'donationboxes'
                );
    
    // Run query :
    $posts = get_posts( $args );

    if ( empty( $posts ) ) 
    {
        return new WP_Error( 'awesome_no_author', 'Invalid author', array( 'status' => 404 ) );
    }
    
    $data = array();
    
    for ( $i = 0; $i < count($posts); $i++ )
    {
        // Because post_modified is like "2017-06-20 13:50:08", that's very good! :)
        
        $request_date = new DateTime( $date_param .' '.$time_param );
        $currnet_post_time = new DateTime($posts[$i]->post_modified);
        
        if ( $currnet_post_time > $request_date )
        {
            $data[$i]['ID'] = $posts[$i]->ID;
            $data[$i]['post_modified'] = $posts[$i]->post_modified;
        }
    }
    
    if ( empty($data) )
    {
        $data = null;
    }

    
    $response = new WP_REST_Response( $data );

    // Add a custom status code
    $response->set_status( 201 );
    
    return $response ;
}


/*
 * To make this available via the API, we need to register a route. 
 * This tells the API to respond to a given request with our function.
 * 
 * “Route” is the URL, whereas “endpoint” is the function behind it that 
 * corresponds to a method and a URL.
 * 
 * If your site domain is example.com and you’ve kept the API path of wp-json,
 * then the full URL would be :
 * http://example.com/wp-json/myplugin/v1/author/(?P\d+).
 * for my example : 
 * http://localhost:8000/wp-json/myplugin/v1/author/1
 * 
 */

function db_custm_rest_route()
{
    register_rest_route( 
            'donationboxes/v1',
            '/updated/(?P<date>([0-9]{4}-(0[1-9]|1[0-2])-(0[1-9]|[1-2][0-9]|3[0-1])))/(?P<time>(([01]?[0-9]|2[0-3]):([0-5][0-9]):([0-5][0-9])))',
            array(
                'methods' => 'GET',
                'callback' => 'db_create_endpoint_REST_API',
                'args' => array(
                            'date' => array(
                                        'validate_callback' => function($param, $request, $key) 
                                        {
                                            return preg_match("/^[0-9]{4}-(0[1-9]|1[0-2])-(0[1-9]|[1-2][0-9]|3[0-1])$/", $param );
                                        }
                                        ),
                            'time' => array(
                                        'validate_callback' => function($param, $request, $key) 
                                        {
                                            return preg_match("/^([01]?[0-9]|2[0-3]):([0-5][0-9]):([0-5][0-9])?$/", $param );
                                        }
                                        )
                                ),
                )
    );
}

add_action( 'rest_api_init', 'db_custm_rest_route');





// function my_post_updated_messages_filter($messages)
//{
////      $messages['trashed'][1] = 'Publish not allowed';
//      return $messages . 'odsdsfsdfssssssssssssss';
// }
// 
// add_filter('post_updated_messages', 'my_post_updated_messages_filter');





function my_bulk_post_updated_messages_filter( $bulk_messages, $bulk_counts )
{
    $singular = 'donation project';
    $plural = 'donation projects';
    
    $bulk_messages['donationboxes'] = array(
        'updated'   => _n( '%s ' . $singular . ' updated.', '%s ' . $plural . ' updated.', $bulk_counts['updated'] ),
        'locked'    => _n( '%s ' . $singular . ' not updated, somebody is editing it.', '%s ' . $plural . ' not updated, somebody is editing them.', $bulk_counts['locked'] ),
        'deleted'   => _n( '%s ' . $singular . ' permanently deleted.', '%s ' . $plural . ' permanently deleted.', $bulk_counts['deleted'] ),
        'trashed'   => _n( '%s ' . $singular . ' moved to the Trash.', '%s ' . $plural . ' moved to the Trash.', $bulk_counts['trashed'] ),
        'untrashed' => _n( '%s ' . $singular . ' restored from the Trash.', '%s ' . $plural . ' restored from the Trash.', $bulk_counts['untrashed'] ),
    );

    return $bulk_messages;

}

add_filter( 'bulk_post_updated_messages', 'my_bulk_post_updated_messages_filter', 10, 2 );





function db_add_cron_interval( $schedules )
{
    $schedules['five_seconds'] = array(
        'interval' => 5,
        'display'  => esc_html__( 'Every Five Seconds' ),
    );
 
    return $schedules;
}

add_filter( 'cron_schedules', 'db_add_cron_interval' );

