<?php
/**
 * The template for displaying Donation Box reports
 *
 * @package influence-child
 * @license GPL 3.0
 *
 * Template Name: Main Page
 */

get_header(); 
?>

<div id="primary" class="content-area">

	<div id="content" class="site-content" role="main"> 
		<?php while ( have_posts() ) : the_post(); ?>

<video poster="CF-Video-still.png" id="bgvid" width="100%" playsinline autoplay muted loop>
<source src="/Video.mp4" type="video/mp4">
</video>        

<!--Total amount raised BEGIN-->
<!--<div class="totalamountwidget">ΣΥΝΟΛΙΚΟ ΠΟΣΟ ΔΩΡΕΩΝ:  -->
<div class="totalamountwidget">TOTAL DONATED AMOUNT: 
    <div id="totalamount" >
<?php 
    $conn = new mysqli("localhost", "root", "c0mm0ns", "donationbox");
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    } 
    setlocale(LC_MONETARY, 'el_GR');
    //$pid = get_the_ID();
    $sql = "SELECT SUM(Ammount) FROM donations WHERE ProjectID!=-1";
    $result = $conn->query($sql);
    if ($result->num_rows > 0) 
    {
        while($row = $result->fetch_assoc()) 
        {
            $sum = $row['SUM(Ammount)'];
            print money_format('%.2n', $sum);
        }
    } else 
    {
        print "0";
    }
    $conn->close();
?>
</div><strong>&euro;</strong>
<div style="display:block;">NEW DONATION: <div id="donationvalue_side">0</div><strong style="">&euro;</strong></div>
</div>

			<?php get_template_part( 'content', 'page' ); ?>

		<?php endwhile; // end of the loop. ?>
	</div><!-- #content .site-content -->

</div><!-- #primary .content-area -->

<?php// get_sidebar() ?>
<?php get_footer(); ?>

