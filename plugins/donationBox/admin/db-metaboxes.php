<?php

/* Metaboxes. */

// Bootstrap CSS :
wp_enqueue_style('bootstrap-css', plugins_url( '/css/bootstrap.min.css' , __FILE__ ) , 11  );





/* Project status metaboxe. */
function db_project_status_callback( $post )
{
    wp_nonce_field( 'db_save_project_status', 'db_status_meta_box_nonce');    
    $status_value = get_post_meta( $post->ID  , '_db_project_status',true);
    
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
            'donationboxes',                // Page.
            'side'                          // Position.
            );
}


add_action('add_meta_boxes' , 'db_project_status_metabox' , 1 );





/* Target amount metaboxe. */

function db_project_target_amount_metabox()
{
    add_meta_box(
            'db_amount_metabox',            // Unique id of metabox.
            'Donation money',               // Displayed metabox title.
            'db_target_amount_callback',    // Callback function.
            'donationboxes',                // Page.
            'side',                         // Position.
            'high'                          // Priority.

            );
}


add_action('add_meta_boxes' , 'db_project_target_amount_metabox', 1 );


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
                                true);                          // single
    

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

    
    echo esc_url( admin_url('options.php') );
    
    $html = '<p class="description">';
    
    
    if ( count($theFILE) > 0  &&  is_array($theFILE) )
    {
        $html .= "Current stylesheet file URL : " . $theFILE[0]['url'];
  
        $html .=
        '<form>'
        . '<input type="hidden" name="action" value="wpse_79898">'
        . '<button class="btn btn-danger btn-sm" name="remove_btn" id="remove_btn">
              <span class="glyphicon glyphicon-remove" aria-hidden="true"></span>
              Remove
            </button>
        </form>';

    }
    else
    {
        $html .= 'If you want, upload a stylesheet (css) file. <br>';
    }
    
    $html .= '</p>';
    
    
    $html .= '<input id="db_project_file_field" title="select file" multiple="multiple" name="db_project_file_field[]" size="25" type="file" value="" />';  
// text/css
    echo $html; 
    
}


function db_project_style_metabox()
{
    add_meta_box(
            'db_style_metabox',     // Unique id of metabox.
            'Project Style',        // Displayed metabox title. 
            'db_style_callback',    // Callback function.
            'donationboxes',        // Page.
            'normal',               // Position.
            'high'                  // Priority.
            );
}

add_action('add_meta_boxes' , 'db_project_style_metabox' , 1 );









// Save metaboxes data.

add_action('save_post' , 'db_save_metaboxes_data');


function db_save_metaboxes_data( $post_id )
{
    
    /* For file security! */  
    if( isset($_POST['db_upload_stylesheet_file_meta_box_nonce']) )
    {
        if( ! wp_verify_nonce( $_POST['db_upload_stylesheet_file_meta_box_nonce'], 'db_save_stylesheet_file' ) )
        {
            return $post_id;  
        }
    }
    
    if( defined('DOING_AUTOSAVE') && DOING_AUTOSAVE ) 
    {  
        return $post_id;  
    }
    
    
    
    
    if  ( isset($_POST['post_type']) ) // Αυτός ελέγχει αν και το post, είναι τύπου post_type!! Ωραίος! ;)
    {
        
        if( 'page' == $_POST['post_type'] ) // Και εδώ αν και το η σελίδα είναι η αναμενόμενη που θα έπρεπε να είναι!! 
        {  
            if( !current_user_can('edit_page', $post_id) )
            {  
               return $post_id;  
            }
        }
        else
        {  
            if ( !current_user_can('edit_page', $post_id) )
            {  
                return $post_id;  
            } 
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
    
    
    
    
    /* ---------------------------------------------------------------------- */

    
    // For status:
    if ( ! isset( $_POST['db_status_meta_box_nonce'] ) )
    {
        return;
    }
    
    if ( !wp_verify_nonce( $_POST['db_status_meta_box_nonce'] , 'db_save_project_status' ) )
    {
        return;
    }
    
    // If it's autosave, DON'T save anything!
    if ( defined('DOING_AUTOSAVE') && DOING_AUTOSAVE )
    {
        return;
    }
    
    // User can edit post ?
    if ( ! current_user_can('edit_post' , $post_id ) )
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
        echo 'eeeee--> ' . $status_data;
        $status_data_int = 0;
        if ( strcmp($status_data, 'activate') == 0 )
        {
            $status_data_int =  1;
        }
        if ( strcmp($status_data, 'deactivate') == 0 )
        {
            $status_data_int = 0;
        }
        settype($status_data_int, 'integer');
        update_post_meta( $post_id, '_db_project_status', $status_data_int );
    }
    
    /* ---------------------------------------------------------------------- */
    
    // For current amount :
    if ( ! isset( $_POST['db_current_amount_meta_box_nonce'] ) )
    {
        return;
    }
    
    if ( !wp_verify_nonce( $_POST['db_current_amount_meta_box_nonce'] , 'db_save_current_amount' ) )
    {
        return;
    }
    
    // If it's autosave, DON'T save anything!
    if ( defined('DOING_AUTOSAVE') && DOING_AUTOSAVE )
    {
        return;
    }
    
    // User can edit post ?
    if ( ! current_user_can('edit_post' , $post_id ) )
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
    
    
    /* ----------------------------------------------------------------------*/
    
    
    // For target amount : 

    if ( ! isset( $_POST['db_target_amount_meta_box_nonce'] ) )
    {
        return;
    }
    
    if ( !wp_verify_nonce( $_POST['db_target_amount_meta_box_nonce'] , 'db_save_target_amount' ) )
    {
        return;
    }
    
    // If it's autosave, DON'T save anything!
    if ( defined('DOING_AUTOSAVE') && DOING_AUTOSAVE )
    {
        return;
    }
    
    // User can edit post ?
    if ( ! current_user_can('edit_post' , $post_id ) )
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



