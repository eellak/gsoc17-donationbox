<?php
    require ('../../../../wp-load.php');
    
    $current_post_id = $_GET['db_preview_id'];
    
    $project_status = get_post_meta( $current_post_id , '_db_project_status', true) ? 'Activate' : 'Deactivate';
    $project_current_amount = get_post_meta( $current_post_id , '_db_project_current_amount', true);
    $project_target_amount  = get_post_meta( $current_post_id , '_db_project_target_amount', true);
    
    
?>
<!DOCTYPE html>
<html>
    <head>
        <meta charset="utf-8">
        <title>Preview project</title>

    </head>
    <body>

        <div id="primary" class="content-area">

                <div id="content" class="site-content" role="main">
                        <?php // while ( have_posts() ) : the_post(); ?>
                <?php // get_template_part( 'content', 'page' ); ?>
                        <?php // endwhile; // end of the loop. ?>
                <!-- Progress Bar BEGIN -->
                <script src="/jquery-ui.min.js"></script>
                <div id="instructions">
                    I have the post with id : <?php echo $current_post_id ?> <br>
                    Project status : <?php echo $project_status ?> <br>
                    Current mount : <?php echo $project_current_amount ?> <br>
                    Target mount : <?php echo $project_target_amount ?> <br>
                    Title : <?php echo get_the_title( $current_post_id ) ?>
                    
                            <div class="instruction_line"><img src="/wp-content/themes/influence-child/red_dot.png"> Εάν θέλεις να ενισχύσεις το έργο που προβάλλεται και να παίξεις το παιχνίδι, βάλε κέρμα στον κερματοδέκτη.</div>
                    <div class="instruction_line"><img src="/wp-content/themes/influence-child/red_dot.png"> Ενημερώσου και για άλλα έργα που μπορείς να ενισχύσεις κουνώντας το μοχλό δεξιά ή αριστερά.</div>
                    <div class="instruction_line"><img src="/wp-content/themes/influence-child/red_dot.png"> Με τον κωδικό που αναγράφεται στην απόδειξη μάζεψε πόντους στο http://thecoopbox.commonslab.gr και πάρε ένα T-shirt δώρο με συνολικές δωρεές 30€.</div>
                </div>

                <div class="info">
                    <div class="info-title">ΠΑΡΑΚΟΛΟΥΘΗΣΕ ΤΗΝ ΠΟΡΕΙΑ ΤΟΥ ΕΡΓΟΥ</div>
                    <div class="pro-bar-container color-nephritis">
                        <div id="probar" class="pro-bar bar-90 color-emerald" data-pro-bar-percent="0" data-pro-bar-delay="100" ></div>
                    </div>
                    <div id="percentvalue"></div>
                    <!-- Progress Bar END -->
                    <!--Total amount raised BEGIN-->
                    <div class="infobox">
                    <div class="info-widget">
                        ΔΩΡΕΕΣ ΜΕΧΡΙ ΣΗΜΕΡΑ
                        <div id="totalamount" >
        <?php
//            $conn = new mysqli("localhost", "dbuser", "c0mm0nsDB", "donationbox");
//            if ($conn->connect_error)
//            {
//                die("Connection failed: " . $conn->connect_error);
//            }
//            setlocale(LC_MONETARY, 'el_GR');
//            $pid = get_the_ID();
//            $sql = "SELECT SUM(Ammount) FROM donations WHERE ProjectID=$pid";
//            $result = $conn->query($sql);
//            if ($result->num_rows > 0)
//            {
//                while($row = $result->fetch_assoc())
//                {
//                    $sum = $row['SUM(Ammount)'];
//                    print money_format('%.2n', $sum);
//                }
//            } else
//            {
//                print "0";
//            }
//            $conn->close();
        ?>
                        </div><div class="currency">&euro;</div>
                        <!--
                        <div id="currency">&nbsp;ΔΩΡΕΕΣ ΑΠΟ </div>
                        <strong></strong>-->
                   </div>
                    <!--Total amount raised END-->
        <!-- Days Left BEGIN -->
                    <div id="daysleftwidget" class="info-widget">ΗΜΕΡΕΣ ΠΟΥ ΑΠΟΜΕΝΟΥΝ
                        <div id="daysleft">
        <?php
//            //$days = get_post_custom_values( 'Finish Date' ); //custom fields
//            $days = get_field('finish_date'); //Plug in: Advanced custom fields
//            if ($days[0] > 0)
//            {
//                $now = time(); // or your date as well
//                $date = strtotime($days);
//                $datediff = $date-$now;
//                if ($datediff < 0) {
//                    echo '0';
//                } else {
//                    //echo 'μένουν&nbsp;';
//                    echo floor($datediff/(60*60*24));
//                    //echo '&nbsp;ημέρες';
//                }
//            }
        ?>
                        </div>
                    </div><!--daysleft-->
                    <div id="time"></div>
                    <div id="goal"><?php //$goal = get_post_custom_values( 'Goal' ); print $goal[0];
//                                                echo get_field('goal');?></div>
                    <!-- Days Left END -->
                    <div id="donationwidget" class="info-widget">
                        <div style="width:250px;">Η ΔΙΚΗ ΣΟΥ ΔΩΡΕΑ</div>
                        <div id="donationvalue_side">0</div>
                        <div class="currency">&euro;</div>
                    </div>
                </div><!-- #infobox -->
                </div><!-- #info -->
                </div><!-- #content .site-content -->
        </div><!-- #primary .content-area -->

        <script type='text/javascript' src='../admin/js/jquery-3.2.1.min.js'></script>
        <script type='text/javascript' src='../admin/js/db-preview.js'></script>
    </body>
</html>