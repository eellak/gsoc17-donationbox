<div class="wrap">

    <h1><?= esc_html( get_admin_page_title() ); ?></h1>

    <?php
    settings_errors();
    wp_clear_scheduled_hook( "db_cron_job_332", array( 332 ) );
    ?>
    
    <form method="post" action="options.php">
        <?php
        echo '<pre>';
        print_r( _get_cron_array() );
        echo '</pre>';
        
        $all_cron_jobs = _get_cron_array();
        
        foreach ($all_cron_jobs as $cron_job) 
        {
//            foreach ($cron_job as $value) 
//            {
//                echo var_dump($cron_job);
                print_r( $cron_job );
//                echo key($cron_job);
                echo '<br>-------------------------<br>';
//            }
        }
        ?>         
    </form>
</div>
