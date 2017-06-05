

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
	$convname = $vidsize . $spacer . $filename;
		
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
				echo '<br /><a href="converted/'.$convname.'"> Download Video</a><br />';
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
//
function force_download($file)
{
    $ext = explode(".", $file);
    switch($ext[sizeof($ext)-1])
   {
      case 'jar': $mime = "application/java-archive"; break;
      case 'zip': $mime = "application/zip"; break;
      case 'jpeg': $mime = "image/jpeg"; break;
      case 'jpg': $mime = "image/jpg"; break;
      case 'jad': $mime = "text/vnd.sun.j2me.app-descriptor"; break;
      case "gif": $mime = "image/gif"; break;
      case "png": $mime = "image/png"; break;
      case "pdf": $mime = "application/pdf"; break;
      case "txt": $mime = "text/plain"; break;
      case "doc": $mime = "application/msword"; break;
      case "ppt": $mime = "application/vnd.ms-powerpoint"; break;
      case "wbmp": $mime = "image/vnd.wap.wbmp"; break;
      case "wmlc": $mime = "application/vnd.wap.wmlc"; break;
      case "mp4s": $mime = "application/mp4"; break;
      case "ogg": $mime = "application/ogg"; break;
      case "pls": $mime = "application/pls+xml"; break;
      case "asf": $mime = "application/vnd.ms-asf"; break;
      case "swf": $mime = "application/x-shockwave-flash"; break;
      case "mp4": $mime = "video/mp4"; break;
      case "m4a": $mime = "audio/mp4"; break;
      case "m4p": $mime = "audio/mp4"; break;
      case "mp4a": $mime = "audio/mp4"; break;
      case "mp3": $mime = "audio/mpeg"; break;
      case "m3a": $mime = "audio/mpeg"; break;
      case "m2a": $mime = "audio/mpeg"; break;
      case "mp2a": $mime = "audio/mpeg"; break;
      case "mp2": $mime = "audio/mpeg"; break;
      case "mpga": $mime = "audio/mpeg"; break;
      case "wav": $mime = "audio/wav"; break;
      case "m3u": $mime = "audio/x-mpegurl"; break;
      case "bmp": $mime = "image/bmp"; break;
      case "ico": $mime = "image/x-icon"; break;
      case "3gp": $mime = "video/3gpp"; break;
      case "3g2": $mime = "video/3gpp2"; break;
      case "mp4v": $mime = "video/mp4"; break;
      case "mpg4": $mime = "video/mp4"; break;
      case "m2v": $mime = "video/mpeg"; break;
      case "m1v": $mime = "video/mpeg"; break;
      case "mpe": $mime = "video/mpeg"; break;
      case "mpeg": $mime = "video/mpeg"; break;
      case "mpg": $mime = "video/mpeg"; break;
      case "mov": $mime = "video/quicktime"; break;
      case "qt": $mime = "video/quicktime"; break;
      case "avi": $mime = "video/x-msvideo"; break;
      case "midi": $mime = "audio/midi"; break;
      case "mid": $mime = "audio/mid"; break;
      case "amr": $mime = "audio/amr"; break;
      default: $mime = "application/force-download";
   }
    header('Content-Description: File Transfer');
    header('Content-Type: '.$mime);
    header('Content-Disposition: attachment; filename='.basename($file));
    header('Content-Transfer-Encoding: binary');
    header('Expires: 0');
    header('Cache-Control: must-revalidate, post-check=0, pre-check=0');
    header('Pragma: public');
    header('Content-Length: ' . filesize($file));
    ob_clean();
    flush();
    readfile($file);
}
//
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
 
