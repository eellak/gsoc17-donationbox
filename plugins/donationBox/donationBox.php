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
License: GNU GENERAL PUBLIC LICENSE
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



/* --------------------- DonationBox Plugin Menu ----------------------------- */
/* WordPress Menus API. */

function add_new_menu_and_submenu_items()
{
    add_menu_page(
        "Donation Boxes Manage Page",   //Required. - Page title - Text in browser title bar when the page associated with this menu item is displayed.
        "Donation Boxes",               //Required. Text to be displayed in the menu.
        "edit_posts",                   //Required. The required capability of users to access this menu item.
        "db-settings-menu",             //Required. A unique identifier to identify this menu item.
        "db_settings_page",             //Optional. This callback outputs the content of the page associated with this menu item.
        "dashicons-feedback",           //Optional. The URL to the menu item icon.
        25                              //Optional. Position of the menu item in the menu.
    );

 
    add_submenu_page(
            'db-settings-menu', // Parrent menu Slug.
            'Donation Boxes',   // Page title.
            'General',          // The side bar Menu title.
            'edit_posts',       // Capability
            'db-settings-menu', // menu_slug
            'db_settings_page'  // function
            );    
    
    add_submenu_page(
            'db-settings-menu',           	// Parrent Slug
            'Create new donation project', 	// Page title
            'Create project',               // Menu_title when be show at the menu side bar
            'edit_posts',                 	// capability
            'db-create-project-submenu',   	// menu_slug
            'db_create_project'            	// function
            );

}


function db_settings_page()
{
    require_once( plugin_dir_path(__FILE__) . 'admin/db_settings_page.php' );
}

function db_create_project()
{
    require_once( plugin_dir_path(__FILE__) . 'admin/db_create_project.php' );
}


//this action callback is triggered when wordpress is ready to add new items to menu.
add_action("admin_menu", "add_new_menu_and_submenu_items");





/* ---------------------------- End menus ------------------------------------ */
/* WordPress Settings API. */

function display_options()
{
    
    // For "General" submenu :

    add_settings_section(
            "general_section",                   /* The unique name of section */
            "General Settings",                  /* The display name of section */
            "display_header_options_content",	 /* Function */
            "db-settings-menu");                 /* page to which section is attached. */

    
    add_settings_field(
            "database_url_field",			/* The unique setting ID name. */
            "Database Url",                 /* The display name of field. */
            "display_logo_form_element",    /* Function. */
            "db-settings-menu",             /* Page in which field is displayed. */
            "general_section");             /* Unique name of section. */
    
    
    add_settings_field(
            "advertising_code",
            "Ads Code",
            "display_ads_form_element",
            "db-settings-menu",
            "general_section");

    //section name, form element name, callback for sanitization
    register_setting("general_section", "database_url_field");
    register_setting("general_section", "advertising_code");
    
    
    //------------------------------- For "Create Project" submenu . -----------------------------------------
    add_settings_section(
            'create_project_section',           // Section unique id.
            'Project Options',                 	// Section title.
            'display_create_header_section',    // Callback function.
            'db-create-project-submenu'         // Page to show.
            );
    
    add_settings_field(
            'db_project_name_field',        // Field id
            'Project name',                 // Field title
            'display_project_name_field',   // Field function
            'db-create-project-submenu',    // Page to show
            'create_project_section'        // Section to show
            );

    add_settings_field(
        'db_project_description_field',         // Field id
        'Description',                          // Field title
        'display_project_description_field',    // Field function
        'db-create-project-submenu',            // Page to show
        'create_project_section'                // Section to show
        );

    add_settings_field(
        'db_project_content_field',         // Field id
        'Content',                          // Field title
        'display_project_content_field',    // Field function
        'db-create-project-submenu',        // Page to show
        'create_project_section'            // Section to show
        );
    
    add_settings_field(
        'db_project_state_field',       // Field id
        'State',                        // Field title
        'display_project_state_field',  // Field function
        'db-create-project-submenu',    // Page to show
        'create_project_section'        // Section to show
        );
    
    add_settings_field(
        'db_project_amount_field',      // Field id
        'Financing target',             // Field title
        'display_project_amount_field', // Field function
        'db-create-project-submenu',    // Page to show
        'create_project_section'        // Section to show
        );
    
    add_settings_field(
        'db_project_organization_field',        // Field id
        'Οrganization',                         // Field title
        'display_project_organization_field',   // Field function
        'db-create-project-submenu',            // Page to show
        'create_project_section'                // Section to show
        );
        
    add_settings_field(
        'db_project_styling_field',         // Field id
        'File for styling',                 // Field title
        'display_project_styling_field',    // Field function
        'db-create-project-submenu',        // Page to show
        'create_project_section'            // Section to show
        );


    register_setting("create_project_section", "db_project_name_field");
    register_setting("create_project_section", "db_project_description_field");
    register_setting("create_project_section", "db_project_content_field");
    register_setting("create_project_section", "db_project_state_field");
    register_setting("create_project_section", "db_project_amount_field");
    register_setting("create_project_section", "db_project_organization_field");
    register_setting("create_project_section", "db_project_styling_field");



    
}

/* --------- For General section ----------- */

function display_header_options_content()
{
     echo "The following settings apply to all donation boxes.";
}


function display_logo_form_element()
{

    ?>
        <input type="text" name="database_url_field" id="database_url_field" placeholder="https://..." value="<?php echo get_option('database_url_field'); ?>" required="required" aria-describedby="tagline-description" />
        <p id="tagline-description" class="description">The database should be there.</p>
    <?php
}
function display_ads_form_element()
{

    ?>
        <input type="text" name="advertising_code" id="advertising_code" value="<?php echo get_option('advertising_code'); ?>" />
    <?php
}


/* --------- For New project section ----------- */

function display_create_header_section()
{
//    echo 'Create a new donation project.';
}

function display_project_name_field()
{
    ?>
        <input type="text" name="db_project_name_field" id="db_project_name_field" value="<?php echo get_option('db_project_name_field'); ?>" required="required" />
    <?php
}


function display_project_description_field()
{
    ?>
        <input type="text" name="db_project_description_field" id="db_project_description_field" value="<?php echo get_option('db_project_description_field'); ?>" placeholder="A sort description.." />
    <?php
}


function display_project_content_field()
{
    ?>
        <textarea name="db_project_content_field" id="db_project_content_field" value="<?php echo get_option('db_project_content_field'); ?>" required="required" ></textarea>
        <!--<textarea id="content" class="wp-editor-area" name="content" cols="40" autocomplete="off" style="height: 322px; margin-top: 37px; " ></textarea>-->
    <?php
}

function display_project_state_field()
{
    ?>
        Active :     <input type="radio" name="db_project_state_field" id="db_project_state_field" value="<?php echo get_option('db_project_state_field'); ?>" />
        Deactivate : <input type="radio" name="db_project_state_field" id="db_project_state_field" value="<?php echo get_option('db_project_state_field'); ?>" />
    <?php
}

function display_project_amount_field()
{
    ?>
        <input type="number" name="db_project_amount_field" id="db_project_amount_field" value="<?php echo get_option('db_project_amount_field'); ?>"  />
    <?php
}

function display_project_organization_field()
{
    //..
}

function display_project_styling_field()
{
    //..
}



add_action("admin_init", "display_options");













