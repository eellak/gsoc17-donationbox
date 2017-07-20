<?php require_once('db-List-Table.php'); ?>

<div class="wrap">

    <h1><?= esc_html( get_admin_page_title() ); ?></h1>
    On this page, you see all the actions done on donation projects, and it was not possible to update the donation boxes database.

    <?php

        echo '<h4> Current time : <em>' . date('Y/m/d H:i:s', time() ) . '</em> ' . date_default_timezone_get() . '</h4>' ;

        $all_cron_jobs = _get_cron_array();

        $myListTable = new db_List_Table( $all_cron_jobs );
        $myListTable->prepare_items(); 
        $myListTable->display();
        
        $message = 'Overall are queued for sending to database, <span class="badge">' . $myListTable->get_total_items() . '</span> donation projects.';
        echo '<p id="tagline-description" class="description"> ' . $message .' </p>';

    ?>

</div>


