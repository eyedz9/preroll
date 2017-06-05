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
	//$frate = $_POST['frate'];
	//$brate = $_POST['brate'];
	$spacer = '_';
	$today = date("Y_m_d_His");
	$vid_ext = '.mp4';
	$convname = $today . $spacer . $vidsize . $spacer . $file_basename . $vid_ext;
	$loading = '/img/loading.gif';
	
	
		
		if (in_array($file_ext,$allowed_file_types))//&& ($filesize < 200000000)
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
			exec("/usr/bin/ffmpeg -i ".$uploadLocation.$filename." -vcodec libx264 -b 900k -r 25 -s ".$vidsize." ".$convertedLocation.$convname." 2>&1");
			if (file_exists("converted/" . $convname))
			{
				if(unlink("upload/" . $filename)) echo '<br />'; echo ("Deleted the uploaded source file: " . $filename);
				echo '<br /><a href="converted/'.$convname.$vidext.'"> Download Video</a><br />';
			}
			echo ($convname);
			
		}
	}

	elseif (empty($file_basename))
	{	
		// file selection error
		echo "Please select a file to upload.";
	} 
	//elseif ($filesize > 200000000)
	//{	
		// file size error
		//echo "The file you are trying to upload is too large.";
	//}
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
					<option value="1024x768">Landscape (480x320)</option>
				</select>
			</div>
			<!--<div class="fieldset">
				<label for="file">Framerate:</label>
				<select name="frate">
					<option value="25" selected="selected">Default 25fps</option>
					<option value="30">30fps</option>
				</select>
			</div>
			<div class="fieldset">
				<label for="file">Bitrate:</label>
				<select name="brate">
					<option value="1200" selected="selected">Default 1.2mb</option>
					<option value="2000">High 2.0mb</option>
					<option value="700">Low 700kb</option>
				</select>
			</div>-->
			<input id="Submit" name="submit" type="submit" value="Submit" />
		</form>
	</body>
</html>
