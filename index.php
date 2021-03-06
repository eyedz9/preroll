<?php
	// Upload and Rename File
	if (isset($_POST['submit'])){
		
		$filename = $_FILES["file"]["name"];
		$file_basename = substr($filename, 0, strripos($filename, '.')); // get file name
		$file_ext = substr($filename, strripos($filename, '.')); // get file extention
		$filesize = $_FILES["file"]["size"];
		$allowed_file_types = array('.mp4','.avi','.mov','.wmf','.MOV','.MP4');
		$ffmpegCommand = '/usr/bin/ffmpeg';
		$uploadLocation = 'upload/';
		$convertedLocation = 'converted/';
		$vidsize = $_POST['vidsize'];
		$vidstart = $_POST['vidstart'];
		$vidend = $_POST['vidend'];
		$spacer = '_';
		$today = date("Y_m_d_His");
		$vid_ext = '.mp4';
		$convname = $today . $spacer . $vidsize . $spacer . $vid_ext; //. $file_basename
		$loading = '/img/loading.gif';
		$thumbext = '.jpg';
		$vidthumb = $today . $spacer . $vidsize . $spacer . $file_basename . $thumbext;

			
		if (in_array($file_ext,$allowed_file_types)) {	
			// Rename file
			$newfilename = md5($file_basename) . $file_ext;
			
			if (file_exists("upload/" . $newfilename)) {
				// file already exists error
				echo "You have already uploaded this file.";
			}
			else {		
				move_uploaded_file($_FILES["file"]["tmp_name"], "upload/" . $filename);
				exec("/usr/bin/ffmpeg -i ".$uploadLocation.$filename." -vcodec libx264 -b 900k -r 25 -aspect 16:9 -s ".$vidsize." ".$convertedLocation.$convname." 2>&1");
			}
		}
		elseif (empty($file_basename)){	
			// file selection error
			echo "Please select a file to upload.";
		} 
		else {
			// file type error
			echo "Only these file typs are allowed for upload: " . implode(', ',$allowed_file_types);
			unlink($_FILES["file"]["tmp_name"]);
		}
		/*** cycle through all files in the directory ***/
		foreach (glob($convertedLocation."*") as $file) {
			/*** if file is 1 hour (3600 seconds) old then delete it ***/
			if(time() - filectime($file) > 3600){
				unlink($file);
			}
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
		<div class="contianer" style="padding-top: 2em; overflow: hidden;">
			<div class="row" style="margin-bottom: 1em;">
				<div class="col-md-4 col-md-offset-4">
					<h2 class="warning center">Please make sure file names are under 36 characters</h2>
					<form action="" enctype="multipart/form-data" method="post">
						<div class="form-group">
							<label>Select Video:</label>
							<input id="file" name="file" type="file" class="form-control" />
						</div>
						<div class="form-group">
							<label>Video Size:</label>
							<select class="form-control" name="vidsize">
								<option value="320x480" selected="selected">Portrait (320x480)</option>
								<option value="480x320">Landscape (480x320)</option>
							</select>
						</div>
						
						<input id="Submit" name="submit" type="submit" value="Submit" class="btn btn-primary" />
					</form>
				</div>
			</div>
			<?php 
				if (file_exists("converted/" . $convname)) {
					if(unlink("upload/" . $filename)) {
						echo '<div class="row"><div class="col-md-4 col-md-offset-4">';
						echo '<div class="alert alert-danger"><p>Deleted the uploaded source file: ' . $filename .'</p></div>';
						echo '<div class="alert alert-success">
							<h4>Pre-roll video ready for downlod</h4>
							<p>File Name: '.$convname.' <a href="converted/'.$convname.$vidext.'" class="btn btn-lg btn-info center-block">Download Video</a></div>';
						echo '<p>File Size: '.$filesize.' </p>';
						echo '</div></div>';
							
						if(strcmp($vidsize,'320x480')==0) {
							echo '<video controls preload=metadata width=320 height=480 style="background: #000 !important;" class="center-block">
									<source src="converted/'.$convname.$vidext.'" type="video/mp4">
								</video>';
						}
						else {
							echo '<video controls preload=metadata width=480 height=320 style="background: #000 !important;" class="center-block">
									<source src="converted/'.$convname.$vidext.'" type="video/mp4">
								</video>';
						}		
					}
				}
			 ?>
		</div>
	<!-- jQuery -->
	<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.12.4/jquery.min.js"></script>
	<!-- Latest compiled and minified JavaScript -->
	<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js" integrity="sha384-Tc5IQib027qvyjSMfHjOMaLkfuWVxZxUPnCJA7l2mCWNIpG9mGCD8wGNIcPD7Txa" crossorigin="anonymous"></script>
	</body>
</html>
