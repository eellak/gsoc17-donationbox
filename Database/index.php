<?php

	$username		= $_POST['username'];
	$password		= $_POST['password'];

	$check			= $_POST['check'];
	$current_amount		= $_POST['current_amount'];
	$delete			= $_POST['delete'];

	$id			= $_POST['id'];
	$title			= $_POST['title'];
	$content		= $_POST['content'];
	$image_url		= $_POST['image_url'];
	$video_url		= $_POST['video_url'];
	$stylesheet_file_url	= $_POST['stylesheet_file_url'];
	$status			= $_POST['status'];
	$organization		= $_POST['organization'];
	$target_amount		= $_POST['target_amount'];

	$start_date		= $_POST['start_date'];
	$end_date		= $_POST['end_date'];
	$wordPress_last_modified = $_POST['last_modified'];


	if ( isset( $username ) )
	{
		if ( $username === 'db_admin' && $password == '123456789' )
		{
			// Receive data for delete a donation project
			if ( isset($_POST['delete']) && $delete == '1' )
			{
				header("HTTP/1.1 460 Invalid user credentials");

				$myfile = fopen("data.log", "a+") or die("Unable to open file!");

				$txt = "The donation project \"<b>" . $id ."</b>\" was deleted successfully from donation box database.\n";
				fwrite($myfile, $txt);

				$txt = "-------------------------------------------------------------------------------------------------\n";
				fwrite($myfile, $txt);

				fclose($myfile);

				echo "The donation project \"<b>" . $id ."</b>\" was deleted successfully from donation box database.";
				exit;
			}

			else if ( isset($_POST['check']) && $check == '1' ) // Check user credentials
			{
				echo '1';
//				echo 'The user credentials are valid.';
				exit;
			}

			else if ( isset($_POST['current_amount']) && $current_amount == '1' ) // Request the total amount that already collected for a donation project
			{ // Here are some examples of donation projects with a specific id.
				if ( $id == '195' )
				{
					echo '1200';
				}

				if ( $id == '108' )
				{
					echo '60';
				}

				exit;
			}


			else // Receive data for Insert/Update
			{
				echo 'I received the data for the donation project "<b>' . $title . '</b>".';

				$myfile = fopen("data.log", "a+") or die("Unable to open file!");

				$txt = $username . "\n";
				fwrite($myfile, $txt);

				$txt = $password . "\n";
				fwrite($myfile, $txt);

				$txt = $id . "\n";
				fwrite($myfile, $txt);

				$txt = $title . "\n";
				fwrite($myfile, $txt);

				$txt = $content . "\n";
				fwrite($myfile, $txt);

				$txt = $image_url . "\n";
				fwrite($myfile, $txt);

				$txt = $video_url . "\n";
				fwrite($myfile, $txt);

				$txt = $stylesheet_file_url . "\n";
				fwrite($myfile, $txt);

				$txt = $status . "\n";
				fwrite($myfile, $txt);

				$txt = $organization . "\n";
				fwrite($myfile, $txt);

				$txt = $target_amount . "\n";
				fwrite($myfile, $txt);

				$txt = $start_date . "\n";
				fwrite($myfile, $txt);

				$txt = $end_date . "\n";
				fwrite($myfile, $txt);

				$txt = $wordPress_last_modified . "\n";
				fwrite($myfile, $txt);

				$txt = "-------------------------------------------------------------------------------------------------\n";
				fwrite($myfile, $txt);


				fclose($myfile);
			}
		}
		else // Client error 4xx
		{
			header("HTTP/1.1 455 Invalid user credentials");
			exit;
		}

	}
	else
		echo 'You did not send any data';

?>
