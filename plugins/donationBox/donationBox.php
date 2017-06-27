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


global $db_error;
$db_error = array(
            'have'  => false,
            'message'  => 'Unexpected error.',
                );


// Plugin Activation :
register_activation_hook( __FILE__, 'db_create_role_for_users');
function db_create_role_for_users()
{
//    $capabilities = array(
//                            'publish_posts'             => 'db_publish_posts',
//                            'edit_posts'                => 'db_edit_posts',
//                            'edit_others_posts'         => 'db_edit_others_posts',
//                            'delete_posts'              => 'db_delete_posts',
//                            'delete_others_posts'       => 'db_delete_others_posts',
//                            'read_private_posts'        => 'db_read_private_posts',
//                            'edit_post'                 => 'db_edit_post',
//                            'delete_post'               => 'db_delete_post',
//                            'read_post'                 => 'db_read_post',
//                            'read'                      => 'db_read',
//                            'upload_files'              => 'db_upload_files',
//                            'delete_published_posts'    => 'db_delete_published_posts',
//                            'edit_private_posts'        => 'db_edit_private_posts',
//                            'edit_published_posts'      => 'db_edit_published_posts',
//                        );

    
//        $capabilities = array(
//                            'db_publish_posts'                => true,
//                            'db_edit_posts'                   => true,
//                            'db_edit_others_posts'            => true,
//                            'db_delete_posts'                 => true,
//                            'db_delete_others_posts'          => true,
//                            'db_read_private_posts'           => true,
//                            'db_edit_post'                    => true,
//                            'db_delete_post'                  => true,
//                            'db_read_post'                    => true,
//                            'db_read'                         => true,
//                            'db_upload_files'                 => true,
//                            'db_delete_published_posts'       => true,
//                            'db_edit_private_posts'           => true,
//                            'db_edit_published_posts'         => true,
//                        );
    
    
    $capabilities = array(
        'read'                      => true,
        'edit_posts'                => true,
        'edit_others_posts'         => true,
        'edit_private_posts'        => true,
        'edit_published_posts'      => true,
        'delete_posts'              => true,
        'delete_others_posts'       => true,
        'delete_published_posts'    => true,
        'publish_posts'             => true
//        'upload_files'              => true, // να μην μπορεί να ανεβάζει stylesheet files.
//        'manage_categories'         => true, // να μην μπορεί να αλλάζει την κατηγορία των donation projects 
        
        

        
    );
    
    add_role('project_creator', 'Project Creator', $capabilities);
}





// Plugin Deactivation :
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
 * @param array $data Options for the function.
 * @return string|null Post title for the latest,  * or null if none.
 * 
 * Αυτή είναι μια συνάρτηση που την κατασκευάζω να κάνει κάτι..
 * αυτή την συνάρτηση θα ορίσω παρακάτω σε ένα endpoint να την καλεί.. ;)
 */
function my_awesome_func( $request ) 
{
    
    $date_param = $request->get_param( 'date' );
    $time_param = $request->get_param( 'time' );
    
    // Search args :
    $args = array(
//                'author' => $data['id'],
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
    
    // Αν δε βρει τίποτα :
    if ( empty($data) )
    {
        $data = null;
    }
        

    
//        $response->set_data( $current_post[0]->post_title );
    
//    $response->set_data($posts[0]->post_title);
    
//    $ok = array( "1" => 'ok' , "2" => 'pera vrexi' );
//    $response->set_data( $ok );
    
//    $controller = new WP_REST_Posts_Controller( 'type' );
//
//    foreach ( $posts as $post )
//    {
//        $data    = $controller->prepare_item_for_response( "$post", $request );
//        $posts[] = $controller->prepare_response_for_collection( $data );
//    }
    
    
    $response = new WP_REST_Response( $data );

    // Add a custom status code
    $response->set_status( 201 );

    // Add a custom header
//    $response->header( 'Location', 'http://example.com/' );
    
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
 * Εδώ από ότι καταλαβαίνω κατασκευάζω - σχεδιάζω - θέτω την REST API διαδρομή.
 * Τι θα δέχετε ως όρισμα, 
 * τι επιτρεπόμενες μεθόδους καλέσματος έχει
 * και τέλος ποια συνάρτηση θα ανταποκρίνετε στο κάλεσμα αυτής της διαδρομής.
 * 
 */


function db_custm_rest_route()
{
    register_rest_route( 
            'donationboxes/v1',          /* Namespace - Route. */
            '/updated/(?P<date>([0-9]{4}-(0[1-9]|1[0-2])-(0[1-9]|[1-2][0-9]|3[0-1])))/(?P<time>(([01]?[0-9]|2[0-3]):([0-5][0-9]):([0-5][0-9])))',  /* Endpoint with parameters. - Μπορώ να ορίσω και άλλα μάλλον - */
            array(
                'methods' => 'GET', /* Τι μεθόδοι επιτρέπονται για αυτό το endpoint. */
                'callback' => 'my_awesome_func', /* Call back function for this endpoint. */
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
//                                'permission_callback' => function () 
//                                {
//                                    return current_user_can( 'edit_others_posts' );
//                                }
                )
    );
}

add_action( 'rest_api_init', 'db_custm_rest_route');


