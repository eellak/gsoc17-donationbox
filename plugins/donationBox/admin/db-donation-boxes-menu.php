<?php

/* Create new submenu to add new Donation project - with register post type */
function db_register_new_post_type()
{
    $capabilities = array(
        'read'                      => true,
        'edit_posts'                => true,
        'edit_others_posts'         => true,
        'edit_private_posts'        => true,
        'edit_published_posts'      => true,
        'delete_posts'              => true,
        'delete_others_posts'       => true,
        'delete_published_posts'    => true,
        'publish_posts'             => true,
        'upload_files'              => true,
                    );

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
//		'capability_type'    => 'project_creator',
		'capabilities'       => array('post' , 'manage_categories'),
		'map_meta_cap'       => true,
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
                );
    
    
    register_taxonomy('organization', 'donationboxes', $args);
}


add_action( 'init' , 'db_register_organization_taxonomy' );






/* For custom submenu. */
function db_add_custom_submenu()
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
            "display_header_options_content",    /* Section. */
            "db-settings-menu");                 /* Page to which section is attached. */

    add_settings_field(
            "database_url_field",        /* The unique setting ID name. */
            "Database Url",              /* The display name of field. */
            "display_logo_form_element", /* Callback Function. */
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
    // id and name of form element should be same as the setting name.
    $database_URL = esc_url( get_option( 'database_url_field' ) ) ;
    ?>
        
        <input type="text" name="database_url_field" id="database_url_field" placeholder="https://..." value="<?php echo $database_URL ?>" required="required" aria-describedby="tagline-description" />
        <p id="tagline-description" class="description">The database should be there.</p>
    <?php
}


add_action("admin_menu", "db_add_custom_submenu");
add_action("admin_init", "display_options");


/* Load metaboxes : */
require_once( plugin_dir_path(__FILE__) . 'db-metaboxes.php' );




// Add more data to "All donation projects"  list.



//add_filter( 'manage_yourcustomposttype_posts_columns' );
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


// Αυτή η μέθοδος είναι μια loop για κάθε γραμμή.
function db_donation_projects_custom_collum( $column , $post_id )
{
    
    switch( $column )
    {
        case 'amount' :
            $c_amount_value = get_post_meta($post_id, '_db_project_current_amount', true);
            $t_amount_value = get_post_meta($post_id, '_db_project_target_amount' , true);
            echo $c_amount_value . '<b>/</b>' . $t_amount_value;
            break;
        case 'status':
            $status_value = get_post_meta($post_id, '_db_project_status',true);
            echo $status_value == 1 ? 'Activate' : 'Deactivate';
            break;
    }
    
}



