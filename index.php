

<?php

// Upload and Rename File

if (isset($_POST['submit']))
{
	$filename = $_FILES["file"]["name"];
	$file_basename = substr($filename, 0, strripos($filename, '.')); // get file extention
	$file_ext = substr($filename, strripos($filename, '.')); // get file name
	$filesize = $_FILES["file"]["size"];
	$allowed_file_types = array('.mp4','.avi','.mov','.wmf','.MOV','.MP4');
	$ffmpegCommand = '/usr/bin/ffmpeg';
	$uploadLocation = 'upload/';
	$convertedLocation = 'converted/';
	$direct_text = 'Deleted source file ';
		
		if (in_array($file_ext,$allowed_file_types) && ($filesize < 20000000))
	{	
		// Rename file
		$newfilename = md5($file_basename) . $file_ext;

		if (file_exists("upload/" . $newfilename))
		{
			// file already exists error
			echo "You have already uploaded this file.";
		}
		else
		{		
			
			move_uploaded_file($_FILES["file"]["tmp_name"], "upload/" . $filename);
			echo $uploadLocation, $filename;
			exec("/usr/bin/ffmpeg -i ".$uploadLocation.$filename." -r 25 -s 320x480 ".$convertedLocation.$filename." 2>&1");
			if (file_exists("converted/" . $filename))
			{
				echo '<br /><a href="converted/'.$filename.'">Download Video</a>';
				if(unlink("upload/" . $filename)) echo ($direct_text . $filename);
			}			
			
		}
	}

	elseif (empty($file_basename))
	{	
		// file selection error
		echo "Please select a file to upload.";
	} 
	elseif ($filesize > 20000000)
	{	
		// file size error
		echo "The file you are trying to upload is too large.";
	}
	else
	{
		// file type error
		echo "Only these file typs are allowed for upload: " . implode(', ',$allowed_file_types);
		unlink($_FILES["file"]["tmp_name"]);
	}
}

?>
<html>
<head>
	<title>Pre-roll upload</title>
</head>
	<body>
		<form action="" enctype="multipart/form-data" method="post">
			<input id="file" name="file" type="file" />
			<input id="Submit" name="submit" type="submit" value="Submit" />
		</form>
	</body>
</html>
 
