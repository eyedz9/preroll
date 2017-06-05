

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
	$vidsize = $_POST['vidsize'];
	$spacer = '_';
	$convname = $vidsize, $spacer, $filename;
		
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
			echo ("Upload and Conversion of " .$filename. " is complete.");
			exec("/usr/bin/ffmpeg -i ".$uploadLocation.$filename." -r 25 -s ".$vidsize." ".$convertedLocation.$convname." 2>&1");
			if (file_exists("converted/" . $convname))
			{
				if(unlink("upload/" . $filename)) echo '<br />'; echo ("Deleted the uploaded source file: " . $filename);
				echo '<br /><a href="converted/'$convname.'" target="_blank"> Download Video</a><br />';
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
			<div class="fieldset">
				<label for="file">Video Size:</label>
				<select name="vidsize">
					<option value="320x480" selected="selected">Portrait (320x480)</option>
					<option value="480x320">Landscape (480x300)</option>
				</select>
			</div>
			<input id="Submit" name="submit" type="submit" value="Submit" />
		</form>
	</body>
</html>
 
