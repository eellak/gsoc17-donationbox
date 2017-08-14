<?php

/* 
 * In this source file, all meta - boxes are created.
 * 
 */


wp_enqueue_style('bootstrap-css', plugins_url( '/css/bootstrap.min.css' , __FILE__ ) , 11 );
wp_enqueue_script('bootstrap-js', plugins_url( '/js/bootstrap.min.js', __FILE__ ) , 11 );
wp_enqueue_script('myScripts-js', plugins_url( '/js/db-scripts.js', __FILE__ ) , 11 );

wp_enqueue_script('jquery-ui-core');
wp_enqueue_script('jquery-ui-datepicker');
wp_enqueue_style('jquery-ui-css', 'http://ajax.googleapis.com/ajax/libs/jqueryui/1.8.2/themes/smoothness/jquery-ui.css');

require_once('db-validations.php');
require_once('db-send_data_to_db.php');
require_once('db-functions.php');






/**
 * Project status meta box.
 * This functions creates the panel it contains the fields concerning the status
 * of the donation project.
 * 
 */

function db_project_status_callback( $post )
{
    wp_nonce_field( 'db_save_project_status', 'db_status_meta_box_nonce');    
    $status_value = get_post_meta( $post->ID, '_db_project_status', true);
    
    ?>
        <label for=db_project_state_field_a"">Activate  </label>
        <input type="radio" name="db_project_state_field" id="db_project_state_field_a" value="activate"    <?php echo $status_value == 1 ? 'checked="checked"' : '' ?> /> <br>

        <label for=db_project_state_field_d"">Deactivate  </label>
        <input type="radio" name="db_project_state_field" id="db_project_state_field_d" value="deactivate"  <?php echo $status_value == 0 ? 'checked="checked"' : '' ?> />
    <?php
}


function db_project_status_metabox()
{
    add_meta_box(
            'db_project_status_metabox',    // Unique id of metabox.
            'Project Status',               // Displayed metabox title.
            'db_project_status_callback',   // Callback function.
            'donationboxes',                // On which page it will appear.
            'side'                          // In which position.
            );
}

add_action('add_meta_boxes', 'db_project_status_metabox' , 1 );





/**
 * Target amount meta box.
 * This functions creates the panel it contains the fields concerning the target
 * amount of the donation project.
 * 
 */

function db_target_amount_callback( $post )
{
    // For current amount :
    wp_nonce_field( 'db_save_current_amount', 'db_current_amount_meta_box_nonce');
    $current_amount_value = get_post_meta( $post->ID , '_db_project_current_amount', true);
    
    // For target amount :
    wp_nonce_field(
            'db_save_target_amount',
            'db_target_amount_meta_box_nonce'
            );
    $target_amount_value = get_post_meta(
                                $post->ID,
                                '_db_project_target_amount',
                                true);
    
    ?>
    <div class="form-field form-required" >
        <label for="db_project_current_amount_field">Current amount </label>
        <input type="text" disabled="disabled" name="db_project_current_amount_field" id="db_project_current_amount_field" value="<?php echo ($current_amount_value > 0 ? esc_attr($current_amount_value) : '0') ?>" aria-required="true" required="required" /> <br>
    </div>
    <div class="form-field form-required" >
        <label for="db_project_target_amount_field">Target amount </label>
        <input type="number" name="db_project_target_amount_field" id="db_project_target_amount_field" value="<?php echo esc_attr($target_amount_value) ?>"  aria-required="true" required="required" min="1" /> <br>
    </div>
    <?php
    
}


function db_project_target_amount_metabox()
{
    add_meta_box(
            'db_amount_metabox',
            'Donation money',
            'db_target_amount_callback',
            'donationboxes',
            'side',
            'high'
            );
}


add_action('add_meta_boxes', 'db_project_target_amount_metabox', 1 );





/**
 * About Project meta box.
 * This functions create the panel it contains the start & end project datepicker
 * fields, and the preview button with which the user can preview the current
 * donation project just as it will appear in the donation box.
 * 
 */

function db_about_callback( $post )
{
    wp_nonce_field( 'db_save_start_project_date', 'db_start_date_meta_box_nonce');
    $start_date_value = get_post_meta( $post->ID , '_db_project_start_date', true);
    
    wp_nonce_field( 'db_save_end_project_date', 'db_end_date_meta_box_nonce');
    $end_date_value = get_post_meta( $post->ID , '_db_project_end_date', true);
    
    
    $preview_page = '/wp-content/plugins/donationBox/templates/template-portrait_mode.php';
    $preview_page .= '?db_preview_id=' . get_the_ID();
    
    ?>
    <p>
        <label for="db_start_datepicker_field">Start date :</label>
        <input type="date" id="db_start_datepicker_field" name="db_start_datepicker_field" value="<?php echo !empty($start_date_value) ? esc_attr($start_date_value) : '' ?>" class="datepicker" required="required" />
        
        <label for="db_end_datepicker_field">End date:</label>
        <input type="date" id="db_end_datepicker_field" name="db_end_datepicker_field" value="<?php echo !empty($end_date_value) ? esc_attr($end_date_value) : '' ?>" class="datepicker" required="required" />

        <center>
            <button type="submit" class="btn btn-primary" id="db_preview_button" name="<?php echo get_the_ID() ?> ">
                <span class="glyphicon glyphicon-eye-open"></span> Donation Box Preview
            </button>
        </center>
    </p>
    <?php

}


function db_project_about_metabox()
{
    add_meta_box(
            'db_about_metabox',
            'About Project',
            'db_about_callback',
            'donationboxes',
            'side',
            'high'
            );
}


add_action('add_meta_boxes', 'db_project_about_metabox' , 1 );






/**
 * Upload style sheet file metaboxe.
 * This functions creates the panel it contain the css upload file field with
 * which the user can upload a stylesheet (.css) file.
 * 
 */

function db_style_callback( $post )
{
    wp_nonce_field( 'db_save_stylesheet_file', 'db_upload_stylesheet_file_meta_box_nonce');
    $theFILE = get_post_meta( $post->ID , '_db_project_stylesheet_file', true );

    echo '<p id="current_css_file" class="description">';

    if ( count($theFILE) > 0  &&  is_array($theFILE) )
    {
        ?>
        Current stylesheet file URL : <?php echo $theFILE[0]['url'] ?>
        <a href="#" title="Remove" id="rm_css" onClick="return false;"><span class="glyphicon glyphicon-remove text-danger" aria-hidden="true">   </span> </a>
        <?php
    }
    else
    {
        ?>
        If you want, upload a stylesheet (css) file.
        <br>
        <input id="db_project_css_file_field" title="select file" name="db_project_css_file_field" size="25" type="file" accept="text/css" value="" />
        <?php
    }

    echo '</p>';

}


function db_project_style_metabox()
{
    if ( current_user_can('upload_files') ) // Only for users who can upload files - Essentially only the administrator -
    {
        add_meta_box(
                'db_style_metabox',
                'Project Style',
                'db_style_callback',
                'donationboxes',
                'normal',
                'high'
                );
    }
}


add_action('add_meta_boxes', 'db_project_style_metabox' , 1 );





/**
 * Upload video file metaboxe.
 * This functions creates the panel it contain the video upload file field with
 * which the user can upload a video (.mp4) file.
 * 
 */

function db_video_callback( $post )
{
    wp_nonce_field( 'db_save_video_file', 'db_upload_video_file_meta_box_nonce');
    $theFILE = get_post_meta( $post->ID , '_db_project_video_file', true );

    echo '<p id="current_video_file" class="description">';

    if ( count($theFILE) > 0  &&  is_array($theFILE) )
    {
        ?>
        Current video file URL : <?php echo $theFILE[0]['url'] ?>
        <a href="#" title="Remove" id="rm_video" onClick="return false;"><span class="glyphicon glyphicon-remove text-danger" aria-hidden="true">   </span> </a>
        <?php
    }
    else
    {
        ?>
        If you want, upload a video file.
        <br>
        <input id="db_project_video_field" title="select file" name="db_project_video_field" size="25" type="file" accept="video/mp4" />
        <?php
    }

    echo '</p>';
}


function db_project_video()
{
    add_meta_box(
            'db_video_metabox',
            'Project Video',
            'db_video_callback',
            'donationboxes',
            'normal',
            'high'
            );

}


add_action('add_meta_boxes', 'db_project_video');





/**
 * Upload image file metaboxe.
 * This functions creates the panel it contain the image upload file field with
 * which the user can upload a image (.jpg or .png) file.
 * 
 */

function db_image_callback( $post )
{
    wp_nonce_field( 'db_save_image_file', 'db_upload_image_file_meta_box_nonce');
    $theFILE = get_post_meta( $post->ID , '_db_project_image_file', true );

    echo '<p id="current_image_file" class="description">';

    if ( count($theFILE) > 0  &&  is_array($theFILE) )
    {
        ?>
        Current image file URL : <?php echo $theFILE[0]['url'] ?>
        <a href="#" title="Remove" id="rm_image" onClick="return false;"><span class="glyphicon glyphicon-remove text-danger" aria-hidden="true">   </span> </a>
        <?php
    }
    else
    {
        ?>
        If you want, upload a image file.
        <br>
        <input id="db_project_image_field" title="select file" name="db_project_image_field" size="25" type="file" accept="image/jpeg, image/png" value="" />
        <?php
    }

    echo '</p>';
}


function db_project_image()
{
    add_meta_box(
            'db_image_metabox',
            'Project Image',
            'db_image_callback',
            'donationboxes',
            'normal',
            'high'
            );

}


add_action('add_meta_boxes', 'db_project_image');






/**
 * update_edit_form
 * Very important function for uploading a file.
 * 
 */

add_action('post_edit_form_tag', 'update_edit_form');

function update_edit_form()
{
  echo 'enctype="multipart/form-data"';
}




/**
 * Save meta boxes data.
 * This function is responsible for storing all data assigned to the custom
 * metaboxes. Executed when it Publish/Update a post.
 * 
 */

function db_save_metaboxes_data( $post_id )
{
    // Validations for stylesheet file.
    if ( db_css_file_validations() )
    {
        if( ! empty( $_FILES['db_project_css_file_field']['name'] ) )
        {
            $flag = 0;

            if( !empty($_FILES['db_project_css_file_field']['name']) )
            {
                $flag = 1;
                $upload[] = wp_upload_bits(
                                            $_FILES['db_project_css_file_field']['name'],
                                            null,
                                            file_get_contents( $_FILES['db_project_css_file_field']['tmp_name'] )
                                        );
            }

            if ( $flag == 1 )
            {
                update_post_meta( $post_id, '_db_project_stylesheet_file', $upload);
                unset($upload);
            }
        }
    }
    
    
    if (  isset( $_POST['remove_css'] ) )
    {
        db_delete_css_file($post_id);
    }
    
    
    //  Validations for video file.
    if ( db_video_file_validations() )
    {
        if( ! empty( $_FILES['db_project_video_field']['name'] ) )
        {
            $flag = 0;

            if( !empty($_FILES['db_project_video_field']['name']) )
            {
                $flag = 1;
                $upload[] = wp_upload_bits(
                                            $_FILES['db_project_video_field']['name'],
                                            null,
                                            file_get_contents( $_FILES['db_project_video_field']['tmp_name'] )
                                        );
            }

            if ( $flag == 1 )
            {
                update_post_meta( $post_id, '_db_project_video_file', $upload);
                unset($upload);
            }
        }
    }
    
    // For delete video file.
    if (  isset( $_POST['remove_video'] ) )
    {
        db_delete_video_file($post_id);
    }
    
    
    //  Validations for image file.
    if ( db_image_file_validations() )
    {
        if( ! empty( $_FILES['db_project_image_field']['name'] ) )
        {
            $flag = 0;

            if( !empty($_FILES['db_project_image_field']['name']) )
            {
                $flag = 1;
                $upload[] = wp_upload_bits(
                                            $_FILES['db_project_image_field']['name'],
                                            null,
                                            file_get_contents( $_FILES['db_project_image_field']['tmp_name'] )
                                        );
            }

            if ( $flag == 1 )
            {
                update_post_meta( $post_id, '_db_project_image_file', $upload);
                unset($upload);
            }
        }
    }
    
    // For delete image file.
    if (  isset( $_POST['remove_image'] ) )
    {
        db_delete_image_file($post_id);
    }
    
    
    // Validations for status:
    if ( db_status_validations() )
    {
        if ( isset( $_POST['db_project_state_field'] ) )
        {
            $status_data = esc_attr( sanitize_text_field( $_POST['db_project_state_field'] ) ) ;
            $status_data_int = 0;

            if ( strcmp($status_data, 'activate') == 0 )
            {
                $status_data_int =  1;
            }

            else if ( strcmp($status_data, 'deactivate') == 0 )
            {
                $status_data_int = 0;
            }
            settype($status_data_int, 'integer');
            update_post_meta( $post_id, '_db_project_status', $status_data_int );
        }
    }
    
    // For current amount :
    // The value 0 is set by default. All new projects will begin with 0 initial amount.
    // The user will NEVER be able to change this value.
    if ( get_post_status( $post_id) == 'auto-draft' || get_post_status( $post_id ) == 'draft' )
    {
        update_post_meta( $post_id, '_db_project_current_amount', 0 );
    }
    

    // Validations for target amount :
    if ( db_target_amount_validations() )
    {
        if ( isset( $_POST['db_project_target_amount_field'] ) )
        {
            $target_amount_data = esc_attr( sanitize_text_field( $_POST['db_project_target_amount_field'] ) ) ;
            settype($target_amount_data, 'integer');
            update_post_meta(
                    $post_id,
                    '_db_project_target_amount',
                    $target_amount_data
                    );
        }
    }
    
    
    // Validations for start project date :
    if ( db_start_project_date_validations() )
    {
        if ( isset( $_POST['db_start_datepicker_field'] ) )
        {
            $start_date_data = esc_attr(sanitize_text_field( $_POST['db_start_datepicker_field'] ) );
            update_post_meta($post_id, '_db_project_start_date', $start_date_data);            
        }
    }
    
    
    // Validations for end project date :
    if ( db_end_project_date_validations() )
    {
        if ( isset( $_POST['db_end_datepicker_field'] ) )
        {
            $end_date_data = esc_attr(sanitize_text_field( $_POST['db_end_datepicker_field'] ) );
            update_post_meta($post_id, '_db_project_end_date', $end_date_data);            
        }
    }

}


add_action('save_post_donationboxes', 'db_save_metaboxes_data');





/**
 * Redirect function when there are errors.
 * 
 * @global Array $db_error : If I have an error, $db_error['have'] == True
 * and $db_error['message_code'] == 'a message code'.
 * 
 * @global Array $untrashed_posts : testing mode...
 * 
 * @param string $location : The current-destination URL location.
 * @param integer $post_id : The post ID.
 * 
 * @return string $location : The destination URL.
 * 
 */

function db_redirect_post_location( $location, $post_id )
{
    global $db_error;
    global $untrashed_posts;

    if ( $db_error['have'] )
    {
        $location = add_query_arg( 'message', $db_error['message_code'], get_edit_post_link( $post_id, 'url' ) );
    }
    
    if ( $untrashed_posts ) // If i have untrashed posts.
    {
        $location = add_query_arg( 'ids', $untrashed_posts[0], $location );
        var_dump($untrashed_posts);
//        wp_die();
    }
    
    return $location;
}


add_filter( 'redirect_post_location', 'db_redirect_post_location' , 10, 2 );





/**
 * My own admin notices for fail save post!
 * This function detects for any error ( URL ) message codes and displays the
 * appropriate admin notices.
 * Also, after specific actions when detected from the URL message codes
 * οther background actions are executed.
 * 
 */

function db_admin_notices( $post_id )
{
    global $untrashed_posts;

    switch ( $_GET['message'] )
    {
        case 1: // Update post.
            db_send_data_to_donationBox_database( $_GET['post'] );
            break;
        case 6: // Create new post.
            db_send_data_to_donationBox_database( $_GET['post'] );
            break;
        case 101 : 
            db_print_user_error( '101', 'Problem with css file [UPLOAD_ERR_NO_FILE].' );
            break;
        case 102 : 
            db_print_user_error( '102', 'Problem with css file [UPLOAD_ERR_INI_SIZE] || [UPLOAD_ERR_FORM_SIZE].' );
            break;
        case 103 : 
            db_print_user_error( '103', 'Problem with css file [Something went wrong].' );
            break;
        case 201 : 
            db_print_user_error( '201', 'Problem with video file [UPLOAD_ERR_NO_FILE].' );
            break;
        case 202 : 
            db_print_user_error( '202', 'Problem with video file [UPLOAD_ERR_INI_SIZE] || [UPLOAD_ERR_FORM_SIZE].' );
            break;
        case 203 : 
            db_print_user_error( '203', 'Problem with video file [Something went wrong]. ' );
            break;
        case 301 : 
            db_print_user_error( '301', 'Problem with image file [UPLOAD_ERR_NO_FILE].' );
            break;
        case 302 : 
            db_print_user_error( '302', 'Problem with image file [UPLOAD_ERR_INI_SIZE] || [UPLOAD_ERR_FORM_SIZE].' );
            break;
        case 303 : 
            db_print_user_error( '303', 'Problem with image file [Something went wrong].' );
            break;
        case 401 :
            db_print_user_error( '401', 'Problem with start project date.');
            break;
        case 501 :
            db_print_user_error( '501', 'Problem with end project date.');
            break;
    }

    if ( isset( $_GET['trashed'] ) ) // If he sends donation projects in the trash.
    {
        if ( isset( $_GET['ids'] ) )
        {
            db_delete_data_from_donationBox_database( $_GET['ids'] );
        }
    }
    
    if ( isset( $_GET['untrashed'] ) ) // If he sends donation projects in the trash.
    {
        var_dump($untrashed_posts);

        if ( isset( $_GET['ids'] ) )
        {
            db_print_user_error( 'Untrashed posts is : ', $_GET['ids'] );
        }
    }
        
}

add_action( 'admin_notices', 'db_admin_notices' );

