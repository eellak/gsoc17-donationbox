<?php

require_once( plugin_dir_path(__FILE__) . 'db-functions.php');


/* Create new submenu to add new Donation project - with register post type */
function db_register_new_post_type()
{
    $labels = array(
		'name'               => _x( 'DonationBoxes', 'post type general name', 'your-plugin-textdomain' ),
		'singular_name'      => _x( 'DonationBoxes', 'post type singular name', 'your-plugin-textdomain' ),
		'menu_name'          => _x( 'Donation Boxes', 'admin menu', 'your-plugin-textdomain' ), // Side-bar menu name
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
		'capability'         => 'project_creator',
		'map_meta_cap'       => true,
		'has_archive'        => true,
		'hierarchical'       => false,
		'menu_icon'          => 'dashicons-feedback',
		'menu_position'      => 26,
		'supports'           => array( 'title', 'editor', 'author', 'thumbnail', 'excerpt', 'comments', 'custom-fields', 'revisions' ),
		'taxonomies'         => array( 'organization' ),
		'show_in_rest'       => true,
		'rest_base'          => 'donationboxes',
		'rest_controller_class' => 'WP_REST_Posts_Controller',
                );
    
    register_post_type('donationboxes', $args);
}

add_action('init', 'db_register_new_post_type' );



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
                'update_count_callback'     => '_update_post_term_count',
                'query_var'                 => true,
                'rewrite'                   => array( 'slug' => 'organizations' ),
                'show_in_rest'              => true,
                'rest_base'                 => 'organization',
                'rest_controller_class'     => 'WP_REST_Terms_Controller',
                );
    
    
    register_taxonomy('organization', 'donationboxes', $args);
}


add_action( 'init' , 'db_register_organization_taxonomy' );






/* For custom submenu - Donation Boxes Settings . */
function db_add_custom_settings_submenu()
{
    
    add_submenu_page(
        'edit.php?post_type=donationboxes', // Parrent menu Slug.
        'Donation Boxes Settings',          // Page title.
        'General Settings',                 // The side bar Menu title.
        'administrator',                    // Capability.
        'db-settings-menu',                 // menu_slug.
        'db_settings_page'                  // Callback Function.
        );  

}

function db_settings_page()
{
    require_once( plugin_dir_path(__FILE__) . 'db-submenu-settings-page.php' );
}



/* Add settings using WordPress Settings API. */
function display_options()
{
    // For "General" submenu :

    add_settings_section(
        "general_section",                   /* The unique name of section. */
        "General Settings",                  /* The display name of section. */
        "display_header_options_content",    /* Callback Function. */
        "db-settings-menu");                 /* Page to which section is attached. */

    
    add_settings_field(
        "db_username_field",            /* The unique setting ID name. */
        "Username",                     /* The display name of field. */
        "display_user_form_element",    /* Callback Function. */
        "db-settings-menu",             /* Page in which field is displayed. */
        "general_section");             /* Section. */

    add_settings_field(
        "db_password_field",
        "Password",
        "display_password_form_element",
        "db-settings-menu",
        "general_section");

    add_settings_field(
        "database_url_field",
        "Database Url",
        "display_database_form_element",
        "db-settings-menu",
        "general_section");


    register_setting("general_section", "database_url_field");
    register_setting("general_section", "db_username_field");
    register_setting("general_section", "db_password_field");

        

}

function display_header_options_content()
{
     echo "The following settings apply to all donation boxes.";
}


function display_database_form_element()
{
    $database_URL = esc_url( get_option( 'database_url_field' ) ) ;
    ?>
        <input type="text" name="database_url_field" id="database_url_field" placeholder="https://..." value="<?php echo $database_URL ?>" required="required" aria-describedby="tagline-description" />
        <p id="tagline-description" class="description">The database should be there.</p>
    <?php
}


function display_user_form_element()
{
    $db_username = get_option( 'db_username_field' );
    ?>
        <input type="text" name="db_username_field" id="db_username_field" value="<?php echo $db_username ?>" required="required" aria-describedby="tagline-description" />
        <span id="check_username">Checking..</span>
        <p id="tagline-description" class="description">This user has privileges to send data to the machine where the database is located.</p>
    <?php
}


function display_password_form_element()
{
    $db_password = get_option( 'db_password_field' );
    ?>
        <input type="password" name="db_password_field" id="db_password_field" value="<?php echo $db_password ?>" required="required" aria-describedby="tagline-description" />
        <span id="check_password">Checking..</span>
    <?php
}


add_action("admin_menu", "db_add_custom_settings_submenu");
add_action("admin_init", "display_options");




/* For custom submenu - View non-uploaded donation projects . */
function db_add_view_nonuploaded_projects_submenu()
{
    
    add_submenu_page(
        'edit.php?post_type=donationboxes',
        'View non-uploaded Donation Projects',
        'Non-uploaded Donation Projects',
        'administrator',
        'db-non-uploaded-projects-menu',
        'db_non_uploaded_projects_page'
        );

}

function db_non_uploaded_projects_page()
{
    require_once( plugin_dir_path(__FILE__) . 'db-submenu-view_non_uploaded-page.php' );
}


add_action("admin_menu", "db_add_view_nonuploaded_projects_submenu");





/* Load metaboxes : */
require_once( plugin_dir_path(__FILE__) . 'db-metaboxes.php' );




// Add more data to "All donation projects" table-list.



add_filter( 'manage_donationboxes_posts_columns' , 'db_set_donation_projects_list_collumns' );
add_action( 'manage_donationboxes_posts_custom_column', 'db_donation_projects_custom_collum', 10, 2 );



function db_set_donation_projects_list_collumns( $columns )
{
    $newColumns = array();
    
    $newColumns["cb"] = '<input type="checkbox" />';
    $newColumns["title"] = 'Title';
    $newColumns["author"] = 'Author';
    $newColumns["taxonomy-organization"] = 'Organization';
    $newColumns['amount'] = 'Current<b>/</b>Target Amount';
    $newColumns['status'] = 'Status';
    $newColumns["comments"] = '<span class="vers comment-grey-bubble" title="Comments"><span class="screen-reader-text">Comments</span></span>';
    $newColumns["date"] = 'Date';

    return $newColumns;
}



function db_donation_projects_custom_collum( $column , $post_id )
{
    
    switch( $column )
    {
        case 'amount' :
            $current_amount_value = get_post_meta($post_id, '_db_project_current_amount', true);
            $target_amount_value  = get_post_meta($post_id, '_db_project_target_amount' , true);
            
            $percent = intval( ($current_amount_value/$target_amount_value) * 100 );
            
            echo '<span id="current_amount">';
            echo $current_amount_value ; //!= null ? $current_amount_value : '<i class="fa fa-spinner fa-spin"></i>';
            echo '</span> <b> / </b> ' . '<span id="target_amount">' . $target_amount_value . '</span> <br>' ;
            echo '<div class="progress">';
            echo '<div class="progress-bar" role="progressbar" style="width: '.$percent.'%;" aria-valuenow="'.$percent.'" aria-valuemin="0" aria-valuemax="100">'.$percent.'%</div>';
            echo '</div>';
            
            break;
        case 'status':
            $status_value = get_post_meta($post_id, '_db_project_status',true);
            echo $status_value == 1 ? 'Activate' : 'Deactivate';
            break;
    }
    
}





/*
 * > For REST API.
 * 
 * Add REST API support to an already registered post type.
 */


add_action( 'init', 'my_custom_post_type_rest_support', 25 );

function my_custom_post_type_rest_support()
{
    global $wp_post_types;

    $post_type_name = 'donationboxes';
    
    if( isset( $wp_post_types[ $post_type_name ] ) )
    {
        $wp_post_types[$post_type_name]->show_in_rest = true;
        $wp_post_types[$post_type_name]->rest_base = $post_type_name;
        $wp_post_types[$post_type_name]->rest_controller_class = 'WP_REST_Posts_Controller';
    }
}





/*
 * Add REST API support to an already registered taxonomy.
 */

add_action( 'init', 'my_custom_taxonomy_rest_support', 25 );

function my_custom_taxonomy_rest_support()
{
    global $wp_taxonomies;

    $taxonomy_name = 'organization';

    if ( isset( $wp_taxonomies[ $taxonomy_name ] ) ) 
    {
        $wp_taxonomies[ $taxonomy_name ]->show_in_rest = true;
        $wp_taxonomies[ $taxonomy_name ]->rest_base = $taxonomy_name;
        $wp_taxonomies[ $taxonomy_name ]->rest_controller_class = 'WP_REST_Terms_Controller';
    }
}

// Modifying built-in REST API responses.
require_once( plugin_dir_path(__FILE__) . 'db-rest-api-modifying-responses.php' );






