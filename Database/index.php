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

	$db_server = "localhost";
	$db_user = "dbuser";
	$db_pass = "dbpass";
	$db_name = "donationbox"

	if ( isset( $username ) )
	{
		if ( $username === 'db_admin' && $password == '123456789' )
		{
			// Receive data for delete a donation project
			if ( isset($_POST['delete']) && $delete == '1' )
			{
				header("HTTP/1.1 460 Invalid user credentials");

				// Create connection
				$conn = new mysqli($db_server, $db_user, $db_pass, $db_name);
				// Check connection
				if ($conn->connect_error) {
				    die("Connection failed: " . $conn->connect_error);
				}

				// sql to delete a record
				$sql = "DELETE FROM project WHERE ProjectID=$id";
				//TODO: Check database to see if other rows in other tables need to be
				//removed or updated in case of a project deletion
				if ($conn->query($sql) === TRUE) {
				    echo "Record deleted successfully";
				} else {
				    echo "Error deleting record: " . $conn->error;
				}

				$conn->close();

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
			{
				//$conn = new mysqli(server, user, password, table);
				$conn = new mysqli($db_server, $db_user, $db_pass, $db_name);
				if ($conn->connect_error)
				{
					die("Connection failed: " . $conn->connect_error);
				}
				//setlocale(LC_MONETARY, 'el_GR');
				$sql = "SELECT SUM(Ammount) FROM donations WHERE ProjectID=$id";
				$result = $conn->query($sql);
				if ($result->num_rows > 0)
				{
					while($row = $result->fetch_assoc())
					{
						$sum = $row['SUM(Ammount)'];
						echo money_format('%.2n', $sum);
					}
				}
				else
				{
					//TODO: Print an error message
					echo 0;
				}
				//Closing the database connection
				$conn->close();
				exit;
			}


			else // Receive data for Insert/Update
			{
				echo 'I received the data for the donation project "<b>' . $title . '</b>".';

				// Create connection
				$conn = new mysqli($db_server, $db_user, $db_pass, $db_name);
				// Check connection
				if ($conn->connect_error) {
				    die("Connection failed: " . $conn->connect_error);
				}

				$sql = "SELECT ProjectID FROM Project WHERE ProjectID=$id";

				if ($conn->query($sql)->num_rows > 0) {
				  //TODO: The rest of the fields need to be filled in.
					//Check which fields are not integers, those require ''
					//$sql = "INSERT INTO Project (ProjectID, Goal, Title, ...) VALUES ($id,'$target_amount','$title')";
					if ($conn->query($sql) === TRUE) {
					  echo "Record inserted successfully";
					} else {
					  echo "Error insertedd record: " . $conn->error;
					}
				} else {
			    //TODO: The rest of the fields required in an update need to be filled in.
					//Check which fields are not integers, those require ''
					//$sql = "UPDATE Project SET Goal=$target_amount, Title='$title' ... WHERE ProjectID=$id";
					if ($conn->query($sql) === TRUE) {
					  echo "Record updated successfully";
					} else {
					  echo "Error updating record: " . $conn->error;
					}
				}

				$conn->close();
				/*
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
				/**/
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
