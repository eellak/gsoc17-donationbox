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




/* Add Organization Taxonomy. */
function db_register_organization_taxonomy()
{

    $plural = 'Organizations';
    $singular = 'Organization';

    $labels = array(
		'name'                          => $plural,
		'singular_name'                 => $singular,
		'search_items'                  => 'Search ' . $plural,
		'all_items'                     => 'All ' . $plural,
		'parent_item'                   => null,
		'parent_item_colon'             => null,
		'edit_item'                     => 'Edit ' . $singular,
		'update_item'                   => 'Update ' . $singular,
		'add_new_item'                  => 'Add New ' . $singular,
		'new_item_name'                 => 'New ' . $singular . ' Name',
		'separate_items_with_commas'    => 'Separate ' . $plural . ' with commas',
		'add_or_remove_items'           => 'Add or remove ' . $plural,
		'choose_from_most_used'         => 'Choose from the most used ' . $plural,
		'not_found'                     => 'No ' . $plural . ' found.',
		'menu_name'                     =>  $singular,
                );

    $args = array(
                'hierarchical'              => true,
                'labels'                    => $labels,
                'show_ui'                   => true,
                'show_admin_column'         => true,
                'update_count_callback'   	=> '_update_post_term_count',
                'query_var'                 => true,
                'rewrite'                   => array( 'slug' => 'organizations' ),
                );


    register_taxonomy('organization', array('donationboxes'), $args);
}


add_action( 'init' , 'db_register_organization_taxonomy' );







/* Create new submenu to add new Donation project - with register post type */
function db_register_new_post_type()
{

    $labels = array(
		'name'               => _x( 'DonationBoxes', 'post type general name', 'your-plugin-textdomain' ),
		'singular_name'      => _x( 'DonationBoxes', 'post type singular name', 'your-plugin-textdomain' ),
		'menu_name'          => _x( 'DonationBoxes', 'admin menu', 'your-plugin-textdomain' ),
		'name_admin_bar'     => _x( 'Donation Project', 'add new on admin bar', 'your-plugin-textdomain' ),
		'add_new'            => _x( 'Add New Project', 'book', 'your-plugin-textdomain' ),
		'add_new_item'       => __( 'Add New Donation Project', 'your-plugin-textdomain' ),
		'new_item'           => __( 'New Project', 'your-plugin-textdomain' ),
		'edit_item'          => __( 'Edit Project', 'your-plugin-textdomain' ),
		'view_item'          => __( 'View Project', 'your-plugin-textdomain' ),
		'all_items'          => __( 'All Donation Projects', 'your-plugin-textdomain' ),
		'search_items'       => __( 'Search Projects', 'your-plugin-textdomain' ),
		'parent_item_colon'  => __( 'Parent Projects:', 'your-plugin-textdomain' ),
		'not_found'          => __( 'No projects found.', 'your-plugin-textdomain' ),
		'not_found_in_trash' => __( 'No projects found in Trash.', 'your-plugin-textdomain' )
                );


    $args = array(
		'labels'             => $labels,
		'description'        => __( 'Description.', 'your-plugin-textdomain' ),
		'public'             => true,
		'publicly_queryable' => true,
		'show_ui'            => true,
		'show_in_menu'       => true,
		'query_var'          => true,
		'rewrite'            => array( 'slug' => 'donationBoxes' ),
		'capability_type'    => 'post',
		'has_archive'        => true,
		'hierarchical'       => false,
		'menu_icon'          => 'dashicons-feedback',
		'menu_position'      => 26,
		'supports'           => array( 'title', 'editor', 'author', 'thumbnail', 'excerpt', 'comments', 'custom-fields', 'revisions' ),
		'taxonomies'         => array( 'organization' ),
                );

    register_post_type('donationboxes', $args);
}

add_action('init', 'db_register_new_post_type' );






/* For custom submenu. */
function add_new_menu_and_submenu_items()
{

    add_submenu_page(
        'edit.php?post_type=donationboxes', // Parrent menu Slug.
        'Donation Boxes Settings',          // Page title.
        'General Settings',                 // The side bar Menu title.
        'edit_posts',                       // Capability
        'db-settings-menu',                 // menu_slug
        'db_settings_page'                  // function - Επίσης βάζω την ίδια συνάρτηση. Για να εμφανίσει ένα πράγματα.
        );

}

function db_settings_page()
{
    require_once( plugin_dir_path(__FILE__) . 'admin/db_settings_page.php' );
}

/* WordPress Settings API. */

function display_options()
{

    // For "General" submenu :

    add_settings_section(
            "general_section",                   /* The unique name of section. */
            "General Settings",                  /* The display name of section. */
            "display_header_options_content",    /* Callback function. */
            "db-settings-menu");                 /* Page to which section is attached. */



    add_settings_field(
            "database_url_field",        /* The unique setting ID name. */
            "Database Url",              /* The display name of field. */
            "display_logo_form_element", /* Callback function. */
            "db-settings-menu",          /* Page in which field is displayed. */
            "general_section");          /* Section. */


    register_setting("general_section", "database_url_field");

}

function display_header_options_content()
{
     echo "The following settings apply to all donation boxes.";
}


function display_logo_form_element()
{

    ?>
        <input type="text" name="database_url_field" id="database_url_field" placeholder="https://..." required="required" aria-describedby="tagline-description" />
        <p id="tagline-description" class="description">The database should be there.</p>
    <?php
}


add_action("admin_menu", "add_new_menu_and_submenu_items");
add_action("admin_init", "display_options");


/* Metaboxes. */

function db_ps_callback()
{
        ?>
        Active     <input type="radio" name="db_project_state_field" id="db_project_state_field" />
        <br />
        Deactivate <input type="radio" name="db_project_state_field" id="db_project_state_field"  />
    <?php
}


function db_project_status_metabox()
{
    add_meta_box(
            'db_ps_status_metabox', // id
            'Project Status',       // title
            'db_ps_callback',       // callback function
            'donationboxes',        // page
            'side'                  // position
            );
}


add_action('add_meta_boxes' , 'db_project_status_metabox');


function db_target_amount_callback()
{
    ?>
        <input type="number" name="db_project_amount_field" id="db_project_amount_field"  />
    <?php
}


function db_project_target_amount_metabox()
{
    add_meta_box(
            'db_amount_metabox',            // id
            'Target amount',                // title
            'db_target_amount_callback',    // callback function
            'donationboxes',                // page
            'side'                          // position
            );
}

add_action('add_meta_boxes' , 'db_project_target_amount_metabox');

