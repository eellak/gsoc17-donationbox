<?php

/* Meta boxes. */


wp_enqueue_style('bootstrap-css', plugins_url( '/css/bootstrap.min.css' , __FILE__ ) , 11 );
wp_enqueue_script('bootstrap-js', plugins_url( '/js/bootstrap.min.js', __FILE__ ) , 11 );
wp_enqueue_script('myScripts-js', plugins_url( '/js/db-scripts.js', __FILE__ ) , 11 );




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
            'donationboxes',                // On which page it will appear.
            'side'                          // In which position.
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
                                true);                          // single variable
    
    ?>
    <div class="form-field form-required" >
        <label for="db_project_current_amount_field">Current amount </label>
        <input type="number" name="db_project_current_amount_field" id="db_project_current_amount_field" value="<?php echo ($current_amount_value > 0 ? esc_attr($current_amount_value) : '0') ?>" aria-required="true" required="required" /> <br>
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
            'donationboxes',                // On which page it will appear. 
            'side',                         // In which position.
            'high'                          // Priority.

            );
}

add_action('add_meta_boxes' , 'db_project_target_amount_metabox', 1 );





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
        <input id="db_project_file_field" title="select file" multiple="multiple" name="db_project_file_field[]" size="25" type="file" accept=".css" value="" />
        <?php
    }

    echo '</p>';
                            // Only for text/css!

}

function db_project_style_metabox()
{
    if ( current_user_can('upload_files') ) // Only for users who can upload files - Essentially only the administrator -
    {
        add_meta_box(
                'db_style_metabox',     // Unique id of metabox.
                'Project Style',        // Displayed metabox title. 
                'db_style_callback',    // Callback function.
                'donationboxes',        // On which page it will appear.
                'normal',               // In which position.
                'high'                  // Priority.
                );
    }
}

add_action('add_meta_boxes' , 'db_project_style_metabox' , 1 );





function db_preview_callback()
{
    $preview_page = '/wp-content/plugins/donationBox/templates/template-portrait_mode.php';
    $preview_page .= '?db_preview_id=' . get_the_ID();

    $category_detail = get_the_category( get_the_ID() ); 

    
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
            'donationboxes',        // On which page it will appear.
            'side',                 // In which position.
            'high'                  // Priority.
            );
}

add_action('add_meta_boxes' , 'db_project_preview_metabox' , 1 );






$error = false;

// Save meta boxes data.

function db_save_metaboxes_data( $post_id )
{
    global $error;
    
    // Global basic validations.

    // If it's autosave, DON'T save anything!
    if ( defined('DOING_AUTOSAVE') && DOING_AUTOSAVE )
    {
        return;
    }


    // Validations for stylesheet file.
    if( isset($_POST['db_upload_stylesheet_file_meta_box_nonce']) )
    {
        if( ! wp_verify_nonce( $_POST['db_upload_stylesheet_file_meta_box_nonce'], 'db_save_stylesheet_file' ) )
        {
            return $post_id;  
        }
     
    }

    // For upload file code :
    
    // Make sure the file array isn't empty  
    if( ! empty( $_FILES['db_project_file_field']['name'] ) )
    {
        
        // Get the file type of the upload  
        $flag = 0;
        
        for( $i=0; $i < count( $_FILES['db_project_file_field']['name'] ); $i++ )
        {
            if( !empty($_FILES['db_project_file_field']['name'][$i]) )
            {
                $flag = 1;
                // Use the WordPress API to upload the multiple files
                $upload[] = wp_upload_bits(
                                            $_FILES['db_project_file_field']['name'][$i],
                                            null,
                                            file_get_contents( $_FILES['db_project_file_field']['tmp_name'][$i] )
                                        );  
            }
        }
        if ( $flag == 1 )
        {
            update_post_meta( $post_id, '_db_project_stylesheet_file', $upload);
        }

    }     
    
    
    // For delete css file. Maybe we want more validations here..
    if (  isset( $_POST['remove_css'] ) )
    {
        $theFILE = get_post_meta( $post_id, '_db_project_stylesheet_file', true );
        wp_delete_file( $theFILE[0]['file'] );
        delete_post_meta($post_id, '_db_project_stylesheet_file');
    }
    
    
    // For status:
    if ( ! isset( $_POST['db_status_meta_box_nonce'] ) )
    {
        return;
    }
    
    if ( !wp_verify_nonce( $_POST['db_status_meta_box_nonce'], 'db_save_project_status' ) )
    {
        return;
    }
    
    if ( ! isset( $_POST['db_project_state_field'] ) )
    {
        return;
    }
    
    if ( isset( $_POST['db_project_state_field'] ) )
    {
        $status_data = esc_attr( sanitize_text_field( $_POST['db_project_state_field'] ) ) ;
        $status_data_int = 0;
        
        if ( strcmp($status_data, 'activate') == 0 )
        {
            $status_data_int =  1;  // Save on database number, because is more optimal..
        }
        
        else if ( strcmp($status_data, 'deactivate') == 0 )
        {
            $status_data_int = 0;
        }
        settype($status_data_int, 'integer');
        update_post_meta( $post_id, '_db_project_status', $status_data_int );
    }
    
    
    // For current amount :
    if ( ! isset( $_POST['db_current_amount_meta_box_nonce'] ) )
    {
        return;
    }
    
    if ( !wp_verify_nonce( $_POST['db_current_amount_meta_box_nonce'] , 'db_save_current_amount' ) )
    {
        return;
    }

    if ( ! isset( $_POST['db_project_current_amount_field'] ) )
    {
        return;
    }
    
    if ( isset( $_POST['db_project_current_amount_field'] ) )
    {
        $current_amount_data = esc_attr( sanitize_text_field( $_POST['db_project_current_amount_field'] ) ) ;
        settype($current_amount_data, 'integer');
        update_post_meta( $post_id, '_db_project_current_amount', $current_amount_data );
    }

    
    // For target amount : 
    if ( ! isset( $_POST['db_target_amount_meta_box_nonce'] ) )
    {
        return;
    }
    
    if ( !wp_verify_nonce( $_POST['db_target_amount_meta_box_nonce'] , 'db_save_target_amount' ) )
    {
        return;
    }

    if ( ! isset( $_POST['db_project_target_amount_field'] ) )
    {
        return;
    }
    
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

add_action('save_post' , 'db_save_metaboxes_data');





add_filter( 'redirect_post_location', function( $location, $post_id ) 
{
    global $error;

    if ( $error )
    {
        $location = add_query_arg( 'message', 11, get_edit_post_link( $post_id, 'url' ) );
    }

    return $location;
}, 10, 2 );








// Create my custom admin notice for fail save post!
function sample_admin_notice__error()
{
    if ( $_GET['message'] == 11 )
    {
    $class = 'notice notice-error is-dismissible';
    $message = __( 'Failed saving/updating.', 'sample-text-domain' );

    printf( '<div class="%1$s"><p><b>Failed!</b> %2$s</p></div>', esc_attr( $class ), esc_html( $message ) ); 
    }

}
add_action( 'admin_notices', 'sample_admin_notice__error' );





function get_user_ip()
{
    if ( ! empty( $_SERVER['HTTP_CLIENT_IP'] ) )
    {
        $ip = $_SERVER['HTTP_CLIENT_IP'];
    }
    elseif ( ! empty( $_SERVER['HTTP_X_FORWARDED_FOR'] ) ) 
    {
        $ip = $_SERVER['HTTP_X_FORWARDED_FOR'];
    }
    else
    {
        $ip = $_SERVER['REMOTE_ADDR'];
    }
    return $ip;
}


