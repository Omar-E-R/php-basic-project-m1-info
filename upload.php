<?php
	$target_dir = "uploads/";
	$target_image = $_FILES["selected_image"]["name"];
	$image_size = $_FILES['selected_image']['size'];
	$image_type = strtolower(pathinfo($target_image, PATHINFO_EXTENSION));
	// Switch variable used to check if there is an error
	$upload_ok = 1;
	$max_image_size = 3000000;//Byte => 3MB
	if ($image_size > $max_image_size ) {
		echo "This image is too large. (Max size permitted is ". $max_image_size . "Kb)";
		echo '<br/>';
		$upload_ok = 0;
	}
	if ($image_type != "jpg" && $image_type != "png" && $image_type != "jpeg"
		&& $image_type != "gif") {
		echo "Oops! only JPG, JPEG, PNG & GIF files are allowed.";
		echo '<br/>';
		$upload_ok = 0;
	}

	if (isset($target_image)) {
	// Check if $upload_ok is set to 0 by an error
		if ($upload_ok == 0) {
			echo "Error, your image was not uploaded.";

		} else { // if everything is ok, try to upload file
			$conn = mysqli_connect('localhost', 'root', '17082015');
			mysqli_select_db($conn, 'pagination');
			$query = 'SELECT * FROM `image`';
			$result = mysqli_query($conn, $query);
			$nb_of_images = mysqli_num_rows($result) + 1;
			$new_image_name =  'image'. $nb_of_images .'.'. $image_type;
			if (move_uploaded_file($_FILES["selected_image"]["tmp_name"], $target_dir . $new_image_name)) {
				$query = "INSERT INTO `image` (`origin`, `type`, `size`, `name`)
				VALUES ('$target_image', '$image_type', '$image_size', '$new_image_name');";

				if (mysqli_query($conn, $query)) {
					echo "New image recorded to the DB successfully <br/>";
				} else {
					echo "Error: " . $query . "<br/>";
				}
				echo "The image " . htmlspecialchars( $target_image ). " has been uploaded to '" . $target_dir. "'<br/>" ;

			} else {
				echo "Oops! Error uploading your image.<br/>";
			}
		}
	}
?>
