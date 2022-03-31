<html>

<head>
	<title> Pagination </title>
</head>

<body>
	<?php
	// Directory of uploaded photos
	$upload_dir = "uploads/";

	$db_name = 'php_project';

	//Connecting to the MySQL
	$conn = mysqli_connect('localhost', 'root', '17082015');
	//Check if connection is good
	if (!$conn) {
		die("Connection failed" . mysqli_connect_error());
	} else {
		//Selecting $db_name database
		mysqli_select_db($conn, $db_name);
	}

	// Determine page number currently visited by user
	// if null => set page var it to 1
	if (!isset($_GET['page'])) {
		$page = 1;
	} else { // else set page var to the return results
		$page = $_GET['page'];
	}

	$images_per_page = 4; //max images per page
	$images_at_first_page = ($page - 1) * $images_per_page;

	// Query total nb of pages available
	$query = 'SELECT * FROM `image`';
	$result = mysqli_query($conn, $query);
	$nb_of_images = mysqli_num_rows($result); //Total number of images
	$total_pages = ceil($nb_of_images / $images_per_page); // Total number of pages

	// retrieve images data and display them
	// Get the 1st '$images_at_first_page' to '$images_per_page'
	$query = "SELECT * FROM `image` ORDER BY id DESC LIMIT " . $images_at_first_page . ',' . $images_per_page;
	$result = mysqli_query($conn, $query);

	$i = 0;
	$images_per_raw = 2;

	// Start of table
	echo "<table>";
	echo "<tr>";
	//Display the data retrieved in a table
	while ($row = mysqli_fetch_array($result)) {
		$i = $i + 1;
		echo "<td>";
		echo "<img src=" . '"' . $row['name'] . '" style="height:271px; max-height: 336px; max-width:336px; width: 263px;"' . ">" . '<br>';
		echo $row['origin'] . ' size:' . $row['size'] . '<br>';
		echo "</td>";
		if ($i % $images_per_raw == 0) {
			echo "</tr>";
		}
	}
	// print trailing </tr>
	if ($i % $images_per_raw != 0) {
		echo "</tr>";
	}
	echo "</table>";


	//Displaying hrefs to each page number with prev and nesxt

	if ($page >= 2) {
		echo "<a href='index.php?page=" . ($page - 1) . "'>  Prev </a>";
	}

	$page_url = "";
	for ($i = 1; $i <= $total_pages; $i++) {
		if ($i == $page) {
			$page_url .= "<a href='index.php?page=" . $i . "'>" . $i . " </a>";
		} else {
			$page_url .= "<a href='index.php?page=" . $i . "'>" . $i . " </a>";
		}
	};
	echo $page_url;

	if ($page < $total_pages) {
		echo "<a href='index.php?page=" . ($page + 1) . "'>  Next </a>";
	}

	?>
	<form style="text-align: center; " action="upload.php" method="post" enctype="multipart/form-data">
		Select image to upload:
		<input type="file" name="selected_image" id="selected_image">
		<input type="submit" value="Upload Image" name="submit">
	</form>
</body>

</html>
