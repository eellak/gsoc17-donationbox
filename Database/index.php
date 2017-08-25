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

	$db_server 	= "localhost";
	$db_user	= "db_admin";
	$db_pass	= "123456789";
	$db_name	= "donationbox"


	// Create database connection
	$conn = new mysqli($db_server, $db_user, $db_pass, $db_name);

	// Check connection
	if ($conn->connect_error)
	{
		die("Connection failed: " . $conn->connect_error);
	}


	if ( isset( $username ) )
	{
		if ( $username === $db_user && $password == $db_pass )
		{
			// Receive data for delete a donation project
			if ( isset($_POST['delete']) && $delete == '1' )
			{
				header("HTTP/1.1 460 Invalid user credentials");

				// SQL to delete a record
				$sql = "DELETE FROM project WHERE ProjectID=$id";
				//TODO: Check database to see if other rows in other tables need to be
				// removed or updated in case of a project deletion.
				if ($conn->query($sql) === TRUE)
				{
					echo "The donation project \"<b>" . $id ."</b>\" was deleted successfully from donation box database.";
				}
				else
				{
					echo "Error deleting record: " . $conn->error;
				}

				$conn->close();
				exit;
			}

			else if ( isset($_POST['check']) && $check == '1' ) // Check user credentials
			{
				echo '1'; // echo 'The user credentials are valid.';
				$conn->close();
				exit;
			}

			else if ( isset($_POST['current_amount']) && $current_amount == '1' ) // Request the total amount that already collected for a donation project
			{ // Here are some examples of donation projects with a specific id.

				//setlocale(LC_MONETARY, 'el_GR');
				$sql = "SELECT SUM(Ammount) FROM donation WHERE idproject=$id";
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

				$conn->close();
				exit;
			}

			else // Receive data for Insert/Update
			{
				$sql = "SELECT ProjectID FROM Project WHERE ProjectID=$id";
				if ($conn->query($sql)->num_rows > 0)
				{
					$sql = "INSERT INTO Project (idProject, Goal, Title, Description, Video, FeaturedImage, idOrganization, DateFinish, idProjectStatus)
					VALUES ($id, $target_amount, '$title', '$content', '$video_url', '$image_url', $organization, date($end_date), $status )";

					//TODO: Decide what to do with the following data:
					// Database: ShortDescription
					// WordPress: $stylesheet_file_url
					// WordPress: $start_date
					// WordPress: $wordPress_last_modified

					if ($conn->query($sql) === TRUE)
					{
						echo 'I received the data for the <b>new</b> donation project "<b>' . $title . '</b>".';
					}
					else
					{
						echo "Error inserted record: " . $conn->error;
					}
				}
				else
				{
					$sql = "UPDATE Project
					SET Goal=$target_amount, Title='$title', Description='$content', Video='$video_url', FeaturedImage='$image_url', idOrganization=$organization, DateFinish=date($end_date), idProjectStatus=$status
					WHERE ProjectID=$id";

					//TODO: Decide what to do with the following data:
					// Database: ShortDescription
					// WordPress: $stylesheet_file_url
					// WordPress: $start_date
					// WordPress: $wordPress_last_modified

					if ($conn->query($sql) === TRUE)
					{
						echo 'I received the data for the donation project "<b>' . $title . '</b>".';
					}
					else
					{
						echo "Error updating record: " . $conn->error;
					}
				}

				$conn->close();

			}
		}
		else // Client error 4xx
		{
			header("HTTP/1.1 455 Invalid user credentials");
			$conn->close();
			exit;
		}

	}
	else
		$conn->close();
		echo 'You did not send any data';

?>
