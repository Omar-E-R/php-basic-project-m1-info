
<P>
<B>DEBUTTTTTT DU PROCESSUS :</B>
<BR>
<?php echo " ", date ("h:i:s"); ?>
</P>
<?php

// Limits the maximum execution time to 500sec
set_time_limit (500);
$path= "docs";

echo "Directory: " . $path . "<br>" . "<br>";

//Execute the function on docs folder
explorerDir($path);

function explorerDir($path)
{
	// Open current folder and handle it to $folder
	$folder = opendir($path);

	// Looping on all files in current $folder and saving them as entries
	while($entree = readdir($folder))
	{
		// Execlude "." and ".." folders from loop
		if($entree != "." && $entree != "..")
		{
			// In case current entry is a directory
			if(is_dir($path."/".$entree))
			{
				// Save current path in sav_path
				$sav_path = $path;
				// Append the current entry (directory) to current path
				$path .= "/".$entree;
				// Execute recursively the function on the new directory
				explorerDir($path);

				echo "<br>" . "Directory: " . $path . "<br>" . "<br>";

				// Save the current path back in $path
				$path = $sav_path;


			}
			else
			{
				// if entry is a file we append the entry to path in $path_source
				$path_source = $path."/".$entree;
				// Get file type and size
				$file_type = pathinfo($path_source, PATHINFO_EXTENSION);
				$file_size = filesize($path_source);
				//Connecting to MySQL
				$conn = mysqli_connect('localhost', 'root',
					'17082015'
				);
				//Check if connection is good
				if (!$conn) {
					die("Connection failed" . mysqli_connect_error());
				} else {
					//Selecting 'pagination' database
					mysqli_select_db($conn, 'pagination');
				}
				$table_name = "";
				if ($file_type != "jpg" && $file_type != "png" && $file_type != "jpeg"){
					$table_name = "none_image_file";
				}else{
					$table_name = "image";
				}
				$query = "INSERT INTO `$table_name` (`origin`, `type`, `size`, `name`)
				VALUES ('$entree', '$file_type', '$file_size', '$path_source');";

			if (mysqli_query($conn, $query)) {
				echo "File Recorded: ". $entree . "<br>";
			} else {
				echo "Error: " . $query . "<br>";
				}
			}
		}
	}
	closedir($folder);
}
?>
<P>
<B>FINNNNNN DU PROCESSUS :</B>
<BR>
<?php echo " ", date ("h:i:s"); ?>
</P>
