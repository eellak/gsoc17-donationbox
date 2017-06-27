<?php

/* Meta boxes. */


wp_enqueue_style('bootstrap-css', plugins_url( '/css/bootstrap.min.css' , __FILE__ ) , 11 );
wp_enqueue_script('bootstrap-js', plugins_url( '/js/bootstrap.min.js', __FILE__ ) , 11 );
wp_enqueue_script('myScripts-js', plugins_url( '/js/db-scripts.js', __FILE__ ) , 11 );
require_once('db-validations.php');



/* Project status meta box. */
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
            'donationboxes',                // On which page it will appear. σε ποια σελίδα να εφμανιστεί
            'side'                          // In which position. που να εμφανιστεί.
            );
}

add_action('add_meta_boxes' , 'db_project_status_metabox' , 1 );





/* Target amount meta box. */

function db_target_amount_callback( $post )
{
    // For current amount :
    wp_nonce_field( 'db_save_current_amount', 'db_current_amount_meta_box_nonce');
    $current_amount_value = get_post_meta( $post->ID , '_db_project_current_amount', true);
    
    // For target amount :
    wp_nonce_field(
            'db_save_target_amount',            // action
            'db_target_amount_meta_box_nonce'   // name
            );
    $target_amount_value = get_post_meta(
                                $post->ID,                      // post id
                                '_db_project_target_amount',    // unique id for database -- NEED to start with "_" -- 
                                true);                          // single - είναι μια απλή τιμή ή κάποιο array ή κάποια άλλη περίπλοκη δομή.. ;
    
    ?>
    <div class="form-field form-required" >
        <label for="db_project_current_amount_field">Current amount </label>
        <input type="text" disabled="disabled" name="db_project_current_amount_field" id="db_project_current_amount_field" value="<?php echo ($current_amount_value > 0 ? esc_attr($current_amount_value) : '0') ?>" aria-required="true" required="required" /> <br>
    </div>
    <div class="form-field form-required" >
        <label for="db_project_target_amount_field">Target amount </label>
        <input type="number" name="db_project_target_amount_field" id="db_project_target_amount_field" value="<?php echo esc_attr($target_amount_value) ?>"  aria-required="true" required="required" /> <br>
    </div>
    <?php
    
}

function db_project_target_amount_metabox()
{
    add_meta_box(
            'db_amount_metabox',            // Unique id of metabox.
            'Donation money',               // Displayed metabox title.
            'db_target_amount_callback',    // Callback function.
            'donationboxes',                // On which page it will appear. σε ποια σελίδα να εφμανιστεί
            'side',                         // In which position. που να εμφανιστεί.
            'high'                          // Priority. ύψος - προταιρεώτητα σε σχέση με τα υπόλοιπα.

            );
}

add_action('add_meta_boxes' , 'db_project_target_amount_metabox', 1 );





function db_preview_callback( $post )
{
    $preview_page = '/wp-content/plugins/donationBox/templates/template-portrait_mode.php';
    $preview_page .= '?db_preview_id=' . get_the_ID();

    $category_detail = get_the_category( get_the_ID() ); 
    
    global $pagenow;
    echo get_post_status( $post->ID );
    
    global $db_error;
    echo '<br> error : ';
    echo $db_error['have'] == false ? 'false' : 'true';
    echo '<br>';
    var_dump($db_error);
    
    ?>
    <p>
        <button type="submit" class="btn btn-primary" id="db_preview_button" name="<?php echo get_the_ID() ?> ">
            <span class="glyphicon glyphicon-eye-open"></span> Donation Box Preview
        </button>
    </p>
    <?php
}

function db_project_preview_metabox()
{
    add_meta_box(
            'db_preview_metabox',   // Unique id of metabox.
            'Project Preview',      // Displayed metabox title. 
            'db_preview_callback',  // Callback function.
            'donationboxes',        // On which page it will appear. σε ποια σελίδα να εφμανιστεί
            'side',                 // In which position. που να εμφανιστεί.
            'high'                  // Priority. για να φαίνεται ψηλά
            );
}

add_action('add_meta_boxes' , 'db_project_preview_metabox' , 1 );





// update_edit_form  : Very important function for uploading a file.
add_action('post_edit_form_tag', 'update_edit_form');

function update_edit_form()
{
  echo 'enctype="multipart/form-data"';
}


/* Upload style sheet file metaboxe. */
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
                'db_style_metabox',     // Unique id of metabox.
                'Project Style',        // Displayed metabox title. 
                'db_style_callback',    // Callback function.
                'donationboxes',        // On which page it will appear. σε ποια σελίδα να εφμανιστεί
                'normal',               // In which position. που να εμφανιστεί.
                'high'                  // Priority. για να φαίνεται ψηλά
                );
    }
}

add_action('add_meta_boxes' , 'db_project_style_metabox' , 1 );





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
        'db_video_metabox',     // Unique id of metabox.
        'Project Video',        // Displayed metabox title. 
        'db_video_callback',    // Callback function.
        'donationboxes',        // On which page it will appear. σε ποια σελίδα να εφμανιστεί
        'normal',               // In which position. που να εμφανιστεί.
        'high'                  // Priority. για να φαίνεται ψηλά
        );

}

add_action('add_meta_boxes', 'db_project_video');






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
        <input id="db_project_image_field" title="select file" name="db_project_image_field" size="25" type="file" accept="image/jpeg" value="" />
        <?php
    }

    echo '</p>';
}

function db_project_image()
{
    add_meta_box(
        'db_image_metabox',     // Unique id of metabox.
        'Project Image',        // Displayed metabox title. 
        'db_image_callback',    // Callback function.
        'donationboxes',        // On which page it will appear. σε ποια σελίδα να εφμανιστεί
        'normal',               // In which position. που να εμφανιστεί.
        'high'                  // Priority. για να φαίνεται ψηλά
        );

}

add_action('add_meta_boxes', 'db_project_image');





// Save meta boxes data.

function db_save_metaboxes_data( $post_id )
{
    global $db_error;
    
    // Global basic validations.

    // If it's autosave, DON'T save anything!
    if ( defined('DOING_AUTOSAVE') && DOING_AUTOSAVE )
    {
        return;
    }
    
    // User can edit post ?
//    if ( ! current_user_can('edit_post' , $post_id ) )
//    {
//        return;
//    }
//
//    if  ( isset($_POST['post_type']) )
//    {
//        
//        if( $_POST['post_type'] == 'donationboxes' ) // Αν προέρχεται από την σελίδα 'donationboxes'
//        {  
//            if( ! current_user_can('edit_page', $post_id) )
//            {  
//                return;
//            }
//        }
//        else // Αν δε προέρχεται από την σελίδα 'donationboxes'
//        {
//            $db_error = true;
////            $message  = 'You must so becurefull with your next steps, because you haven\'t access to this page. Our eyes are upon you!';
////            $message .= '<br>My friend <b>';
////            $message .= get_user_ip();
////            $message .= '</b> :) <br>';
////            $message .= $_SERVER['HTTP_USER_AGENT'];
////            wp_die( $message , "You haven't access.");
//            return;
//        }  
//    }
    
    
    // Validations for stylesheet file.
    if ( db_css_file_validations() )
    {
        // Upload css file :
        // Make sure the file array isn't empty  
        if( ! empty( $_FILES['db_project_css_file_field']['name'] ) )
        {
            // Get the file type of the upload  
            $flag = 0;

            if( !empty($_FILES['db_project_css_file_field']['name']) )
            {
                $flag = 1;
                // Use the WordPress API to upload the multiple files
                $upload[] = wp_upload_bits(
                                            $_FILES['db_project_css_file_field']['name'],
                                            null,
                                            file_get_contents( $_FILES['db_project_css_file_field']['tmp_name'] )
                                        );
            }

            if ( $flag == 1 )
            {
                update_post_meta( $post_id, '_db_project_stylesheet_file', $upload);
            }
        }
    }
    
    
    // For delete css file. Maybe it's necessary to canmore validations here..
    if (  isset( $_POST['remove_css'] ) )
    {
        $theFILE = get_post_meta( $post_id, '_db_project_stylesheet_file', true );
        wp_delete_file( $theFILE[0]['file'] );
        delete_post_meta($post_id, '_db_project_stylesheet_file');
    }
    
    
//  Validations for video file.
    if ( db_video_file_validations() )
    {
        // Upload video file :
        if( ! empty( $_FILES['db_project_video_field']['name'] ) )
        {
            // Get the file type of the upload
            $flag = 0;

            if( !empty($_FILES['db_project_video_field']['name']) )
            {
                $flag = 1;
                // Use the WordPress API to upload the multiple files
                $upload[] = wp_upload_bits(
                                            $_FILES['db_project_video_field']['name'],
                                            null,
                                            file_get_contents( $_FILES['db_project_video_field']['tmp_name'] )
                                        );
            }

            if ( $flag == 1 )
            {
                update_post_meta( $post_id, '_db_project_video_file', $upload);
            }
        }
    }
    
    // For delete video file.
    if (  isset( $_POST['remove_video'] ) )
    {
        $theFILE = get_post_meta( $post_id, '_db_project_video_file', true );
        wp_delete_file( $theFILE[0]['file'] );
        delete_post_meta($post_id, '_db_project_video_file');
    }
    
    
//  Validations for image file.
    if ( db_image_file_validations() )
    {
        // Upload image file :
        if( ! empty( $_FILES['db_project_image_field']['name'] ) )
        {
            // Get the file type of the upload
            $flag = 0;

            if( !empty($_FILES['db_project_image_field']['name']) )
            {
                $flag = 1;
                // Use the WordPress API to upload the multiple files
                $upload[] = wp_upload_bits(
                                            $_FILES['db_project_image_field']['name'],
                                            null,
                                            file_get_contents( $_FILES['db_project_image_field']['tmp_name'] )
                                        );
            }

            if ( $flag == 1 )
            {
                update_post_meta( $post_id, '_db_project_image_file', $upload);
            }
        }
    }
    
    // For delete image file.
    if (  isset( $_POST['remove_image'] ) )
    {
        $theFILE = get_post_meta( $post_id, '_db_project_image_file', true );
        wp_delete_file( $theFILE[0]['file'] );
        delete_post_meta($post_id, '_db_project_image_file');
    }
    
        
    // Validations for status:
    if ( db_status_validations() )
    {
        // Πλέον.. ΟΚ, ΑΝ έχει βάλει δεδομένα σε αυτό το field..
        if ( isset( $_POST['db_project_state_field'] ) )
        {
            $status_data = esc_attr( sanitize_text_field( $_POST['db_project_state_field'] ) ) ;
            $status_data_int = 0;

            if ( strcmp($status_data, 'activate') == 0 )
            {
                $status_data_int =  1;  // Θα αποθηκεύω αριθμούς στη βάση δεδομένων. Λόγο του ότι μπορεί να υπάρχουν & περισσότερο των 2 καταστάσεων και είναι πιο βέλτιστο έτσι
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
    // Θα μπαίνει από προεπιλογή η τιμή 0. Όλα τα νέα έργα θα αρχίζουν με 0 αρχικό ποσό.
    // Και ο χρήστης δε θα μπορεί ΠΟΤΕ να αλλάξει αυτή την τιμή.
    if ( get_post_status( $post_id) == 'auto-draft' || get_post_status( $post_id ) == 'draft' )
    {
        update_post_meta( $post_id, '_db_project_current_amount', 0 );
    }
    

    // Validations for target amount :
    if ( db_target_amount_validations() )
    {
        // Πλέον.. ΟΚ, ΑΝ έχει βάλει δεδομένα σε αυτό το field..
        if ( isset( $_POST['db_project_target_amount_field'] ) )
        {
            $target_amount_data = esc_attr( sanitize_text_field( $_POST['db_project_target_amount_field'] ) ) ;
            settype($target_amount_data, 'integer'); // For more more more secure!!!
            update_post_meta(
                    $post_id,                       // post_id
                    '_db_project_target_amount',    // meta_key
                    $target_amount_data             // meta_value που θέλω να αποθηκεύσω
                    );
        }
    }

}

add_action('save_post' , 'db_save_metaboxes_data');






//add_filter( 'post_updated_messages', function( $messages ) 
//{
//    //create another message code, i.e 11
//    $messages['post'] = $messages['post'] + array( 11 => __( 'Something Wrong', 'textdomain' ) );
//
//    return $messages;
//}
//);


add_filter( 'redirect_post_location', function( $location, $post_id ) 
{
    //let say the conditon is false, or you can create your code here
//    $condition = false;
    global $db_error;
    var_dump($db_error);
//    wp_die();

    if ( $db_error['have'] ) //add 11 as code message or use in the list post messages
    {
        $location = add_query_arg( 'message', 11, get_edit_post_link( $post_id, 'url' ) );
//        add_action( 'admin_notices', 'sample_admin_notice__error' );

    }


    return $location;
}, 10, 2 );








// Create me own admin notice for fail save post!
function sample_admin_notice__error()
{
    global $db_error;
    
    if ( $_GET['message'] == 11 )
    {
    $class = 'notice notice-error is-dismissible';
//    $temp_m = 'Failed saving/updating.<br>';
//    $temp_m .= $db_error['message'];
    
    echo 'eeee--->';
    echo $db_error['have'] ? 'true' : 'false';
    echo '<br>';
    var_dump($db_error);
    echo db_error_message();

    $message = __( 'Failed saving/updating.' , 'sample-text-domain' );

    printf( '<div class="%1$s"><p><b>Failed!</b> %2$s<br>' . db_error_message() . '</p></div>', esc_attr( $class ), esc_html( $message ) );
    $db_error['have'] = false;
    $db_error['message'] = 'PAI AUTO';
    }

}
add_action( 'admin_notices', 'sample_admin_notice__error' );








