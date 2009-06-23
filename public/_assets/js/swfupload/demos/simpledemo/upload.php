<?php
	$upload_directory = '' ; // leave blank for default

	#	CREATE DEFAULT UPLOAD DIRECTY LOCATION
	If ( !$upload_directory )
	{
		$upload_directory = 'uploads' ; 
		$parent_dir = array_pop(explode(DIRECTORY_SEPARATOR, dirname(__FILE__)));
		$upload_directory = substr(dirname(__FILE__), 0, strlen(dirname(__FILE__)) - strlen($parent_dir) ) . $upload_directory ; 
	}

	#	TEST UPLOAD DIRECTORY

	If ( !file_exists($upload_directory) )
	{	
		echo "The assigned SWFUpload directory, \"$upload_directory\" does not exist."; 
		die();
	}

	$uploadfile = $upload_directory. DIRECTORY_SEPARATOR . basename($_FILES['Filedata']['name']);   
	if ( !is_writable($upload_directory) )
	{
		echo "The directory, \"$upload_directory\" is not writable by PHP. Permissions must be changed to upload files."; 
		$upload_directory_writable = false ;
	}
	else
		$upload_directory_writable = true ;

		
	// Work-around for setting up a session because Flash Player doesn't send the cookies
	if (isset($_POST["PHPSESSID"]))
		session_id($_POST["PHPSESSID"]);

	session_start();

	if ( !isset($_FILES["Filedata"]) || !is_uploaded_file($_FILES["Filedata"]["tmp_name"]) || $_FILES["Filedata"]["error"] != 0)
	{
		#	UPLOAD FAILURE REPORT
		if ( $upload_email_reporting == true )
		{
			switch ($_FILES['Filedata']["error"]) {	
				case 1: $error_msg = 'File exceeded maximum server upload size of '.ini_get('upload_max_filesize').'.'; break;
				case 2: $error_msg = 'File exceeded maximum file size.'; break;
				case 3: $error_msg = 'File only partially uploaded.'; break;
				case 4: $error_msg = 'No file uploaded.'; break; 
			}
			echo "SWFUpload Failure: ".$_FILES["Filedata"]["name"],'PHP Error: '.$error_msg."\n\n".'Save Path: '.$uploadfile."\n\n".'$_FILES data: '."\n".print_r($_FILES,true); 
		}
		echo "There was a problem with the upload";
		exit(0);
	}
	else
	{
		#	COPY UPLOAD SUCCESS/FAILURE REPORT
		if ($upload_directory_writable == true )
		{
			if ( move_uploaded_file( $_FILES['Filedata']['tmp_name'] , $uploadfile ) )
			{
			 echo "SWFUpload File Saved: ".$_FILES["Filedata"]["name"],'Save Path: '.$uploadfile."\n\n".'$_FILES data: '."\n";
			 print_r($_FILES,true); 
			}
			else
			{
			 echo "SWFUpload File Not Saved: ".$_FILES["Filedata"]["name"],'Save Path: '.$uploadfile."\n\n".'$_FILES data: '."\n";
			 print_r($_FILES,true); 
			}
		}
		echo "Flash requires that we output something or it won't fire the uploadSuccess event";
	}
?>