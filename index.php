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
	$thumbext = '.jpg';
	$vidthumb = $today . $spacer . $vidsize . $spacer . $file_basename . $thumbext;
	
	
		
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
			
			exec("/usr/bin/ffmpeg -i ".$uploadLocation.$filename." -vcodec libx264 -b 900k -r 25 -s ".$vidsize." -aspect 16:9 ".$convertedLocation.$convname." 2>&1");
			if (file_exists("converted/" . $convname))
			{
				if(unlink("upload/" . $filename)) echo '<br />'; echo ("Deleted the uploaded source file: " . $filename);
				//exec("/usr/bin/ffmpeg -ss 0.10 -i ".$convertedLocation.$convname." -t 1 -aspect 16:9 -f image2 -s 320x480 ".$convertedLocation.$vidthumb."");
				//echo '<br /><img src="'.$convertedLocation.$vidthumb.'" /><br />';
				echo '<h3>Download Pre-roll video</h3><br /><a href="converted/'.$convname.$vidext.'">'.$convname.'</a><br /></br>';
			}
			
			
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
<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="utf-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<!-- The above 3 meta tags *must* come first in the head; any other head content must come *after* these tags -->
	<title>Pre-roll upload</title>
	<!-- Latest compiled and minified CSS -->
	<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css" integrity="sha384-BVYiiSIFeK1dGmJRAkycuHAHRg32OmUcww7on3RYdg4Va+PmSTsz/K68vbdEjh4u" crossorigin="anonymous">
	<!-- Optional theme -->
	<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap-theme.min.css" integrity="sha384-rHyoN1iRsVXV4nD0JutlnGaslCJuC7uwjduW9SVrLvRYooPp2bWYgmgJQIXwl/Sp" crossorigin="anonymous">
</head>
	<body>
		<form action="" enctype="multipart/form-data" method="post">
			<input id="file" name="file" type="file" />
			<div class="fieldset">
				<label for="file">Video Size:</label>
				<select name="vidsize">
					<option value="320x480" selected="selected">Portrait (320x480)</option>
					<option value="480x320">Landscape (480x320)</option>
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
		<!-- jQuery (necessary for Bootstrap's JavaScript plugins) -->
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.12.4/jquery.min.js"></script>
    <!-- Include all compiled plugins (below), or include individual files as needed -->
    <!-- Latest compiled and minified JavaScript -->
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js" integrity="sha384-Tc5IQib027qvyjSMfHjOMaLkfuWVxZxUPnCJA7l2mCWNIpG9mGCD8wGNIcPD7Txa" crossorigin="anonymous"></script>
	</body>
</html>
