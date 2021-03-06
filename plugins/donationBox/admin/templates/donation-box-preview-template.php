<?php
/**
 * The template for displaying donation projects as if they were in the Donation Box
 *
 * @package donationBox
 * @license GPL 3.0
 *
 * Template Name: Portrait Mode -Preview-
 */

    require_once('../../../../../wp-load.php');
    
    $current_post_id = $_GET['db_preview_id'];

    $project_status         = get_post_meta( $current_post_id, '_db_project_status', true) ? 'Activate' : 'Deactivate';
    $project_current_amount = get_post_meta( $current_post_id, '_db_project_current_amount', true);
    $project_target_amount  = get_post_meta( $current_post_id, '_db_project_target_amount', true);
    $project_image          = get_post_meta( $current_post_id, '_db_project_image_file', true);
    $project_video          = get_post_meta( $current_post_id, '_db_project_video_file', true);
    $project_css            = get_post_meta( $current_post_id, '_db_project_stylesheet_file', true );
    $organizations          = get_the_terms( $current_post_id, 'organization' );
    $start_date             = new DateTime( get_post_meta( $current_post_id, '_db_project_start_date', true ) );
    $end_date               = new DateTime( get_post_meta( $current_post_id, '_db_project_end_date', true ) ) ;

    $days_left = $end_date->diff( $start_date )->format("%a");


    if ( count($project_image) > 0  &&  is_array($project_image) )
    {
        $project_image = $project_image[0]['url'];
    }
    else
    {
        $project_image = null;
    }
    
    if ( count($project_video) > 0  &&  is_array($project_video) )
    {
        $project_video = $project_video[0]['url'];
    }
    else
    {
        $project_video = null;
    }
    
    


?>

<!DOCTYPE html>
<html lang="en-US">
    <head>

        <script type="text/javascript"  src="js/config.js">                 </script>
        <script type="text/javascript"  src="js/jquery.min.js">             </script>
        <script type="text/javascript"  src="js/jquery.bpopup.min.js">      </script>
        <script type="text/javascript"  src="js/jquery.plugin.js">          </script>
        <script type="text/javascript"  src="js/jquery.countdown.min.js">   </script>
        <!--<script type="text/javascript"  src="js/db-template.js">            </script>-->

        <!-- ???? o.0
        <script src="/video.js"> </script>
        <link href="/video-js.css"  rel="stylesheet"    type="text/css" />
        -->

        <link href="css/pro-bars.min.css"   rel="stylesheet"    type="text/css" media="all" />
        <link href="css/db-template.css"    rel="stylesheet"    type="text/css" media="all" />
        
        <?php
            if ( count($project_css) > 0  &&  is_array($project_css) )
            {
                echo '<link href="'. $project_css[0]['url'] .'" rel="stylesheet" type="text/css" media="all" />' ;
            }
            else
            {
                echo '<link href="css/style.css" rel="stylesheet" type="text/css" media="all" />';
            }
        ?>

        <meta charset="UTF-8" />
        <!-- <meta name="generator"	content="WordPress 4.6" /> -->
        <meta name="generator"	content="qTranslate-X 3.4.6.8" />
        <meta name="viewport"	content="width=device-width, initial-scale=1, user-scalable=no" />

        <!-- <link rel='dns-prefetch' href='//donationbox3'> -->
        <link rel='dns-prefetch' href='//s.w.org'>


        <title>COOP BOX NETWORK | Donation Box</title>

        <!--[if lt IE 9]>
                <script src="http://donationbox3/wp-content/themes/influence/js/html5.js" type="text/javascript"></script>
        <![endif]-->
        <!--[if (gte IE 6)&(lte IE 8)]>
                <script type="text/javascript" src="http://donationbox3/wp-content/themes/influence/js/selectivizr.js"></script>
        <![endif]-->

    </head>

    <body class="page page-id-86 page-template page-template-templates page-template-template-portrait_mode page-template-templatestemplate-portrait_mode-php logged-in admin-bar no-customize-support custom-background responsive has-main-sidebar">
        <div class="site-header has-shadow site-header-sentinel">
            <div class="container">
                <div class="hgroup">
                    <h1 class="site-title"> Donation Box </h1>
                </div>

                <div role="navigation" class="site-navigation main-navigation primary">
                    <h1 class="assistive-text"> Menu </h1>

                    <a href="#" class="main-menu-button">
                        <i class="influence-icon-menu-icon"></i> Menu
                    </a>
                </div> <!-- .site-navigation .main-navigation -->
            </div>
        </div> <!-- #masthead .site-header -->

        <div id="main" class="site-main">
            <div id="printing_pop_up">
                <div class="popup_content">
                    <div style="font-size:20pt;">
                        Παρακαλώ περιμένετε ενώ <br /> εκτυπώνουμε την απόδειξή σας... <br />
                    </div>

                    <br /> 
                    <br />
                    <br />
                    <br />
                    <br />
                    <br />
                    <br />
                    <br />
                    <br />

                    <div style="background:#70b9aa;color:black;font-size:16px;">
                        Σκανάρε το QR code με το κινητό σου	<br />
                        ή μπες στο <div style="color:white;display:inline;">thecoopbox.commonslab.gr</div> <br />
                        Με τον κωδικό που αναγράφεται		<br />
                        στις αποδείξεις από της δωρεές σου,	<br />
                        μπορείς να συγκεντρώσεις πόντους	<br />
                        για να πάρεις δώρο ένα T-shirt!
                    </div>

                    <div id="loading">
                        <img src="images/loading.gif" />
                    </div>

                </div>
            </div>
            <div id="donation_pop_up">
                <div class="popup_content">
                    <div style="font-size:24pt"> Η ΔΩΡΕΑ ΣΟΥ ΕΙΝΑΙ: </div>
                    <div id="donationvalue_pop" style="display:inline;color:red;font-size:35pt;"> </div>
                    <div id="currency_side" style="display:inline;color:black;font-size:35pt;"> € </div>

                    <br />

                    <div style="font-size:18pt;color:#333;text-align: left;">
                        > Αν θέλεις αύξησε τη δωρεά σου.
                        <br />
                        > Πάτησε το κουμπί για να καταχωρηθεί.
                        <br />
                    </div>
                    <!-- > Πάρε την απόδειξη σου. -->
                </div>
                <!--
                <div style="background:#70b9aa;color:black;font-size:16px;">
                    Μην ξεχάσεις να μπεις στο <br />
                    <div style="color:white;"> thecoopbox.commonslab.gr </div>
                    Με τον κωδικό που αναγράφεται		<br />
                    στις αποδείξεις από της δωρεές σου,	<br />
                    μπορείς να συγκεντρώσεις πόντους	<br />
                    για να πάρεις δώρο ένα T-shirt!
                </div> -->

                <div style="text-align:center">
                        ΕΥΧΑΡΙΣΤΟΥΜΕ <br />
                        ΓΙΑ ΤΗ ΣΤΗΡΙΞΗ
                </div>
            </div>
        </div>

        <div id="thankyou_pop_up">
            <span class="button b-close"> X </span>
            <div class="popup_content">
                <br />
                <b>THANK YOU</b>
                <br />
                for your donation!
            </div>
        </div>

        <div id="registration_success_pop_up">
            <span class="button b-close"> X </span>
            <div class="popup_content">
                <br />
                <b> THANK YOU </b> for registering
                <br />
                to our newsletter.
            </div>
        </div>

        <div id="registration_error_pop_up">
            <span class="button b-close"> X </span>
            <div class="popup_content">
                <br />
                Please enter a valid
                <br />
                e-mail address.
            </div>
        </div>

        <div id="primary" class="content-area">
            <div id="content" class="site-content" role="main">
                <article id="post-86" class="entry post-86 page type-page status-publish hentry">
                    <div class="post-text">

                        <h1 class="entry-title"> COOP BOX NETWORK </h1>

                        <div class="entry-content">
                            <p>
                                <?php
                                    if ( $project_video )
                                    {
                                        echo '<video id="projectvideo" poster="CF-Video-still.png" autoplay="autoplay" loop="loop" muted="" width="100%" height="150">';
                                            echo '<source src=" ' . $project_video . ' " type="video/mp4" />';
                                        echo '</video>';
                                    }
                                ?>
                            </p>

                            <h2 style="padding: 10px; text-align: center;"> ΒΑΛΕ ΚΕΡΜΑ ΚΑΙ ΕΝΙΣΧΥΣΕ ΤΟ COOPBOX NETWORK </h2>

                            <p>
                                Το κοινοτικό δίκτυο οικονομικής ενίσχυσης για την Kοινωνική Aλληλέγγυα Oικονομία (ΚΑΛΟ) είναι ένα έργο που έχει σχεδιαστεί από την ΚΟΙΝΣΕΠ commonslab με έδρα το Ηράκλειο Κρήτης και έχει σαν στόχο την εγκατάσταση 60 συσκευών co-op box σε χώρους συνεργατικών εγχειρημάτων σε όλη την Ελλάδα.
                            </p>
                            <p>
                                Με τις δωρεές σας θα μας βοηθήσετε να κατασκευάσουμε άλλο ένα co-op box.
                            </p>

                        </div> <!-- .entry-content -->
                    </div>
                </article> <!-- #post-86 -->

                <!-- Progress Bar BEGIN -->

                <script src="/jquery-ui.min.js"></script>

                <div id="instructions">
                    <!--
                    <div class="instruction_line">
                        <img src="/wp-content/themes/influence-child/red_dot.png">
                        Εάν θέλεις να ενισχύσεις το έργο που προβάλλεται τώρα στην οθόνη βάλε το κέρμα στον κερματοδέκτη και πάρε την απόδειξη.
                    </div>
                    -->
                    <!--
                    <div class="instruction_line">
                        <img src="/wp-content/themes/influence-child/red_dot.png">
                        Εάν θέλεις να ενισχύσεις το έργο που προβάλλεται και να παίξεις το παιχνίδι, βάλε κέρμα στον κερματοδέκτη.
                    </div>
                    -->
                    <div class="instruction_line">
                        <img src="images/red_dot.png">
                        Εάν θέλεις να ενισχύσεις το έργο που προβάλλεται, βάλε κέρμα στον κερματοδέκτη.
                    </div>

                    <div class="instruction_line">
                        <img src="images/red_dot.png">
                        Ενημερώσου και για άλλα έργα που μπορείς να ενισχύσεις κουνώντας το μοχλό δεξιά ή αριστερά.
                    </div>
                    <!--
                    <div class="instruction_line">
                        <img src="/wp-content/themes/influence-child/red_dot.png">
                        Με τον κωδικό που αναγράφεται στην απόδειξη μάζεψε πόντους στο http://thecoopbox.commonslab.gr και πάρε ένα T-shirt δώρο με συνολικές δωρεές 30€.
                    </div>
                    -->
                </div>

                <div class="info">
                    <div class="info-title"> ΠΑΡΑΚΟΛΟΥΘΗΣΕ ΤΗΝ ΠΟΡΕΙΑ ΤΟΥ ΕΡΓΟΥ </div>
                    <div class="pro-bar-container color-nephritis">
                        <div id="probar" class="pro-bar bar-90 color-emerald" data-pro-bar-percent="0" data-pro-bar-delay="100" > </div>
                    </div>
                    <div id="percentvalue"> </div>
                    <!-- Progress Bar END -->
                    <!--Total amount raised BEGIN-->
                    <div class="infobox">
                        <div class="info-widget">
                            ΔΩΡΕΕΣ ΜΕΧΡΙ ΣΗΜΕΡΑ
                            <div id="totalamount">
                                <?= $project_current_amount; ?>
                            </div>

                            <div class="currency"> &euro; </div>
                            <!--
                            <div id="currency">
                                &nbsp;ΔΩΡΕΕΣ ΑΠΟ 
                            </div>
                            <strong></strong>
                            -->
                        </div>
                        <!--Total amount raised END-->

                        <!-- Days Left BEGIN -->
                        <div id="daysleftwidget" class="info-widget">
                            ΗΜΕΡΕΣ ΠΟΥ ΑΠΟΜΕΝΟΥΝ
                            <div id="daysleft">
                                <?= $days_left ?>
                            </div>
                        </div>	<!--daysleft-->

                        <div id="time">

                        </div>

                        <div id="goal">
                            500
                        </div>
                        <!-- Days Left END -->

                        <div id="donationwidget" class="info-widget">
                            <div style="width:250px;">
                                Η ΔΙΚΗ ΣΟΥ ΔΩΡΕΑ
                            </div>
                            <div id="donationvalue_side">
                                0
                            </div>
                            <div class="currency"> &euro; </div>
                        </div>

                    </div> <!-- #infobox -->
                </div> <!-- #info -->
            </div> <!-- #content .site-content -->
        </div> <!-- #primary -->
        <!-- #primary .content-area -->

        <!--
        <footer id="colophon" class="site-footer" role="contentinfo">
            <div class="container">
                <div id="footer-widgets">
                    <aside id="text-6" class="widget widget_text">
                        <div class="textwidget" style="font-size: 16pt;padding-top: 25px;">
                            <img src="/wp-content/uploads/2017/04/red_dot.png">
                            &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Εισάγετε κέρμα ή επιλέξτε άλλο έργο κουνώντας το μοχλό δεξιά ή αριστερά...
                        </div>
                    </aside>
                </div>
            </div>
        </footer>
        -->
    </body>
</html>








