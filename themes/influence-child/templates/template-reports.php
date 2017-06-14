<?php
/**
 * The template for displaying Donation Box reports
 *
 * @package influence-child
 * @license GPL 3.0
 *
 * Template Name: Reports
 */

get_header(); 
?>
<?php
//CLEAR EMAILS
$servername = "localhost";
$username = "wpuser";
$password = "dbc0mm0ns";
$dbname = "donationbox";
$conn = mysqli_connect($servername, $username, $password);

if (!$conn) {
  echo mysqli_connect_error();
  //die("Connection failed: . mysqli_connect_error());
} else {
  //echo "Connected";
}

if ( isset($_GET['emails']) ) {
//echo "Newsletters cleared";
$sql = "TRUNCATE TABLE donationbox.newsletter";
mysqli_query($conn, $sql);
$conn->close;
}
if ( isset($_GET['donations']) ) {
//echo "Donations cleared";
$sql = "TRUNCATE TABLE donationbox.donations";
mysqli_query($conn, $sql);
$conn->close;
}

?>
<style>
html, button, html input[type="button"],input[type="submit"] {
cursor: pointer !important;
}

</style>
<div id="primary" class="content-area">

	<div id="content" class="site-content" role="main">

			
        <article id="post-64" class="entry post-64 page type-page status-publish hentry">
            <div class="post-text">

                <h1 class="entry-title">Newsletter Registration Report</h1>

				<div class="entry-content">
		<?php 
        global $wpdb;
$results = $wpdb->get_results( 'SELECT * FROM newsletter', OBJECT );
echo "<table><tr><td>Emails</td></tr>";
foreach ( $results as $result ) 
{
        echo "<tr><td>";
	echo $result->email;
        echo "</td></tr>";
}
        ?>
<tr><td><form action='?emails=true' method='post'>
<input type="submit" name="submit" value="Clear Emails">
</form></td></tr>
<tr><td><form action='?donations=true' method='post'>
<input type="submit" name="submit" value="Clear Donations">
</form></td></tr></table>
                </div>
            </div>
        </article>
	</div><!-- #content .site-content -->

</div><!-- #primary .content-area -->

<?php //get_sidebar() ?>

<?php get_footer(); ?>
