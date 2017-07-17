<div class="wrap">

    <h1><?= esc_html( get_admin_page_title() ); ?></h1>

    <?php

    settings_errors();
   
    ?>
    
    <form method="post" action="options.php">
        <?php
        
        $all_cron_jobs = _get_cron_array();
        
        foreach ($all_cron_jobs as $cron_job) 
        {
            if ( $cron_job['db_cron_hook'] )
            {
                foreach ($cron_job as $value) 
                {
                    foreach ($value as $temp ) 
                    {	//	  post id		  ,					title of the post					   , next time we will try to communicate with the database
                        echo $temp['args'][0] . ' - ' . get_the_title($temp['args'][0]) . ' - time : ' . $temp['args'][1] . '<br>';
                    }
                }
            }
        }
        
        echo '<pre>';
        print_r( _get_cron_array() );
        echo '</pre>';

        ?>         
    </form>
</div>
